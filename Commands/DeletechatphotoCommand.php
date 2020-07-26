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
 * Delete chat photo command
 *
 * Gets executed when the photo of a group or channel gets deleted.
 */
class DeletechatphotoCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'deletechatphoto';

    /**
     * @var string
     */
    protected $description = 'Delete chat photo';

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
        //$delete_chat_photo = $message->getDeleteChatPhoto();

        return parent::execute();
    }
}
