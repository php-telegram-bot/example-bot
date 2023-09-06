<?php declare(strict_types=1);
/**
 * This file is part of the TelegramBotManager package.
 *
 * (c) Armando Lüscher <armando@noplanman.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TelegramBot\TelegramBotManager;

use Closure;
use Exception;
use Longman\IPTools\Ip;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ChosenInlineResult;
use Longman\TelegramBot\Entities\InlineQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use TelegramBot\TelegramBotManager\Exception\InvalidAccessException;
use TelegramBot\TelegramBotManager\Exception\InvalidActionException;
use TelegramBot\TelegramBotManager\Exception\InvalidParamsException;
use TelegramBot\TelegramBotManager\Exception\InvalidWebhookException;

class BotManager
{
    public const VERSION = '2.0.0';

    /**
     * @link https://core.telegram.org/bots/webhooks#the-short-version
     * @var array Telegram webhook servers IP ranges
     */
    public const TELEGRAM_IP_RANGES = ['149.154.160.0/20', '91.108.4.0/22'];

    private string $output = '';
    private Telegram $telegram;
    private Params $params;
    private Action $action;
    private ?Closure $custom_get_updates_callback = null;

    /**
     * @throws InvalidParamsException
     * @throws InvalidActionException
     * @throws TelegramException
     */
    public function __construct(array $params)
    {
        $this->params = new Params($params);
        $this->action = new Action($this->params->getScriptParam('a'));

        $this->telegram = new Telegram(
            $this->params->getBotParam('api_key'),
            $this->params->getBotParam('bot_username') ?? ''
        );
    }

    public static function inTest(): bool
    {
        return defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE === true;
    }

    public function getTelegram(): Telegram
    {
        return $this->telegram;
    }

    public function getParams(): Params
    {
        return $this->params;
    }

    public function getAction(): Action
    {
        return $this->action;
    }

    /**
     * @throws TelegramException
     * @throws InvalidAccessException
     * @throws InvalidWebhookException
     * @throws Exception
     */
    public function run(): static
    {
        $this->validateSecret();
        $this->validateRequest();

        if ($this->action->isAction('webhookinfo')) {
            $webhookinfo = Request::getWebhookInfo();
            /** @noinspection ForgottenDebugOutputInspection */
            print_r($webhookinfo->getResult() ?: $webhookinfo->printError(true));
            return $this;
        }
        if ($this->action->isAction(['set', 'unset', 'reset'])) {
            return $this->validateAndSetWebhook();
        }

        $this->setBotExtras();

        if ($this->action->isAction('handle')) {
            $this->handleRequest();
        } elseif ($this->action->isAction('cron')) {
            $this->handleCron();
        }

        return $this;
    }

    /**
     * @throws InvalidAccessException
     */
    public function validateSecret(bool $force = false): static
    {
        // If we're running from CLI, secret isn't necessary.
        if ($force || 'cli' !== PHP_SAPI) {
            $secret     = $this->params->getBotParam('secret');
            $secret_get = $this->params->getScriptParam('s');
            if (!isset($secret, $secret_get) || $secret !== $secret_get) {
                throw new InvalidAccessException('Invalid access');
            }
        }

        return $this;
    }

    /**
     * @throws TelegramException
     * @throws InvalidWebhookException
     */
    public function validateAndSetWebhook(): static
    {
        $webhook = $this->params->getBotParam('webhook');
        if (empty($webhook['url'] ?? null) && $this->action->isAction(['set', 'reset'])) {
            throw new InvalidWebhookException('Invalid webhook');
        }

        if ($this->action->isAction(['unset', 'reset'])) {
            $this->handleOutput($this->telegram->deleteWebhook()->getDescription() . PHP_EOL);
            // When resetting the webhook, sleep for a bit to prevent too many requests.
            $this->action->isAction('reset') && sleep(1);
        }

        if ($this->action->isAction(['set', 'reset'])) {
            $webhook_params = array_filter([
                'certificate'     => $webhook['certificate'] ?? null,
                'max_connections' => $webhook['max_connections'] ?? null,
                'allowed_updates' => $webhook['allowed_updates'] ?? null,
                'secret_token'    => $webhook['secret_token'] ?? null,
            ], function ($v, $k) {
                if ($k === 'allowed_updates') {
                    // Special case for allowed_updates, which can be an empty array.
                    return is_array($v);
                }
                return !empty($v);
            }, ARRAY_FILTER_USE_BOTH);

            $webhook['url'] .= parse_url($webhook['url'], PHP_URL_QUERY) === null ? '?' : '&';
            $this->handleOutput(
                $this->telegram->setWebhook(
                    $webhook['url'] . 'a=handle&s=' . $this->params->getBotParam('secret'),
                    $webhook_params
                )->getDescription() . PHP_EOL
            );
        }

        return $this;
    }

    private function handleOutput(string $output): static
    {
        $this->output .= $output;

        if (!self::inTest()) {
            echo $output;
        }

        return $this;
    }

    /**
     * @throws TelegramException
     */
    public function setBotExtras(): static
    {
        $this->setBotExtrasTelegram();
        $this->setBotExtrasRequest();

        return $this;
    }

    /**
     * @throws TelegramException
     */
    protected function setBotExtrasTelegram(): static
    {
        $simple_extras = [
            'admins'         => 'enableAdmins',
            'commands.paths' => 'addCommandsPaths',
            'custom_input'   => 'setCustomInput',
            'paths.download' => 'setDownloadPath',
            'paths.upload'   => 'setUploadPath',
        ];
        // For simple telegram extras, just pass the single param value to the Telegram method.
        foreach ($simple_extras as $param_key => $method) {
            $param = $this->params->getBotParam($param_key);
            if (null !== $param) {
                $this->telegram->$method($param);
            }
        }

        // Database.
        if ($mysql_config = $this->params->getBotParam('mysql', [])) {
            $this->telegram->enableMySql(
                $mysql_config,
                $mysql_config['table_prefix'] ?? '',
                $mysql_config['encoding'] ?? 'utf8mb4'
            );
        }

        // Custom command configs.
        $command_configs = $this->params->getBotParam('commands.configs', []);
        foreach ($command_configs as $command => $config) {
            $this->telegram->setCommandConfig($command, $config);
        }

        return $this;
    }

    /**
     * @throws TelegramException
     */
    protected function setBotExtrasRequest(): static
    {
        $request_extras = [
            // None at the moment...
        ];
        // For request extras, just pass the single param value to the Request method.
        foreach ($request_extras as $param_key => $method) {
            $param = $this->params->getBotParam($param_key);
            if (null !== $param) {
                Request::$method($param);
            }
        }

        // Special cases.
        $limiter_enabled = $this->params->getBotParam('limiter.enabled');
        if ($limiter_enabled !== null) {
            $limiter_options = $this->params->getBotParam('limiter.options', []);
            Request::setLimiter($limiter_enabled, $limiter_options);
        }

        return $this;
    }

    /**
     * @throws TelegramException
     */
    public function handleRequest(): static
    {
        if ($this->params->getBotParam('webhook.url')) {
            return $this->handleWebhook();
        }

        if ($loop_time = $this->getLoopTime()) {
            return $this->handleGetUpdatesLoop($loop_time, $this->getLoopInterval());
        }

        return $this->handleGetUpdates();
    }

    /**
     * @throws TelegramException
     */
    public function handleCron(): static
    {
        $groups = explode(',', $this->params->getScriptParam('g', 'default'));

        $commands = [];
        foreach ($groups as $group) {
            $commands[] = $this->params->getBotParam('cron.groups.' . $group, []);
        }
        $this->telegram->runCommands(array_merge(...$commands));

        return $this;
    }

    public function getLoopTime(): int
    {
        $loop_time = $this->params->getScriptParam('l');

        if (null === $loop_time) {
            return 0;
        }

        if (is_string($loop_time) && '' === trim($loop_time)) {
            return 604800; // Default to 7 days.
        }

        return max(0, (int) $loop_time);
    }

    public function getLoopInterval(): int
    {
        $interval_time = $this->params->getScriptParam('i');

        if (null === $interval_time || (is_string($interval_time) && '' === trim($interval_time))) {
            return 2;
        }

        // Minimum interval is 1 second.
        return max(1, (int) $interval_time);
    }

    /**
     * @throws TelegramException
     */
    public function handleGetUpdatesLoop(int $loop_time_in_seconds, int $loop_interval_in_seconds = 2): static
    {
        // Remember the time we started this loop.
        $now = time();

        $this->handleOutput('Looping getUpdates until ' . date('Y-m-d H:i:s', $now + $loop_time_in_seconds) . PHP_EOL);

        while ($now > time() - $loop_time_in_seconds) {
            $this->handleGetUpdates();

            // Chill a bit.
            sleep($loop_interval_in_seconds);
        }

        return $this;
    }

    public function setCustomGetUpdatesCallback(callable $callback): static
    {
        $this->custom_get_updates_callback = Closure::fromCallable($callback);

        return $this;
    }

    /**
     * @throws TelegramException
     */
    public function handleGetUpdates(): static
    {
        $get_updates_response = $this->telegram->handleGetUpdates();

        // Check if the user has set a custom callback for handling the response.
        if ($this->custom_get_updates_callback) {
            $this->handleOutput(($this->custom_get_updates_callback)($get_updates_response));
        } else {
            $this->handleOutput($this->defaultGetUpdatesCallback($get_updates_response));
        }

        return $this;
    }

    protected function defaultGetUpdatesCallback(ServerResponse $get_updates_response): string
    {
        if (!$get_updates_response->isOk()) {
            return sprintf(
                '%s - Failed to fetch updates' . PHP_EOL . '%s',
                date('Y-m-d H:i:s'),
                $get_updates_response->printError(true)
            );
        }

        /** @var Update[] $results */
        $results = array_filter((array) $get_updates_response->getResult());

        $output = sprintf(
            '%s - Updates processed: %d' . PHP_EOL,
            date('Y-m-d H:i:s'),
            count($results)
        );

        foreach ($results as $result) {
            $update_content = $result->getUpdateContent();

            $chat_id = 'n/a';
            $text    = $result->getUpdateType();

            if ($update_content instanceof Message) {
                $chat_id = $update_content->getChat()->getId();
                $text    .= ";{$update_content->getType()}";
            } elseif ($update_content instanceof InlineQuery || $update_content instanceof ChosenInlineResult) {
                $chat_id = $update_content->getFrom()->getId();
                $text    .= ";{$update_content->getQuery()}";
            } elseif ($update_content instanceof CallbackQuery) {
                $message = $update_content->getMessage();
                if ($message && $message->getChat()) {
                    $chat_id = $message->getChat()->getId();
                }

                $text .= ";{$update_content->getData()}";
            }

            $output .= sprintf(
                '%s: <%s>' . PHP_EOL,
                $chat_id,
                preg_replace('/\s+/', ' ', trim($text))
            );
        }

        return $output;
    }

    /**
     * @throws TelegramException
     */
    public function handleWebhook(): static
    {
        $this->telegram->handle();

        return $this;
    }

    public function getOutput(): string
    {
        $output       = $this->output;
        $this->output = '';

        return $output;
    }

    public function isValidRequest(): bool
    {
        // If we're running from CLI, requests are always valid, unless we're running the tests.
        if ((!self::inTest() && 'cli' === PHP_SAPI) || false === $this->params->getBotParam('validate_request')) {
            return true;
        }

        return $this->isValidRequestIp()
            && $this->isValidRequestSecretToken();
    }

    protected function isValidRequestIp(): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR'] as $key) {
            if (filter_var($_SERVER[$key] ?? null, FILTER_VALIDATE_IP)) {
                $ip = $_SERVER[$key];
                break;
            }
        }

        return Ip::match($ip, array_merge(
            self::TELEGRAM_IP_RANGES,
            (array) $this->params->getBotParam('valid_ips', [])
        ));
    }

    protected function isValidRequestSecretToken(): bool
    {
        $secret_token     = $this->params->getBotParam('webhook.secret_token');
        $secret_token_api = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? null;

        if ($secret_token || $secret_token_api) {
            return $secret_token === $secret_token_api;
        }

        return true;
    }

    /**
     * @throws InvalidAccessException
     */
    private function validateRequest(): void
    {
        if (!$this->isValidRequest()) {
            throw new InvalidAccessException('Invalid access');
        }
    }
}
