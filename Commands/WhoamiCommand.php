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

/**
 * User "/whoami" command
 *
 * Simple command that returns info about the current user.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class WhoamiCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'whoami';

    /**
     * @var string
     */
    protected $description = 'Show your id, name and username';

    /**
     * @var string
     */
    protected $usage = '/whoami';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        $from       = $message->getFrom();
        $user_id    = $from->getId();
        $chat_id    = $message->getChat()->getId();
        $message_id = $message->getMessageId();

        $data = [
            'chat_id'             => $chat_id,
            'reply_to_message_id' => $message_id,
        ];

        // Send chat action "typing..."
        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action'  => ChatAction::TYPING,
        ]);

        $caption = sprintf(
            'Your Id: %d' . PHP_EOL .
            'Name: %s %s' . PHP_EOL .
            'Username: %s',
            $user_id,
            $from->getFirstName(),
            $from->getLastName(),
            $from->getUsername()
        );

        // Fetch user profile photo
        $limit  = 10;
        $offset = null;

        $user_profile_photos_response = Request::getUserProfilePhotos([
            'user_id' => $user_id,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);

        if ($user_profile_photos_response->isOk()) {
            /** @var UserProfilePhotos $user_profile_photos */
            $user_profile_photos = $user_profile_photos_response->getResult();

            if ($user_profile_photos->getTotalCount() > 0) {
                $photos  = $user_profile_photos->getPhotos();
                // $photo   = $photos[0][2];
                // Get the best quality of the first profile photo found
                $photo   = end($photos[0]);
                $file_id = $photo->getFileId();

                $data['photo']   = $file_id;
                $data['caption'] = $caption;

                $result = Request::sendPhoto($data);

                // Download the photo after sending the message
                $photo_file_response = Request::getFile(['file_id' => $file_id]);
                if ($photo_file_response->isOk()) {
                    /** @var File $photo_file */
                    $photo_file = $photo_file_response->getResult();
                    Request::downloadFile($photo_file);
                }

                return $result;
            }
        }

        // No Photo just send text
        $data['text'] = $caption;

        return Request::sendMessage($data);
    }
}
