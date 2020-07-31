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
 * User "/editmessage" command
 *
 * Command to edit a message sent by the bot.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class EditmessageCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'editmessage';

    /**
     * @var string
     */
    protected $description = 'Edit a message sent by the bot';

    /**
     * @var string
     */
    protected $usage = '/editmessage';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message          = $this->getMessage();
        $chat_id          = $message->getChat()->getId();
        $reply_to_message = $message->getReplyToMessage();
        $text             = $message->getText(true);

        if ($reply_to_message && $message_to_edit = $reply_to_message->getMessageId()) {
            // Try to edit the selected message.
            $result = Request::editMessageText([
                'chat_id'    => $chat_id,
                'message_id' => $message_to_edit,
                'text'       => $text ?: 'Edited message',
            ]);

            // If successful, delete this editing reply message.
            if ($result->isOk()) {
                Request::deleteMessage([
                    'chat_id'    => $chat_id,
                    'message_id' => $message->getMessageId(),
                ]);
            }

            return $result;
        }

        return $this->replyToChat(sprintf("Reply to any bots' message and use /%s <your text> to edit it.", $this->getName()));
    }
}
