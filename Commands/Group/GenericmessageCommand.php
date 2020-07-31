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
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 *
 * In this group-related context, we can handle new and left group members.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        // Handle new chat members
        if ($message->getNewChatMembers()) {
            return $this->getTelegram()->executeCommand('newchatmembers');
        }

        // Handle left chat members
        if ($message->getLeftChatMember()) {
            return $this->getTelegram()->executeCommand('leftchatmember');
        }

        // The chat photo was changed
        if ($new_chat_photo = $message->getNewChatPhoto()) {
            // Whatever...
        }

        // The chat title was changed
        if ($new_chat_title = $message->getNewChatTitle()) {
            // Whatever...
        }

        // A message has been pinned
        if ($pinned_message = $message->getPinnedMessage()) {
            // Whatever...
        }

        return Request::emptyResponse();
    }
}
