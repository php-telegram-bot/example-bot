# PHP Telegram Bot Example

> :construction: Work In Progress :construction:

An A-Z example of Telegram bot using the [PHP Telegram Bot][core-github] library.

This repository aims to demonstrate the usage of all the features offered by the PHP Telegram Bot library and as such contains all example commands.
Also, it gives an example setup for both the standard usage and using the [PHP Telegram Bot Manager][bot-manager-github] 

**:exclamation: Important!**
- Most of the commands found here are **not to be used exactly as they are**, they are mere demonstrations of features! They are provided as-is and any **extra security measures need to be added by you**, the developer.
- Before getting started with this project, make sure you have read the official [readme][core-readme-github] to understand how the PHP Telegram Bot library works and what is required to run a Telegram bot.

Let's get started then! :smiley:

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

Now you can choose what installation you would like, either the default one or using the [Bot Manager][bot-manager-github] project.
Depending on which one you choose, you can delete the files that are not required.

---

First of all, you need to rename `config.example.php` to `config.php` and then replace all necessary values with those of your project.

**Default**
Some of these files require extra configurations to be added. Check `hook.php` how they are loaded.
Thanks to reading the main readme file, you should know what these files do.

- `composer.json` (Describes your project and it's dependencies)
- `set.php` (Used to set the webhook)
- `unset.php` (Used to unset the webhook)
- `hook.php` (Used for the webhook method)
- `getUpdatesCLI.php` (Used for the getUpdates method)
- `cron.php` (Used to execute commands via cron)

**Bot Manager**
Using the bot manager makes life much easier, as all configuration goes into a single file, `manager.php`.

If you decide to use the Bot Manager, be sure to [read all about it][bot-manager-readme-github] and change the `require` block in the `composer.json` file:
```json
"require": {
    "php-telegram-bot/telegram-bot-manager": "*"
}
```

Then, edit the following files, replacing all necessary values with those of your project.

- `composer.json` (Describes your project and it's dependencies)
- `manager.php` (Used as the main entry point for everything)

---

Now you can install all dependencies using [composer]:
```bash
$ composer install
```

## 2. Adding your own commands

You can find a few example commands in the [`Commands`](Commands) folder.

Do **NOT** just copy all of them to your bot, but instead learn from them and only add to your bot what you need.

Adding any extra commands to your bot that you don't need can be a security risk!

## To be continued!

[core-github]: https://github.com/php-telegram-bot/core "php-telegram-bot/core"
[core-readme-github]: https://github.com/php-telegram-bot/core#readme "PHP Telegram Bot - README"
[bot-manager-github]: https://github.com/php-telegram-bot/telegram-bot-manager "php-telegram-bot/telegram-bot-manager"
[bot-manager-readme-github]: https://github.com/php-telegram-bot/telegram-bot-manager#readme "PHP Telegram Bot Manager - README"
[composer]: https://getcomposer.org/ "Composer"
