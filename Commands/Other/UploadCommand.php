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
 * User "/upload" command
 *
 * A command that allows users to upload files to your bot, saving them to the bot's "Download" folder.
 *
 * IMPORTANT NOTICE
 * This is a "demo", do NOT use this as-is in your bot!
 * Know the security implications of allowing users to upload arbitrary files to your server!
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class UploadCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'upload';

    /**
     * @var string
     */
    protected $description = 'Upload and save files';

    /**
     * @var string
     */
    protected $usage = '/upload';

    /**
     * @var string
     */
    protected $version = '0.2.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();
        $user_id = $message->getFrom()->getId();

        // Make sure the Download path has been defined and exists
        $download_path = $this->telegram->getDownloadPath();
        if (!is_dir($download_path)) {
            return $this->replyToChat('Download path has not been defined or does not exist.');
        }

        // Initialise the data array for the response
        $data = ['chat_id' => $chat_id];

        if ($chat->isGroupChat() || $chat->isSuperGroup()) {
            // Reply to message id is applied by default
            $data['reply_to_message_id'] = $message->getMessageId();
            // Force reply is applied by default to work with privacy on
            $data['reply_markup'] = Keyboard::forceReply(['selective' => true]);
        }

        // Start conversation
        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $message_type = $message->getType();

        if (in_array($message_type, ['audio', 'document', 'photo', 'video', 'voice'], true)) {
            $doc = $message->{'get' . ucfirst($message_type)}();

            // For photos, get the best quality!
            ($message_type === 'photo') && $doc = end($doc);

            $file_id = $doc->getFileId();
            $file    = Request::getFile(['file_id' => $file_id]);
            if ($file->isOk() && Request::downloadFile($file->getResult())) {
                $data['text'] = $message_type . ' file is located at: ' . $download_path . '/' . $file->getResult()->getFilePath();
            } else {
                $data['text'] = 'Failed to download.';
            }

            $conversation->notes['file_id'] = $file_id;
            $conversation->update();
            $conversation->stop();
        } else {
            $data['text'] = 'Please upload the file now';
        }

        return Request::sendMessage($data);
    }
}
