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
 * Edited channel post command
 *
 * Gets executed when a post in a channel is edited.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class EditedchannelpostCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'editedchannelpost';

    /**
     * @var string
     */
    protected $description = 'Handle edited channel post';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        // Get the edited channel post
        $edited_channel_post = $this->getEditedChannelPost();

        return parent::execute();
    }
}
