<?php
/**
 * README
 * This configuration file is intended to be used as the main script for the PHP Telegram Bot Manager.
 * Uncommented parameters must be filled
 *
 * For the full list of options, go to:
 * https://github.com/php-telegram-bot/telegram-bot-manager#set-extra-bot-parameters
 */

// Load composer
require_once __DIR__ . '/vendor/autoload.php';

// Add you bot's username (also to be used for log file names)
$bot_username = 'username_bot'; // Without "@"

try {
    $bot = new TelegramBot\TelegramBotManager\BotManager([
        // Add you bot's API key and name
        'api_key'      => 'your:bot_api_key',
        'bot_username' => $bot_username,

        // Secret key required to access the webhook
        'secret'       => 'super_secret',

        //'webhook'      => [
        //    // When using webhook, this needs to be uncommented and defined
        //    'url' => 'https://your-domain/path/to/manager.php',
        //    // Use self-signed certificate
        //    'certificate' => __DIR__ . '/server.crt',
        //    // Limit maximum number of connections
        //    'max_connections' => 5,
        //],

        //'commands' => [
        //    // Define all paths for your custom commands
        //    'paths'   => [
        //        __DIR__ . '/Commands',
        //    ],
        //    // Here you can set some command specific parameters
        //    'configs' => [
        //        // e.g. Google geocode/timezone api key for /date command
        //        'date' => ['google_api_key' => 'your_google_api_key_here'],
        //    ],
        //],

        // Define all IDs of admin users
        //'admins'       => [
        //    123,
        //],

        // Enter your MySQL database credentials
        //'mysql'        => [
        //    'host'     => 'localhost',
        //    'user'     => 'dbuser',
        //    'password' => 'dbpass',
        //    'database' => 'dbname',
        //],

        // Logging (Error, Debug and Raw Updates)
        //'logging'  => [
        //    'debug'  => __DIR__ . "/{$bot_username}_debug.log",
        //    'error'  => __DIR__ . "/{$bot_username}_error.log",
        //    'update' => __DIR__ . "/{$bot_username}_update.log",
        //],

        // Set custom Upload and Download paths
        //'paths'    => [
        //    'download' => __DIR__ . '/Download',
        //    'upload'   => __DIR__ . '/Upload',
        //],

        // Botan.io integration
        //'botan' => [
        //    'token' => 'your_botan_token',
        //],

        // Requests Limiter (tries to prevent reaching Telegram API limits)
        'limiter'      => ['enabled' => true],
    ]);

    // Run the bot!
    $bot->run();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    //echo $e;
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initialisation errors
    //echo $e;
}
