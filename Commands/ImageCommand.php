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
use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;
use Longman\TelegramBot\Request;

/**
 * User "/image" command
 */
class ImageCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'image';

    /**
     * @var string
     */
    protected $description = 'Send Image';

    /**
     * @var string
     */
    protected $usage = '/image';

    /**
     * @var string
     */
    protected $version = '1.0.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text    = $message->getText(true);

        $data = [
            'chat_id' => $chat_id,
            'caption' => $text,
        ];

        //Return a random picture from the telegram->getUploadPath().
        return Request::sendPhoto($data, $this->ShowRandomImage($this->telegram->getUploadPath()));
    }

    /**
     * Return the path to a random image in the passed directory.
     *
     * @param string $dir
     *
     * @return string
     */
    private function ShowRandomImage($dir)
    {
        $image_list = scandir($dir);

        return $dir . '/' . $image_list[mt_rand(2, count($image_list) - 1)];
    }
}
