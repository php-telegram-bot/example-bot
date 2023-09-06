# PHP Telegram Bot Manager

[![Join the bot support group on Telegram][support-group-badge]][support-group]
[![Donate][donate-badge]](#donate)

[![Scrutinizer Code Quality][code-quality-badge]][code-quality]
[![Codecov][code-coverage-badge]][code-coverage]
[![Tests Status][tests-status-badge]][tests-status]

[![Latest Stable Version][latest-version-badge]][github-tgbot-manager]
[![Dependencies][dependencies-badge]][Tidelift]
[![Total Downloads][total-downloads-badge]][packagist-tgbot-manager]
[![License][license-badge]][license]

This project builds on top of [PHP Telegram Bot][github-tgbot-core] and as such, depends on it!

The main purpose of this mini-library is to make the interaction between your webserver and Telegram easier.
I strongly suggest your read the PHP Telegram Bot [instructions][github-tgbot-core-instructions] first, to understand what this library does exactly.

Installation and usage is pretty straight forward:

### Require this package with [Composer]

```bash
composer require php-telegram-bot/telegram-bot-manager:^2.1
```

**NOTE:** This will automatically also install [PHP Telegram Bot][github-tgbot-core] into your project (if it isn't already).

**Advanced:** Due to the fact that the core library is not a stable version yet, this project is partly locked to the core version, to ensure reliable functioning.

It is possible however, to override the core version that this library requires:

```yaml
"require": {
    "php-telegram-bot/telegram-bot-manager": "^2.1",
    "longman/telegram-bot": "dev-master as 0.81"
}
```

This example will pull the master version of the core library, making it appear to be version 0.81, which then satisfies the requirement.

### Performing actions

What use would this library be if you couldn't perform any actions?!

There are a few parameters available to get things rolling:

| Parameter | Description |
| --------- | ----------- |
| s         | **s**ecret: This is a special secret value defined in the main `manager.php` file. |
|           | This parameter is required to call the script via browser! |
| a         | **a**ction: The actual action to perform. (handle (default), webhookinfo, cron, set, unset, reset) |
|           | **handle** executes the `getUpdates` method; **webhookinfo** to get result from `getWebhookInfo`, **cron** executes cron commands; **set** / **unset** / **reset** the webhook. |
| l         | **l**oop: Number of seconds to loop the script for (used for getUpdates method). |
|           | This would be used mainly via CLI, to continually get updates for a certain period. |
| i         | **i**nterval: Number of seconds to wait between getUpdates requests (used for getUpdates method, default is 2). |
|           | This would be used mainly via CLI, to continually get updates for a certain period, every **i** seconds. |
| g         | **g**roup: Commands group for cron (only used together with `cron` action, default group is `default`). |
|           | Define which group of commands to execute via cron. Can be a comma separated list of groups. |

#### via browser

Simply point your browser to the `manager.php` file with the necessary **GET** parameters:
- `http://example.com/manager.php?s=<secret>&a=<action>&l=<loop>&i=<interval>`

**Webhook**

Set, unset and reset the webhook:
- `http://example.com/manager.php?s=super_secret&a=set`
- `http://example.com/manager.php?s=super_secret&a=unset`
- `http://example.com/manager.php?s=super_secret&a=reset` (unset & set combined)

**getUpdates**

Handle updates once:
- `http://example.com/manager.php?s=super_secret&a=handle` or simply
- `http://example.com/manager.php?s=super_secret` (`handle` action is the default)

Handle updates for 30 seconds, fetching every 5 seconds:
- `http://example.com/manager.php?s=super_secret&l=30&i=5`

**cron**

Execute commands via cron:
- `http://example.com/manager.php?s=super_secret&a=cron&g=maintenance` or multiple groups
- `http://example.com/manager.php?s=super_secret&a=cron&g=maintenance,cleanup`

#### via CLI

When using CLI, the secret is not necessary (since it could just be read from the file itself).

Call the `manager.php` file directly using `php` and pass the parameters:
- `$ php manager.php a=<action> l=<loop> i=<interval>`

**Webhook**

Set, unset and reset the webhook:
- `$ php manager.php a=set`
- `$ php manager.php a=unset`
- `$ php manager.php a=reset` (unset & set combined)

**getUpdates**

Handle updates once:
- `$ php manager.php a=handle` or simply
- `$ php manager.php` (`handle` action is the default)

Handle updates for 30 seconds, fetching every 5 seconds:
- `$ php manager.php l=30 i=5`

**cron**

Execute commands via cron:
- `$ php manager.php a=cron g=maintenance` or multiple groups
- `$ php manager.php a=cron g=maintenance,cleanup`

### Create the manager PHP file

You can name this file whatever you like, it just has to be somewhere inside your PHP project (preferably in the root folder to make things easier).
(Let's assume our file is called `manager.php`)

Let's start off with a simple example that uses the webhook method:
```php
<?php

use TelegramBot\TelegramBotManager\BotManager;

// Load composer.
require_once __DIR__ . '/vendor/autoload.php';

try {
    $bot = new BotManager([
        // Vitals!
        'api_key'      => '12345:my_api_key',

        // Extras.
        'bot_username' => 'my_own_bot',
        'secret'       => 'super_secret',
        'webhook'      => [
            'url' => 'https://example.com/manager.php',
        ]
    ]);
    $bot->run();
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
```

### Set vital bot parameters

The only vital parameter is the API key:

```php
$bot = new BotManager([
    // (string) Bot API key provided by @BotFather.
    'api_key' => '12345:my_api_key',
    ...
]);
```

### Set extra bot parameters

Apart from the necessary API key, the bot can be easily configured using extra parameters.

Set the webhook? Enable admins? Add custom command paths?

**All no problem!**

The `secret` is a user-defined key that is required to execute any of the library's features via webhook.
Best make it long, random and very unique!

For 84 random characters:
- If you have `pwgen` installed, just execute `pwgen 84 1` and copy the output.
- If you have `openssl` installed, use `openssl rand -hex 84`.
- Or just go [here][random-characters] and put all the output onto a single line.

(You get 2 guesses why 84 is a good number :wink:)

Below is a complete list of all available extra parameters.

```php
$bot = new BotManager([
    ...
    // (string) Bot username that was defined when creating the bot.
    'bot_username'     => 'my_own_bot',

    // (string) A secret password required to authorise access to the webhook.
    'secret'           => 'super_secret',

    // (array) All options that have to do with the webhook.
    'webhook'          => [
        // (string) URL to the manager PHP file used for setting up the webhook.
        'url'             => 'https://example.com/manager.php',
        // (string) Path to a self-signed certificate (if necessary).
        'certificate'     => __DIR__ . '/server.crt',
        // (int) Maximum allowed simultaneous HTTPS connections to the webhook.
        'max_connections' => 20,
        // (array) List the types of updates you want your bot to receive.
        'allowed_updates' => ['message', 'edited_channel_post', 'callback_query'],
        // (string) Secret token to validate webhook requests.
        'secret_token'    => 'super_secret_token',
    ],

    // (bool) Only allow webhook access from valid Telegram API IPs.
    'validate_request' => true,
    // (array) When using `validate_request`, also allow these IPs.
    'valid_ips'        => [
        '1.2.3.4',         // single
        '192.168.1.0/24',  // CIDR
        '10/8',            // CIDR (short)
        '5.6.*',           // wildcard
        '1.1.1.1-2.2.2.2', // range
    ],

    // (array) All options that have to do with the limiter.
    'limiter'          => [
        // (bool) Enable or disable the limiter functionality.
        'enabled' => true,
        // (array) Any extra options to pass to the limiter.
        'options' => [
            // (float) Interval between request handles.
            'interval' => 0.5,
        ],
    ],

    // (array) An array of user ids that have admin access to your bot (must be integers).
    'admins'           => [12345],

    // (array) Mysql credentials to connect a database (necessary for [`getUpdates`](#using-getupdates-method) method!).
    'mysql'            => [
        'host'         => '127.0.0.1',
        'port'         => 3306,           // optional
        'user'         => 'root',
        'password'     => 'root',
        'database'     => 'telegram_bot',
        'table_prefix' => 'tbl_prfx_',    // optional
        'encoding'     => 'utf8mb4',      // optional
    ],

    // (array) List of configurable paths.
    'paths'            => [
        // (string) Custom download path.
        'download' => __DIR__ . '/Download',
        // (string) Custom upload path.
        'upload'   => __DIR__ . '/Upload',
    ],

    // (array) All options that have to do with commands.
    'commands'         => [
        // (array) A list of custom commands paths.
        'paths'   => [
            __DIR__ . '/CustomCommands',
        ],
        // (array) A list of all custom command configs.
        'configs' => [
            'sendtochannel' => ['your_channel' => '@my_channel'],
            'weather'       => ['owm_api_key' => 'owm_api_key_12345'],
        ],
    ],

    // (array) All options that have to do with cron.
    'cron'             => [
        // (array) List of groups that contain the commands to execute.
        'groups' => [
            // Each group has a name and array of commands.
            // When no group is defined, the default group gets executed.
            'default'     => [
                '/default_cron_command',
            ],
            'maintenance' => [
                '/db_cleanup',
                '/db_repair',
                '/message_admins Maintenance completed',
            ],
        ],
    ],

    // (string) Override the custom input of your bot (mostly for testing purposes!).
    'custom_input'     => '{"some":"raw", "json":"update"}',
]);
```

### Using getUpdates method

Using the `getUpdates` method must not have a `webhook` parameter set and requires a MySQL database connection:
```php
$bot = new BotManager([
    ...
    // Extras.
    'mysql' => [
        'host'         => '127.0.0.1',
        'port'         => 3306,           // optional
        'user'         => 'root',
        'password'     => 'root',
        'database'     => 'telegram_bot',
        'table_prefix' => 'tbl_prfx_',    // optional
        'encoding'     => 'utf8mb4',      // optional
    ],
]);
```

Now, the updates can be done either through the [browser](#via-browser) or [via CLI](#via-cli).

#### Custom getUpdates output

A callback can be defined, to override the default output when updates are handled via getUpdates.

Example of the default output:
```
...
2017-07-10 14:59:25 - Updates processed: 1
123456: <text>
2017-07-10 14:59:27 - Updates processed: 0
2017-07-10 14:59:30 - Updates processed: 0
2017-07-10 14:59:32 - Updates processed: 0
2017-07-10 14:59:34 - Updates processed: 1
123456: <photo>
2017-07-10 14:59:36 - Updates processed: 0
...
```

Using custom callback that must return a string:
```php
// In manager.php after $bot has been defined:
$bot->setCustomGetUpdatesCallback(function (ServerResponse $get_updates_response) {
    $results = array_filter((array) $get_updates_response->getResult());

    return sprintf('There are %d update(s)' . PHP_EOL, count($results));
});
```
output:
```
...
There are 0 update(s)
There are 0 update(s)
There are 2 update(s)
There are 1 update(s)
...
```

## Development

When running live bot tests on a fork, you must enter the following environment variables to your [repository settings][github-actions-encrypted-secrets]:
```
API_KEY="12345:your_api_key"
BOT_USERNAME="username_of_your_bot"
```
It probably makes sense for you to create a new dummy bot for this.

## Security

See [SECURITY](SECURITY.md) for more information.

## Donate

All work on this bot consists of many hours of coding during our free time, to provide you with a Telegram Bot library that is easy to use and extend.
If you enjoy using this library and would like to say thank you, donations are a great way to show your support.

Donations are invested back into the project :+1:

Thank you for keeping this project alive :pray:

- [![Patreon](https://user-images.githubusercontent.com/9423417/59235980-a5fa6b80-8be3-11e9-8ae7-020bc4ae9baa.png) Patreon.com/phptelegrambot][Patreon]
- [![OpenCollective](https://user-images.githubusercontent.com/9423417/59235978-a561d500-8be3-11e9-89be-82ec54be1546.png) OpenCollective.com/php-telegram-bot][OpenCollective]
- [![Ko-fi](https://user-images.githubusercontent.com/9423417/59235976-a561d500-8be3-11e9-911d-b1908c3e6a33.png) Ko-fi.com/phptelegrambot][Ko-fi]
- [![Tidelift](https://user-images.githubusercontent.com/9423417/59235982-a6930200-8be3-11e9-8ac2-bfb6991d80c5.png) Tidelift.com/longman/telegram-bot][Tidelift]
- [![Liberapay](https://user-images.githubusercontent.com/9423417/59235977-a561d500-8be3-11e9-9d16-bc3b13d3ceba.png) Liberapay.com/PHP-Telegram-Bot][Liberapay]
- [![PayPal](https://user-images.githubusercontent.com/9423417/59235981-a5fa6b80-8be3-11e9-9761-15eb7a524cb0.png) PayPal.me/noplanman][PayPal-noplanman] (account of @noplanman)

---

<div align="center">
    <b>
        <a href="https://tidelift.com/subscription/pkg/packagist-php-telegram-bot-telegram-bot-manager?utm_source=packagist-php-telegram-bot-telegram-bot-manager&utm_medium=referral&utm_campaign=readme-footer">Get professional support for this package with a Tidelift subscription</a>
    </b>
    <br>
    <sub>
        Tidelift helps make open source sustainable for maintainers while giving companies<br>assurances about security, maintenance, and licensing for their dependencies.
    </sub>
</div>

[github-tgbot-core]: https://github.com/php-telegram-bot/core "PHP Telegram Bot on GitHub"
[github-tgbot-core-instructions]: https://github.com/php-telegram-bot/core#instructions "PHP Telegram Bot instructions on GitHub"
[github-tgbot-manager]: https://github.com/php-telegram-bot/telegram-bot-manager "PHP Telegram Bot Manager on GitHub"
[packagist-tgbot-manager]: https://packagist.org/packages/php-telegram-bot/telegram-bot-manager "PHP Telegram Bot Manager on Packagist"
[license]: https://github.com/php-telegram-bot/telegram-bot-manager/blob/master/LICENSE "PHP Telegram Bot Manager license"

[support-group-badge]: https://img.shields.io/badge/telegram-@PHP__Telegram__Bot__Support-64659d.svg
[support-group]: https://telegram.me/PHP_Telegram_Bot_Support
[donate-badge]: https://img.shields.io/badge/%F0%9F%92%99-Donate%20%2F%20Support%20Us-blue.svg
[code-quality-badge]: https://img.shields.io/scrutinizer/g/php-telegram-bot/telegram-bot-manager.svg
[code-quality]: https://scrutinizer-ci.com/g/php-telegram-bot/telegram-bot-manager/?branch=master "Code quality on Scrutinizer"
[code-coverage-badge]: https://img.shields.io/codecov/c/github/php-telegram-bot/telegram-bot-manager.svg
[code-coverage]: https://codecov.io/gh/php-telegram-bot/telegram-bot-manager "Code coverage on Codecov"
[tests-status-badge]: https://github.com/php-telegram-bot/telegram-bot-manager/actions/workflows/tests.yml/badge.svg?branch=master
[tests-status]: https://github.com/php-telegram-bot/telegram-bot-manager/workflows/tests.yml "Tests status on GitHub Actions"

[latest-version-badge]: https://img.shields.io/packagist/v/php-telegram-bot/telegram-bot-manager.svg
[dependencies-badge]: https://tidelift.com/badges/github/php-telegram-bot/core?style=flat
[total-downloads-badge]: https://img.shields.io/packagist/dt/php-telegram-bot/telegram-bot-manager.svg
[license-badge]: https://img.shields.io/packagist/l/php-telegram-bot/telegram-bot-manager.svg

[random-characters]: https://www.random.org/strings/?num=7&len=12&digits=on&upperalpha=on&loweralpha=on&unique=on&format=plain&rnd=new "Generate random characters"
[github-actions-encrypted-secrets]: https://docs.github.com/en/actions/security-guides/encrypted-secrets "Encrypted Secrets for GitHub Actions"
[Composer]: https://getcomposer.org/ "Composer"

[Patreon]: https://www.patreon.com/phptelegrambot "Support us on Patreon"
[OpenCollective]: https://opencollective.com/php-telegram-bot "Support us on Open Collective"
[Ko-fi]: https://ko-fi.com/phptelegrambot "Support us on Ko-fi"
[Tidelift]: https://tidelift.com/subscription/pkg/packagist-php-telegram-bot-telegram-bot-manager?utm_source=packagist-php-telegram-bot-telegram-bot-manager&utm_medium=referral&utm_campaign=readme "Support us on Tidelift"
[Liberapay]: https://liberapay.com/PHP-Telegram-Bot "Donate with Liberapay"
[PayPal-noplanman]: https://paypal.me/noplanman "Donate with PayPal"
