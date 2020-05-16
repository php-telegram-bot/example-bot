<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpTelegramBot\Core\Commands\UserCommands;

use PhpTelegramBot\Core\Commands\UserCommand;
use PhpTelegramBot\Core\Entities\InlineKeyboard;
use PhpTelegramBot\Core\Request;

/**
 * User "/inlinekeyboard" command
 *
 * Display an inline keyboard with a few buttons.
 */
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
    protected $version = '0.1.0';

    /**
     * Command execute method
     *
     * @return \PhpTelegramBot\Core\Entities\ServerResponse
     * @throws \PhpTelegramBot\Core\Exception\TelegramException
     */
    public function execute()
    {
        $chat_id = $this->getMessage()->getChat()->getId();

        $switch_element = mt_rand(0, 9) < 5 ? 'true' : 'false';

        $inline_keyboard = new InlineKeyboard([
            ['text' => 'inline', 'switch_inline_query' => $switch_element],
            ['text' => 'inline current chat', 'switch_inline_query_current_chat' => $switch_element],
        ], [
            ['text' => 'callback', 'callback_data' => 'identifier'],
            ['text' => 'open url', 'url' => 'https://github.com/php-telegram-bot/core'],
        ]);

        $data = [
            'chat_id'      => $chat_id,
            'text'         => 'inline keyboard',
            'reply_markup' => $inline_keyboard,
        ];

        return Request::sendMessage($data);
    }
}
