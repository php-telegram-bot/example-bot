<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;

/**
 * User "/hidekeyboard" command
 *
 * Command to hide the keyboard.
 */
class HidekeyboardCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'hidekeyboard';

    /**
     * @var string
     */
    protected $description = 'Hide the custom keyboard';

    /**
     * @var string
     */
    protected $usage = '/hidekeyboard';

    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $chat_id = $this->getMessage()->getChat()->getId();

        $data = [
            'chat_id'      => $chat_id,
            'text'         => 'Keyboard Hidden',
            'reply_markup' => Keyboard::remove(),
        ];

        return Request::sendMessage($data);
    }
}
