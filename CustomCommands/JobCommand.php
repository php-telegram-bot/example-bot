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

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\InlineKeyboard;


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


        // Preparing response
        $data = [
            'chat_id'      => $chat_id,
            // Remove any keyboard by default
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

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

                    $data['text'] = 'Что бы отправить заявку на вступление в команду ответьте на несколько вопросов.' . PHP_EOL;
                    $data['text'] .= 'Выбирете позицию:';
                    $data['reply_markup'] = new InlineKeyboard(...getPositionsArray());

                    $result = Request::sendMessage($data);
                    break;
                }
                $text          = '';

                // No break!
            case 1:
                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = 'Как Вас зовут(ФИО):';

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

                    $data['text'] = 'Ваш возраст:';
                    if ($text !== '') {
                        $data['text'] = 'Введите число';
                    }

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['age'] = $text;
                $text         = '';

                // No break!
            case 3:
                if ($text === '') {
                    $notes['state'] = 3;
                    $this->conversation->update();

                    $data['text'] = 'В каком городе вы живете:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['location'] = $text;
                $text             = '';

                // No break!
            case 4:
                if ($text === '') {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = 'Расскажите о вашем предыдущем опыте работы:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['experience'] = $text;
                $text             = '';

                // No break!
            case 5:
                if ($text === '') {
                    $notes['state'] = 5;
                    $this->conversation->update();

                    $data['text'] = 'Расскажите о себе:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['about'] = $text;
                $text             = '';

                // No break!
            case 6:
                $photoIsRequired = fieldIsRequired($notes['position'], 'photo');
                $mediaGroupId = $message->getMediaGroupId();
                if ($message->getPhoto() === null && $text === '') {
                    $notes['state'] = 6;
                    $this->conversation->update();

                    $data['text'] = 'Ваше фото:';
                    if ($photoIsRequired === false) {
                        $data['reply_markup'] = (new Keyboard(
                            ['text' => 'Пропустить'],
                        ))->setOneTimeKeyboard(true)
                            ->setResizeKeyboard(true)
                            ->setSelective(true);
                    }

                    $result = Request::sendMessage($data);
                    break;
                } else if ($photoIsRequired === false && $text === 'Пропустить') {
                    $notes['photo_id'] = 'no_photo';
                    $text             = '';
                } else if ($text === 'Готово' && count($notes['photo_id'] ?? []) > 0) {
                    $text             = '';
                } else if ($message->getPhoto() !== null) {
                    $photo             = $message->getPhoto()[0];
                    $notes['photo_id'] = [...($notes['photo_id'] ?? []), $photo->getFileId()];

                    $data['text'] = 'Напишите "Готово" или загрузите ещё фото';
                    $data['reply_markup'] = (new Keyboard(
                        ['text' => 'Готово'],
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
                    $data['text'] = 'Ваше фото:';
                    if ($photoIsRequired === false) {
                        $data['reply_markup'] = (new Keyboard(
                            ['text' => 'Пропустить'],
                        ))->setOneTimeKeyboard(true)
                            ->setResizeKeyboard(true)
                            ->setSelective(true);
                    }
                    $result = Request::sendMessage($data);
                    break;
                }

                // No break!
            case 7:
                if ($text === '' && $message->getContact() === null) {
                    $notes['state'] = 7;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(
                        (new KeyboardButton('Отправить номер телефона'))->setRequestContact(true)
                    ))
                        ->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = 'Ваш номер телефона:';

                    $result = Request::sendMessage($data);
                    break;
                } else if ($text !== '' && $message->getContact() === null) {
                    $notes['phone_number'] = $text;
                } else if ($message->getContact() !== null) {
                    $notes['phone_number'] = $message->getContact()->getPhoneNumber();
                }

                // No break!
            case 8:
                $this->conversation->update();
                $out_text = 'Входящая заявка:' . PHP_EOL;
                $out_text .= PHP_EOL . 'Пользователь: @' . $user->getUsername() . PHP_EOL;
                $out_text .= 'ID Пользователя: ' . $user_id . PHP_EOL;
                unset($notes['state']);

                $out_text .= PHP_EOL . 'Должность' . ': ' . $notes['position'];
                $out_text .= PHP_EOL . 'ФИО' . ': ' . $notes['surname'];
                $out_text .= PHP_EOL . 'Возраст' . ': ' . $notes['age'];
                $out_text .= PHP_EOL . 'Город' . ': ' . $notes['location'];
                $out_text .= PHP_EOL . 'Контакты' . ': ' . $notes['phone_number'];
                $out_text .= PHP_EOL . 'О себе' . ': ' . $notes['about'];
                $out_text .= PHP_EOL . 'Опыт работы' . ': ' . $notes['experience'];

                $noPhoto = $notes['photo_id'] === 'no_photo';
                $photos = $notes['photo_id'];
                if ($noPhoto) {
                    $data['text'] = $out_text;
                } else {
                    // $data['photo']   = $notes['photo_id'];
                    $data['caption'] = $out_text;
                }

                $this->conversation->stop();
                $toAdmin = $data;
                $toGroup = $data;
                $toAdmin['chat_id'] = 5458847537; //-945423465;
                $toGroup['chat_id'] = -945423465;
                // $result = Request::emptyResponse();
                if ($noPhoto) {
                    Request::sendMessage($toAdmin);
                    Request::sendMessage($toGroup);
                    $result = Request::sendMessage($data);
                } else {
                    // $result = Request::sendPhoto($data);
                    $isFirst = true;
                    $data['media'] = array_map(function ($id) use (&$isFirst, $out_text) {
                        $r = [
                            'type' => 'photo',
                            'media' => $id
                        ];
                        if ($isFirst) {
                            $r['caption'] = $out_text;
                            $isFirst = false;
                        }
                        return $r;
                    }, $photos);
                    $toAdmin['media'] = $toGroup['media'] = $data['media'];
                    Request::sendMediaGroup($toAdmin);
                    Request::sendMediaGroup($toGroup);
                    $result = Request::sendMediaGroup($data);
                }
                break;
        }

        return $result;
    }
}
