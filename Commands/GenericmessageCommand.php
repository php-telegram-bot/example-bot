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
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
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
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function executeNoDb(): \Longman\TelegramBot\Entities\ServerResponse
    {
        // Do nothing
        return Request::emptyResponse();
    }

    /**
     * Main command execution
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute(): \Longman\TelegramBot\Entities\ServerResponse
    {
        $message = $this->getMessage();

        // If a conversation is busy, execute the conversation command after handling the message.
        $conversation = new Conversation(
            $message->getFrom()->getId(),
            $message->getChat()->getId()
        );

        // Fetch conversation command if it exists and execute it.
        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->telegram->executeCommand($command);
        }

        /**
         * Here, any service messages can be caught and handled.
         *
         * Service messages are:
         * delete_chat_photo - the chat photo was deleted
         * group_chat_created - the group has been created
         * supergroup_chat_created - the supergroup has been created
         * channel_chat_created - the channel has been created
         * successful_payment - information about the payment
         */

        /**
         * For special message commands, you need to call them from here.
         *
         * // Handle new chat members
         * if ($message->getNewChatMembers()) {
         *     return $this->getTelegram()->executeCommand('newchatmembers');
         * }
         *
         * // Handle left chat members
         * if ($message->getLeftChatMember()) {
         *       return $this->getTelegram()->executeCommand('leftchatmember');
         * }
         *
         * // Handle group actions
         * if ($new_chat_photo = $message->getNewChatPhoto()) {
         *     // Whatever...
         * }
         * if ($new_chat_title = $message->getNewChatTitle()) {
         *     // Whatever...
         * }
         *
         * // Message pinning
         * if ($pinned_message = $message->getPinnedMessage()) {
         *     // Whatever...
         * }
         */

        return Request::emptyResponse();
    }
}
