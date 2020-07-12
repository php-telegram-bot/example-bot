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

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;

/**
 * Pinned message command
 *
 * Gets executed when a message gets pinned.
 */
class PinnedmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'pinnedmessage';

    /**
     * @var string
     */
    protected $description = 'Message was pinned';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$pinned_message = $message->getPinnedMessage();

        return parent::execute();
    }
}
