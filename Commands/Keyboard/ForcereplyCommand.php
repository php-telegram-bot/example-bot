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

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

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
    protected $version = '0.2.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        // Force a reply to the sent message
        return $this->replyToChat('Write something in reply:', [
            'reply_markup' => Keyboard::forceReply(),
        ]);
    }
}
