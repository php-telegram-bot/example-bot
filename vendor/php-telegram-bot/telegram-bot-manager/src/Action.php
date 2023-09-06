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

use TelegramBot\TelegramBotManager\Exception\InvalidActionException;

class Action
{
    private static array $valid_actions = [
        'set',
        'unset',
        'reset',
        'handle',
        'cron',
        'webhookinfo',
    ];

    private string $action;

    /**
     * @throws InvalidActionException
     */
    public function __construct(?string $action = 'handle')
    {
        $this->action = $action ?: 'handle';

        if (!$this->isAction(self::$valid_actions)) {
            throw new InvalidActionException('Invalid action: ' . $this->action);
        }
    }

    public function isAction(array|string $actions): bool
    {
        return in_array($this->action, (array) $actions, true);
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public static function getValidActions(): array
    {
        return self::$valid_actions;
    }
}
