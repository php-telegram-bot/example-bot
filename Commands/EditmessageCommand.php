<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/editmessage" command
 *
 * Command to edit a message via bot.
 */
class EditmessageCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'editmessage';

    /**
     * @var string
     */
    protected $description = 'Edit message';

    /**
     * @var string
     */
    protected $usage = '/editmessage';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message          = $this->getMessage();
        $chat_id          = $message->getChat()->getId();
        $reply_to_message = $message->getReplyToMessage();
        $text             = $message->getText(true);

        if ($reply_to_message && $message_to_edit = $reply_to_message->getMessageId()) {
            $data_edit = [
                'chat_id'    => $chat_id,
                'message_id' => $message_to_edit,
                'text'       => $text ?: 'Edited message',
            ];

            // Try to edit selected message.
            $result = Request::editMessageText($data_edit);

            if ($result->isOk()) {
                // Delete this editing reply message.
                Request::deleteMessage([
                    'chat_id'    => $chat_id,
                    'message_id' => $message->getMessageId(),
                ]);
            }

            return $result;
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => sprintf("Reply to any bots' message and use /%s <your text> to edit it.", $this->name),
        ];

        return Request::sendMessage($data);
    }
}
