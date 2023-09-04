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
require_once $path . '/texts.php';

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;

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

        $result = Request::emptyResponse();
        $data = [
            'chat_id'      => $chat_id,
            // Remove any keyboard by default
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        if ('stop_cancle' === $callback_data) {
            $result = Request::editMessageText([
                'chat_id'    => $chat_id,
                'message_id' => $message->getMessageId(),
                'text'       => getTextValue('exit_command_cancle')
            ]);
            // $result = Request::sendMessage($data);
            return $result;
        }
        if ('stop_command' === $callback_data) {
            $this->conversation->stop();
            $result = Request::editMessageText([
                'chat_id'    => $chat_id,
                'message_id' => $message->getMessageId(),
                'text'       => getTextValue('exit_command_approve')
            ]);
            return $result;
        }

        // Load any existing notes from this conversation
        $notes = &$this->conversation->notes;
        $state = $notes['state'] ?? 0;
        if ($state === 0) {
            $positions = getPositionsArray();
            if ('complete_position' !== $callback_data) {
                if ($notes['position']) {
                    if (in_array($callback_data, $notes['position'])) {
                        $notes['position'] = array_filter($notes['position'], static function ($element) use ($callback_data) {
                            return $element !== $callback_data;
                        });
                    } else {
                        $notes['position'] = [$callback_data, ...($notes['position'] ?? [])];
                    }
                } else {
                    $notes['position'] = [$callback_data, ...($notes['position'] ?? [])];
                }

                $positions = count($notes['position']) > 0 ? [
                    ...$positions,
                    [['text' => getTextValue('state_0_selected'), 'callback_data' => 'complete_position']]
                ] : $positions;
            } else {
                $notes['state'] = 1;


                $data['text'] =  getTextValue('state_1');
                $result = Request::sendMessage($data);
            }

            foreach ($notes['position'] ?? [] as $key => $value) {
                $positions = changePositionText($positions, $value, '✅' . getTextByData($value));
            }

            $this->conversation->update();

            $text = getTextValue('state_0');
            $result = Request::editMessageText([
                'chat_id'    => $chat_id,
                'message_id' => $message->getMessageId(),
                'text'       => $text, 
                'reply_markup' => new InlineKeyboard(...$positions) 
            ]);
        }


        return $result;
    }
}
