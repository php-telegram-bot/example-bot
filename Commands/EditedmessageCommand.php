<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;

/**
 * Edited message command
 *
 * Gets executed when a user message is edited.
 */
class EditedmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'editedmessage';

    /**
     * @var string
     */
    protected $description = 'User edited message';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        //$edited_message = $this->getEditedMessage();

        return parent::execute();
    }
}
