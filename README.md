# PHP Telegram Bot Example
An A-Z example of Telegram bot using the [PHP Telegram Bot][1] library.

**Important!** Before getting started with this project, make sure you have read the official [readme][2] to understand how the PHP Telegram Bot library works and what is required to run a Telegram bot.

Let's get started then! :smiley:

This repository aims to demonstrate the usage of all the features offered by the PHP Telegram Bot library and as such contains all example commands.
Also, it gives an example setup for both the standard usage and using the [PHP Telegram Bot Manager][3] 

## 0. Cloning this repository

To start off, you can clone this repository using git:

```bash
$ git clone https://github.com/php-telegram-bot/example-bot.git
```

or better yet, download it as a zip file:

```bash
$ curl -o example-bot.zip https://github.com/php-telegram-bot/example-bot/archive/master.zip
```

Unzip the files to the root of your project folder.

## 1. Making it yours

Now you can choose what installation you would like, either the default one or using the [Bot Manager][3] project.
Depending on which one you choose, you can delete the files that are not required.

---

**Default**
Next, edit the following files, replacing all necessary values with those of your project.
Thanks to reading the main readme file, you should know what these do.

- `composer.json` (Describes your project and it's dependencies)
- `set.php` (Used to set the webhook)
- `unset.php` (Used to unset the webhook)
- `hook.php` (Used for the webhook method)
- `getUpdatesCLI.php` (Used for the getUpdates method)
- `cron.php` (Used to execute commands via cron)

**Bot Manager**
Using the bot manager makes life much easier, as all configuration goes into a single file, `manager.php`.

If you decide to use the Bot Manager, be sure to [read all about it][4] and change the `require` block in the `composer.json` file:
```json
"require": {
    "php-telegram-bot/telegram-bot-manager": "*"
}
```

Then, edit the following files, replacing all necessary values with those of your project.

- `composer.json` (Describes your project and it's dependencies)
- `manager.php` (Used as the main entry point for everything)

---

Now you can install all dependencies using [composer][5]:
```bash
$ composer install
```

## To be continued!

[1]: https://github.com/php-telegram-bot/core "php-telegram-bot/core"
[2]: https://github.com/php-telegram-bot/core#readme "PHP Telegram Bot - README"
[3]: https://github.com/php-telegram-bot/telegram-bot-manager "php-telegram-bot/telegram-bot-manager"
[4]: https://github.com/php-telegram-bot/telegram-bot-manager#readme "PHP Telegram Bot Manager - README"
[5]: https://getcomposer.org/ "Composer"
