<?php


namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Request;

/**
 * User "/survey" command
 *
 * Command that demonstrated the Conversation funtionality in form of a simple survey.
 */
class SurveyCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'survey';

    /**
     * @var string
     */
    protected $description = 'Survery for bot users';

    /**
     * @var string
     */
    protected $usage = '/survey';

    /**
     * @var string
     */
    protected $version = '0.3.0';

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
     * @var \Longman\TelegramBot\Conversation
     */
    protected $conversation;

    protected $notes, $chat, $user, $message, $data, $text;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $this->message = $this->getMessage();
        $this->chat    = $this->message->getChat();
        $this->user    = $this->message->getFrom();
        $this->text    = trim($this->message->getText(true));

        //Preparing Response
        $this->data = [
            'chat_id' => $this->chat->getId(),
        ];

        if ($this->chat->isGroupChat() || $this->chat->isSuperGroup()) {
            //reply to message id is applied by default
            //Force reply is applied by default so it can work with privacy on
            $this->data['reply_markup'] = Keyboard::forceReply(['selective' => true]);
        }

        return $this->run();
    }


    public function run()
    {

        //Conversation start
        $this->conversation = new Conversation($this->user->getId(), $this->chat->getId(), $this->getName());

        $this->notes = &$this->conversation->notes;
        !is_array($this->notes) && $this->notes = [];

        //cache data from the tracking session if any
        $state = 'askName';
        if (isset($this->notes['state'])) {
            $state = $this->notes['state'];
        }

        $this->{$state}();
    }


    protected function askName()
    {
        if ($this->text === '') {
            $this->notes['state'] = __FUNCTION__;
            $this->conversation->update();

            $this->data['text']         = 'Type your name:';
            $this->data['reply_markup'] = Keyboard::remove(['selective' => true]);

            return Request::sendMessage($this->data);
        }

        $this->notes['name'] = $this->text;
        $this->text          = '';
        $this->askSurname();
    }

    protected function askSurname()
    {
        if ($this->text === '') {
            $this->notes['state'] = __FUNCTION__;
            $this->conversation->update();

            $this->data['text'] = 'Type your surname:';

            return Request::sendMessage($this->data);
        }

        $this->notes['surname'] = $this->text;
        $this->text             = '';
        $this->askAge();
    }

    protected function askAge()
    {
        if ($this->text === '' || !is_numeric($this->text)) {
            $this->notes['state'] = __FUNCTION__;
            $this->conversation->update();

            $this->data['text'] = 'Type your age:';
            if ($this->text !== '') {
                $this->data['text'] = 'Type your age, must be a number:';
            }

            return Request::sendMessage($this->data);
        }

        $this->notes['age'] = $this->text;
        $this->text         = '';
        $this->askGender();
    }

    protected function askGender()
    {
        if ($this->text === '' || !in_array($this->text, ['M', 'F'], true)) {

            $this->notes['state'] = __FUNCTION__;
            $this->conversation->update();

            $this->data['reply_markup'] = (new Keyboard(['M', 'F']))->setResizeKeyboard(true)
                                                                    ->setOneTimeKeyboard(true)
                                                                    ->setSelective(true);

            $this->data['text'] = 'Select your gender:';

            if ($this->text !== '') {
                $this->data['text'] = 'Select your gender, choose a keyboard option:';
            }

            return Request::sendMessage($this->data);
        }

        $this->notes['gender'] = $this->text;
        $this->askLocation();
    }

    protected function askLocation()
    {
        if ($this->message->getLocation() === null) {
            $this->notes['state'] = __FUNCTION__;
            $this->conversation->update();

            $this->data['reply_markup'] = (new Keyboard((new KeyboardButton('Share Location'))->setRequestLocation(true)))->setOneTimeKeyboard(true)
                                                                                                                          ->setResizeKeyboard(true)
                                                                                                                          ->setSelective(true);

            $this->data['text'] = 'Share your location:';

            return Request::sendMessage($this->data);
        }

        $this->notes['longitude'] = $this->message->getLocation()->getLongitude();
        $this->notes['latitude']  = $this->message->getLocation()->getLatitude();
        $this->askPhoto();
    }

    protected function askPhoto()
    {
        if ($this->message->getPhoto() === null) {
            $this->notes['state'] = __FUNCTION__;
            $this->conversation->update();
            $this->data['text'] = 'Insert your picture:';

            return Request::sendMessage($this->data);
        }

        $photo                   = $this->message->getPhoto()[0];
        $this->notes['photo_id'] = $photo->getFileId();
        $this->askContact();
    }

    protected function askContact()
    {
        if ($this->message->getContact() === null) {
            $this->notes['state'] = __FUNCTION__;
            $this->conversation->update();

            $this->data['reply_markup'] = (new Keyboard((new KeyboardButton('Share Contact'))->setRequestContact(true)))->setOneTimeKeyboard(true)
                                                                                                                        ->setResizeKeyboard(true)
                                                                                                                        ->setSelective(true);

            $this->data['text'] = 'Share your contact information:';

            return Request::sendMessage($this->data);
        }

        $this->notes['phone_number'] = $this->message->getContact()->getPhoneNumber();
        $this->finish();
    }

    protected function finish()
    {
        $this->conversation->update();
        $out_text = '/Survey result:'.PHP_EOL;

        unset($this->notes['state']);

        foreach ($this->notes as $k => $v) {
            $out_text .= PHP_EOL.ucfirst($k).': '.$v;
        }

        $this->data['photo']        = $this->notes['photo_id'];
        $this->data['reply_markup'] = Keyboard::remove(['selective' => true]);
        $this->data['caption']      = $out_text;
        $this->conversation->stop();

        return Request::sendPhoto($this->data);
    }

}
