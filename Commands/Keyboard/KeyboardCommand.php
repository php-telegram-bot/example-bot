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

namespace Longman\TelegramBot\Commands\UserCommands;

/**
 * User "/keyboard" command
 *
 * Display a keyboard with a few buttons.
 */

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

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
    protected $version = '0.3.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        /** @var Keyboard[] $keyboards */
        $keyboards = [];

        // Simple digits
        $keyboards[] = new Keyboard(
            ['7', '8', '9'],
            ['4', '5', '6'],
            ['1', '2', '3'],
            [' ', '0', ' ']
        );

        // Digits with operations
        $keyboards[] = new Keyboard(
            ['7', '8', '9', '+'],
            ['4', '5', '6', '-'],
            ['1', '2', '3', '*'],
            [' ', '0', ' ', '/']
        );

        // Short version with 1 button per row
        $keyboards[] = new Keyboard('A', 'B', 'C');

        // Some different ways of creating rows and buttons
        $keyboards[] = new Keyboard(
            ['text' => 'A'],
            'B',
            ['C', 'D']
        );

        // Buttons to perform Contact or Location sharing
        $keyboards[] = new Keyboard([
            ['text' => 'Send my contact', 'request_contact' => true],
            ['text' => 'Send my location', 'request_location' => true],
        ]);

        // Shuffle our example keyboards and return a random one
        shuffle($keyboards);
        $keyboard = end($keyboards)
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);

        return $this->replyToChat('Press a Button!', [
            'reply_markup' => $keyboard,
        ]);
    }
}
