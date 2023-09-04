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

/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 *
 * When using deep-linking, the parameter can be accessed by getting the command text.
 *
 * @see https://core.telegram.org/bots#deep-linking
 */

namespace Longman\TelegramBot\Commands\SystemCommands;


require_once $path . '/texts.php';

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\InlineKeyboard;



class ExitCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'exit';

    /**
     * @var string
     */
    protected $description = 'Exit command';

    /**
     * @var string
     */
    protected $usage = '/pstart';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {

        $message = $this->getMessage();

        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        $data = [
            'chat_id'      => $chat_id,
            // Remove any keyboard by default
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        $data['text'] = getTextValue('exit_command_qustion');//'Вы точно хотите отменить заполнение анкеты?';
        $data['reply_markup'] = new InlineKeyboard([
            ['text' => getTextValue('exit_command_yes'), 'callback_data' => 'stop_command'],
            ['text' => getTextValue('exit_command_no'), 'callback_data' => 'stop_cancle']
        ]);

        return Request::sendMessage($data);
    }
}
