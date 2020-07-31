<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

/**
 * User "/inlinekeyboard" command
 *
 * Display an inline keyboard with a few buttons.
 *
 * This command requires CallbackqueryCommand to work!
 *
 * @see CallbackqueryCommand.php
 */

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class InlinekeyboardCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'inlinekeyboard';

    /**
     * @var string
     */
    protected $description = 'Show inline keyboard';

    /**
     * @var string
     */
    protected $usage = '/inlinekeyboard';

    /**
     * @var string
     */
    protected $version = '0.2.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $inline_keyboard = new InlineKeyboard([
            ['text' => 'Inline Query (current chat)', 'switch_inline_query_current_chat' => 'inline query...'],
            ['text' => 'Inline Query (other chat)', 'switch_inline_query' => 'inline query...'],
        ], [
            ['text' => 'Callback', 'callback_data' => 'identifier'],
            ['text' => 'Open URL', 'url' => 'https://github.com/php-telegram-bot/example-bot'],
        ]);

        return $this->replyToChat('Inline Keyboard', [
            'reply_markup' => $inline_keyboard,
        ]);
    }
}
