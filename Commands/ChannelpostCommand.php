<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpTelegramBot\Core\Commands\SystemCommands;

use PhpTelegramBot\Core\Commands\SystemCommand;

/**
 * Channel post command
 *
 * Gets executed when a new post is created in a channel.
 */
class ChannelpostCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'channelpost';

    /**
     * @var string
     */
    protected $description = 'Handle channel post';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return \PhpTelegramBot\Core\Entities\ServerResponse
     * @throws \PhpTelegramBot\Core\Exception\TelegramException
     */
    public function execute()
    {
        //$channel_post = $this->getUpdate()->getChannelPost();

        return parent::execute();
    }
}
