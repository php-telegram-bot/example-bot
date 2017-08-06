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
use Longman\TelegramBot\Request;

/**
 * User "/image" command
 *
 * Fetch any uploaded image from the Uploads path.
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
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        // Use any extra parameters as the caption text.
        $caption = trim($message->getText(true));

        // Get a random picture from the telegram->getUploadPath() directory.
        $random_image = $this->GetRandomImagePath($this->telegram->getUploadPath());

        $data = [
            'chat_id' => $message->getChat()->getId(),
        ];

        if (!$random_image) {
            $data['text'] = 'No image found!';
            return Request::sendMessage($data);
        }

        // If no caption is set, use the filename.
        if ($caption === '') {
            $caption = basename($random_image);
        }

        $data['caption'] = $caption;
        $data['photo']   = Request::encodeFile($random_image);

        return Request::sendPhoto($data);
    }

    /**
     * Return the path to a random image in the passed directory.
     *
     * @param string $dir
     *
     * @return string
     */
    private function GetRandomImagePath($dir)
    {
        // Slice off the . and .. "directories"
        if ($image_list = array_slice(scandir($dir, SCANDIR_SORT_NONE), 2)) {
            shuffle($image_list);
            return $dir . '/' . $image_list[0];
        }

        return '';
    }
}
