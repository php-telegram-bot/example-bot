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
 * Group chat created command
 *
 * Gets executed when a group chat is created.
 */
class GroupchatcreatedCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'groupchatcreated';

    /**
     * @var string
     */
    protected $description = 'Group chat created';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \PhpTelegramBot\Core\Entities\ServerResponse
     * @throws \PhpTelegramBot\Core\Exception\TelegramException
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$group_chat_created = $message->getGroupChatCreated();

        return parent::execute();
    }
}
