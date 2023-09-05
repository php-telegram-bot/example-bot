<?php
function getTextsArray()
{
    return [
        'exit_command_qustion' => 'Вы точно хотите отменить заполнение анкеты?',
        'exit_command_yes' => 'Да',
        'exit_command_no' => 'Нет',
        'exit_command_approve' => 'Вы всегда можете заполнить анкету заного, для этого отправьте команду /start',
        'exit_command_cancle' => '✅',

        'command_start_des' => 'Начать заполнение анкеты',
        'command_exit_des' => 'Отменить заполнение анкеты',

        'state_0' => 'Что бы отправить заявку на вступление в команду ответьте на несколько вопросов.' . PHP_EOL .
            'Выбирете одну или несколько позиций:',
        'state_0_selected' => 'Готово',

        'state_1' => 'Как Вас зовут(ФИО):',

        'state_2' => 'Ваш возраст:',
        'state_2_help' => 'Введите число',

        'state_3' => 'Ваш рост:',
        'state_3_help' => 'Введите число',

        'state_4' => 'В каком городе вы живете:',

        'state_5' => 'Расскажите о вашем предыдущем опыте работы:',

        'state_6' => 'Расскажите о себе:',

        'state_7' => 'Занятость на данный момент:',

        'state_8' => 'Ваше фото и/или видео:',
        'state_8_skip' => 'Пропустить',
        'state_8_complete' => 'Готово',
        'state_8_help' => 'Напишите "Готово" или загрузите ещё медиа',

        'state_9' => 'Ваш номер телефона:',
        'state_9_keyboard' => 'Отправить номер телефона',

        'state_10' => 'Рассматривает он эту работу как подработку или основную?',

        'state_11' => 'ссылка на ВКонтакте(vk.com):',

        'output' => 'Входящая заявка:' . PHP_EOL .
            PHP_EOL . '<b>Пользователь:</b> @user_name' . PHP_EOL .
            '<b>ID Пользователя:</b> <code>user_id</code>' . PHP_EOL .

            PHP_EOL . '<b>Должность:</b> position' .
            PHP_EOL . '<b>ФИО:</b> surname' .
            PHP_EOL . '<b>Возраст:</b> age' .
            PHP_EOL . '<b>Рост:</b> height' .
            PHP_EOL . '<b>Город:</b> location' .
            PHP_EOL . '<b>Контакты:</b> phone_number' .
            PHP_EOL . '<b>О себе:</b> about' .
            PHP_EOL . '<b>Опыт работы:</b> experience' .
            PHP_EOL . '<b>Занятость:</b> employment' .
            PHP_EOL . '<b>Подработку:</b> sidejob' .
            PHP_EOL . '<b>VK:</b> vk',

        'output_user' => 'Ваша анкета отправленаю.' . PHP_EOL .

            PHP_EOL . '<b>Должность:</b> position' .
            PHP_EOL . '<b>ФИО:</b> surname' .
            PHP_EOL . '<b>Возраст:</b> age' .
            PHP_EOL . '<b>Рост:</b> height' .
            PHP_EOL . '<b>Город:</b> location' .
            PHP_EOL . '<b>Контакты:</b> phone_number' .
            PHP_EOL . '<b>О себе:</b> about' .
            PHP_EOL . '<b>Опыт работы:</b> experience' .
            PHP_EOL . '<b>Занятость:</b> employment' .
            PHP_EOL . '<b>Подработку:</b> sidejob' .
            PHP_EOL . '<b>VK:</b> vk' .
            // PHP_EOL . '' .
            PHP_EOL . '',


        // 'command_exit_des' => '',
    ];
}

function getTextValue($key, $content = [])
{
    $texts = getTextsArray();
    $str = $texts[$key] ?? 'no value for key: ' . $key;
    $keys = array_keys($content);
    $values = array_values($content);
    return str_replace($keys, $values, $str);
}
