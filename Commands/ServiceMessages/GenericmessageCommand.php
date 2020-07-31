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
 * In this service-message-related context, we can handle any incoming service-messages.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
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
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        /**
         * Catch and handle any service messages here.
         */

        // The chat photo was deleted
        $delete_chat_photo = $message->getDeleteChatPhoto();

        // The group has been created
        $group_chat_created = $message->getGroupChatCreated();

        // The supergroup has been created
        $supergroup_chat_created = $message->getSupergroupChatCreated();

        // The channel has been created
        $channel_chat_created = $message->getChannelChatCreated();

        // Information about the payment
        $successful_payment = $message->getSuccessfulPayment();

        return Request::emptyResponse();
    }
}
