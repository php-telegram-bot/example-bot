<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpTelegramBot\Core\Commands\UserCommands;

use PhpTelegramBot\Core\Commands\UserCommand;
use PhpTelegramBot\Core\Entities\Keyboard;
use PhpTelegramBot\Core\Request;

/**
 * User "/forcereply" command
 *
 * Force a reply to a message.
 */
class ForcereplyCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'forcereply';

    /**
     * @var string
     */
    protected $description = 'Force reply with reply markup';

    /**
     * @var string
     */
    protected $usage = '/forcereply';

    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Command execute method
     *
     * @return \PhpTelegramBot\Core\Entities\ServerResponse
     * @throws \PhpTelegramBot\Core\Exception\TelegramException
     */
    public function execute()
    {
        $chat_id = $this->getMessage()->getChat()->getId();

        $data = [
            'chat_id'      => $chat_id,
            'text'         => 'Write something:',
            'reply_markup' => Keyboard::forceReply(),
        ];

        return Request::sendMessage($data);
    }
}
