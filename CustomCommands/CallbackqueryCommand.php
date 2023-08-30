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
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */


namespace Longman\TelegramBot\Commands\SystemCommands;

define('__ROOT__', dirname(dirname(__FILE__)));

require_once('positions.php');

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\Keyboard;

class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Handle the callback query';

    /**
     * @var string
     */
    protected $version = '1.2.0';


    /**
     * Conversation Object
     *
     * @var Conversation
     */
    protected $conversation;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws \Exception
     */
    public function execute(): ServerResponse
    {
        // Callback query data can be fetched and handled accordingly.
        $callback_query = $this->getCallbackQuery();
        $callback_data  = $callback_query->getData();
        $message = $callback_query->getMessage();
        $chat    = $message->getChat();
        $user    = $message->getFrom();
        // $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        // $user_id = $user->getId();

        $this->conversation = new Conversation($chat_id, $chat_id, 'start');

        // Load any existing notes from this conversation
        $notes = &$this->conversation->notes;
        $state = $notes['state'] ?? 0;
        if ($state === 0) {
            $notes['position'] = $callback_data;
            $notes['state'] = 1;
            $this->conversation->update();
        }
        $data = [
            'chat_id'      => $chat_id,
            // Remove any keyboard by default
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];
        $data['text'] =  'Как Вас зовут(ФИО):';
        $result = Request::sendMessage($data);

        $result = Request::editMessageText([
            'chat_id'    => $chat_id,
            'message_id' => $message->getMessageId(),
            'text'       => 'Вы выбрали позицию: ' . getTextByData($callback_data),
        ]);
        return $result;
        // return $this->replyToChat(
        //     'Ваше имя',
        //     ['parse_mode' => 'markdown']
        // );
        // return $callback_query->answer([
        //     'text'       => 'Content of the callback data: ' . $callback_data,
        // ]);
    }
}
