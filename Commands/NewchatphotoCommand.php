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
 * New chat photo command
 *
 * Gets executed when the photo of a group or channel gets set.
 */
class NewchatphotoCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'newchatphoto';

    /**
     * @var string
     */
    protected $description = 'New chat Photo';

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
        //$new_chat_photo = $message->getNewChatPhoto();

        return parent::execute();
    }
}
