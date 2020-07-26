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
 * User "/keyboard" command
 *
 * Display a keyboard with a few buttons.
 */
class KeyboardCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'keyboard';

    /**
     * @var string
     */
    protected $description = 'Show a custom keyboard with reply markup';

    /**
     * @var string
     */
    protected $usage = '/keyboard';

    /**
     * @var string
     */
    protected $version = '0.2.0';

    /**
     * Command execute method
     *
     * @return \PhpTelegramBot\Core\Entities\ServerResponse
     * @throws \PhpTelegramBot\Core\Exception\TelegramException
     */
    public function execute()
    {
        //Keyboard examples
        /** @var Keyboard[] $keyboards */
        $keyboards = [];

        //Example 0
        $keyboards[] = new Keyboard(
            ['7', '8', '9'],
            ['4', '5', '6'],
            ['1', '2', '3'],
            [' ', '0', ' ']
        );

        //Example 1
        $keyboards[] = new Keyboard(
            ['7', '8', '9', '+'],
            ['4', '5', '6', '-'],
            ['1', '2', '3', '*'],
            [' ', '0', ' ', '/']
        );

        //Example 2
        $keyboards[] = new Keyboard('A', 'B', 'C');

        //Example 3
        $keyboards[] = new Keyboard(
            ['text' => 'A'],
            'B',
            ['C', 'D']
        );

        //Example 4 (bots version 2.0)
        $keyboards[] = new Keyboard([
            ['text' => 'Send my contact', 'request_contact' => true],
            ['text' => 'Send my location', 'request_location' => true],
        ]);

        //Return a random keyboard.
        $keyboard = $keyboards[mt_rand(0, count($keyboards) - 1)]
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);

        $chat_id = $this->getMessage()->getChat()->getId();
        $data    = [
            'chat_id'      => $chat_id,
            'text'         => 'Press a Button:',
            'reply_markup' => $keyboard,
        ];

        return Request::sendMessage($data);
    }
}
