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
 * User "/survey" command
 *
 * Example of the Conversation functionality in form of a simple survey.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

require_once $path . '/positions.php';
require_once $path . '/texts.php';

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\InlineKeyboard;
// use Longman\TelegramBot\;


class StartCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Survey for bot users';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '0.4.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Conversation Object
     *
     * @var Conversation
     */
    protected $conversation;

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
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        $command = $message->getCommand();


        // Preparing response
        $data = [
            'chat_id'      => $chat_id,
            // Remove any keyboard by default
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        Request::setMyCommands(['commands' => [
            ['command' => 'exit', 'description' => getTextValue('command_exit_des')],
            ['command' => 'start', 'description' => getTextValue('command_start_des')]
        ]]);
        Request::setChatMenuButton([
            'chat_id' => $chat_id,
            'menu_button' => [
                'type' => 'commands'
            ]
        ]);

        // Conversation start
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        // Load any existing notes from this conversation
        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];

        // Load the current state of the conversation
        $state = $notes['state'] ?? 0;

        $result = Request::emptyResponse();

        // State machine
        // Every time a step is achieved the state is updated
        switch ($state) {
            case 0:
                if ($text === '') {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_0', ['user_name' => $user->getUsername()]);
                    $data['reply_markup'] = new InlineKeyboard(...getPositionsArray());

                    $result = Request::sendMessage($data);
                    break;
                }
                $text          = '';
                break;
                // No break!
            case 1:
                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_1');

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['surname'] = $text;
                $text             = '';
                // No break!
            case 2:
                if ($text === '' || !is_numeric($text)) {
                    $notes['state'] = 2;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_2');
                    if ($text !== '') {
                        $data['text'] = getTextValue('state_2_help');
                    }

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['age'] = $text;
                $text         = '';

                // No break!
            case 3:
                //height
                if (fieldIsSpecial($notes['position'], 'height')) {
                    if ($text === '' || !is_numeric($text)) {
                        $notes['state'] = 3;
                        $this->conversation->update();

                        $data['text'] = getTextValue('state_3');
                        if ($text !== '') {
                            $data['text'] = getTextValue('state_3_help');
                        }

                        $result = Request::sendMessage($data);
                        break;
                    }

                    $notes['height'] = $text;
                    $text         = '';
                }

                // No break!
            case 4:
                if ($text === '') {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_4');

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['location'] = $text;
                $text             = '';

                // No break!
            case 5:
                if ($text === '') {
                    $notes['state'] = 5;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_5');

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['experience'] = $text;
                $text             = '';

                // No break!
            case 6:
                if ($text === '') {
                    $notes['state'] = 6;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_6');

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['about'] = $text;
                $text             = '';

                // No break!
            case 7:
                // employment
                if ($text === '') {
                    $notes['state'] = 7;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_7');

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['employment'] = $text;
                $text             = '';
            case 8:
                $photoIsRequired = fieldIsRequired($notes['position'], 'photo');
                $mediaGroupId = $message->getMediaGroupId();
                if ($message->getPhoto() === null && $message->getVideo() === null && $text === '') {
                    $notes['state'] = 8;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_8');
                    if ($photoIsRequired === false) {
                        $data['reply_markup'] = (new Keyboard(
                            ['text' => getTextValue('state_8_skip')],
                        ))->setOneTimeKeyboard(true)
                            ->setResizeKeyboard(true)
                            ->setSelective(true);
                    }

                    $result = Request::sendMessage($data);
                    break;
                } else if ($photoIsRequired === false && $text === getTextValue('state_8_skip')) {
                    $notes['photo_id'] = 'no_photo';
                    $text             = '';
                } else if ($text === getTextValue('state_8_complete') && (count($notes['photo_id'] ?? []) + count($notes['video_id'] ?? [])) > 0) {
                    $text             = '';
                } else if ($message->getPhoto() !== null) {
                    $photo             = $message->getPhoto()[0];
                    $notes['photo_id'] = [...($notes['photo_id'] ?? []), $photo->getFileId()];

                    $data['text'] = getTextValue('state_8_help');
                    $data['reply_markup'] = (new Keyboard(
                        ['text' => getTextValue('state_8_complete')],
                    ))->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);
                    if ($mediaGroupId !== $notes['media_group_id']) {
                        $result = Request::sendMessage($data);
                    } else if ($mediaGroupId === null) {
                        $result = Request::sendMessage($data);
                    }
                    $notes['media_group_id'] = $mediaGroupId;
                    $this->conversation->update();
                    break;
                } else if ($message->getVideo() !== null) {
                    $video             = $message->getVideo();
                    $notes['video_id'] = [...($notes['video_id'] ?? []), $video->getFileId()];

                    $data['text'] = getTextValue('state_8_help');
                    $data['reply_markup'] = (new Keyboard(
                        ['text' => getTextValue('state_8_complete')],
                    ))->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);
                    if ($mediaGroupId !== $notes['media_group_id']) {
                        $result = Request::sendMessage($data);
                    } else if ($mediaGroupId === null) {
                        $result = Request::sendMessage($data);
                    }
                    $notes['media_group_id'] = $mediaGroupId;
                    $this->conversation->update();
                    break;
                } else if ($message->getPhoto() === null && $text !== '') {
                    $data['text'] = getTextValue('state_8');
                    if ($photoIsRequired === false) {
                        $data['reply_markup'] = (new Keyboard(
                            ['text' => getTextValue('state_8_skip')],
                        ))->setOneTimeKeyboard(true)
                            ->setResizeKeyboard(true)
                            ->setSelective(true);
                    }
                    $result = Request::sendMessage($data);
                    break;
                }

                // No break!
            case 9:
                if ($text === '' && $message->getContact() === null) {
                    $notes['state'] = 9;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(
                        (new KeyboardButton(getTextValue('state_9_keyboard')))->setRequestContact(true)
                    ))
                        ->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = getTextValue('state_9');

                    $result = Request::sendMessage($data);
                    break;
                } else if ($text !== '' && $message->getContact() === null) {
                    $notes['phone_number'] = $text;
                    $text             = '';
                } else if ($message->getContact() !== null) {
                    $notes['phone_number'] = $message->getContact()->getPhoneNumber();
                }

                // No break!

            case 10:
                // employment
                if ($text === '') {
                    $notes['state'] = 10;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_10');

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['sidejob'] = $text;
                $text             = '';
            case 11:
                // employment
                if ($text === '') {
                    $notes['state'] = 11;
                    $this->conversation->update();

                    $data['text'] = getTextValue('state_11');

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['vk'] = $text;
                $text             = '';
            case 12:
                $this->conversation->update();



                unset($notes['state']);

                $resumeData = $notes;
                $resumeData['user_name'] = $user->getUsername();
                $resumeData['user_id'] = $user_id;
                $postitions_texts = array_map(function ($value) {
                    return getTextByData($value);
                }, $notes['position']);
                $resumeData['position'] = implode(', ', $postitions_texts);

                $out_text = getTextValue('output', $resumeData);
                $out_text_user = getTextValue('output_user', $resumeData);

                $noPhoto = $notes['photo_id'] === 'no_photo';
                if (!$noPhoto) {
                    $noPhoto = count($notes['photo_id'] ?? []) === 0 ? $noPhoto : false;
                    $noPhoto = count($notes['video_id'] ?? []) === 0 ? $noPhoto : false;
                }
                $photos = $notes['photo_id'] ?? [];

                $videos = $notes['video_id'];

                $data['parse_mode'] = 'html';

                // $out_text_user .= PHP_EOL . 'Анкета готова!';
                // $data['reply_markup'] = new InlineKeyboard([
                //     ['text' => getTextValue('complete_command_send'), 'callback_data' => 'complete_command_send'],
                //     ['text' => getTextValue('complete_command_edit'), 'callback_data' => 'complete_command_edit'],
                //     ['text' => getTextValue('complete_command_cancle'), 'callback_data' => 'complete_command_cancle']
                // ]);

                $toAdmin = $data;
                $toGroup = $data;

                if ($noPhoto) {
                    $data['text'] = $out_text_user;
                    $toGroup['text'] = $out_text;
                    $toAdmin['text'] = $out_text;
                } else {
                    $data['caption'] = $out_text_user;
                    $toGroup['caption'] = $out_text;
                    $toAdmin['caption'] = $out_text;
                }

                $this->conversation->stop();

                $toAdmin['chat_id'] = 5458847537; // id админа которому будет отправленно
                $toGroup['chat_id'] = -945423465; // id группы в которую будет отправленно
                // $result = Request::emptyResponse();
                if ($noPhoto) {
                    Request::sendMessage($toAdmin);
                    // Request::sendMessage($toGroup);
                    $result = Request::sendMessage($data);
                } else {
                    // $result = Request::sendPhoto($data);

                    $v = array_map(function ($id) {
                        return ['type' => 'video', 'media' => $id];
                    }, $videos ?? []);
                    $p = array_map(function ($id) {
                        return ['type' => 'photo', 'media' => $id];
                    }, $photos ?? []);
                    $data['media'] = [...$p, ...$v];
                    $data['media'][0]['caption'] = $out_text_user;
                    $data['media'][0]['parse_mode'] = 'html';

                    $toAdmin['media'] = $toGroup['media'] = $data['media'];

                    $toGroup['media'][0]['caption'] = $out_text;
                    $toAdmin['media'][0]['caption'] = $out_text;


                    Request::sendMediaGroup($toAdmin);
                    // Request::sendMediaGroup($toGroup);


                    $result = Request::sendMediaGroup($data);
                }
                break;
        }

        return $result;
    }
}
