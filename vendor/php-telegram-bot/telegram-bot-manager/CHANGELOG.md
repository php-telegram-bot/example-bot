# Changelog
The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

Exclamation symbols (:exclamation:) note something of importance e.g. breaking changes. Click them to learn more.

## [Unreleased]
### Notes
- [:ledger: View file changes][Unreleased]
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security

## [2.1.0] - 2023-05-27
### Notes
- [:ledger: View file changes][2.1.0]
### Changed
- Bumped core to version 0.81.*.

## [2.0.0] - 2022-07-04
### Notes
- [:ledger: View file changes][2.0.0]
### Added
- Enforce `secret_token` webhook validation check.
### Changed
- Bumped core to version 0.78.*.
- Upgrade code to PHP 8.0.
- Moved tests to GitHub Actions.
### Security
- Minimum PHP 8.0.

## [1.7.0] - 2021-06-14
### Notes
- [:ledger: View file changes][1.7.0]
### Changed
- Bumped core to version 0.73.*.
### Fixed
- `chat_id` output for `getUpdates` method. (#65)

## [1.6.0] - 2020-12-26
### Notes
- [:ledger: View file changes][1.6.0]
### Added
- Allow webhook to contain custom query parameters. (#59)
### Changed
- Upgraded dependencies and bumped core to version 0.70.*.
- Upgrade code to PHP 7.3.
- Update Travis-CI and Scrutinizer configs.
### Removed
- [:exclamation:][1.6.0-bc-remove-botmanager-initlogging] Remove `BotManager::initLogging()` method and require separate logging initialisation with `TelegramLog::initialize`.
### Security
- Minimum PHP 7.3, allow PHP 8.0.

## [1.5.0] - 2019-07-29
### Notes
- [:ledger: View file changes][1.5.0]
### Changed
- Upgraded dependencies and bumped core to version 0.59.*. (#48)
- Code style is now PSR12. (#48)
- Adopt issue templates and git/GitHub related meta from upstream core. (#49)
- Simplify FQNs, cleanup tests and update changelog. (#51)
### Removed
- Botan.io has been removed (see php-telegram-bot/core#924). (#50)
### Fixed
- Fix and improve getUpdates output. (#52)
- Don't output deprecation notices if no logging is enabled. (#53)
### Security
- Security disclosure managed by [Tidelift]. (#49)

## [1.4.0] - 2019-06-01
### Notes
- [:ledger: View file changes][1.4.0]
### Added
- Test up to PHP 7.3 in Travis-CI. (#47)
### Changed
- Use the new Telegram API webhook IP ranges. (#46)
- Upgraded dependencies and bumped core to version 0.57.0. (#47)
### Security
- Minimum PHP version is now 7.1. (#47)

## [1.3.0] - 2018-07-21
### Notes
- [:ledger: View file changes][1.3.0]
### Added
- Allow usage of table prefixes and custom encoding.
- Add error message when trying to use getUpdates without database connection. (#41)
### Changed
- Upgraded dependencies and bumped core to version 0.54.0.

## [1.2.2] - 2017-08-26
### Notes
- [:ledger: View file changes][1.2.2]
### Added
- Linked version numbers in changelog for easy verification of code changes.
### Changed
- Upgraded dependencies and bumped core to version 0.48.0.

## [1.2.1] - 2017-07-12
### Notes
- [:ledger: View file changes][1.2.1]
### Fixed
- Secret should not be required when using CLI for getUpdates. (#36)

## [1.2.0] - 2017-07-10
### Notes
- [:ledger: View file changes][1.2.0]
### Added
- Custom output callback can be defined for getUpdates method. (#34)
### Changed
- Default output of getUpdates method now shows the message type or query text, not the text message content. (#34)
### Fixed
- GetUpdates method would crash if a non-text message was sent. (#34)

## [1.1.0] - 2017-05-23
### Notes
- [:ledger: View file changes][1.1.0]
### Added
- `webhookinfo` action to get result from `getWebhookInfo`.
### Changed
- Clean up and refactor some methods.
### Fixed
- Passing an empty array to `webhook.allowed_updates` parameter now correctly resets to defaults.

## [1.0.1] - 2017-05-09
### Notes
- [:ledger: View file changes][1.0.1]
### Changed
- Use more stable `longman/ip-tools` for IP matching.

## [1.0.0] - 2017-05-08
### Notes
- [:ledger: View file changes][1.0.0]
### Changed
- [:exclamation:][1.0.0-bc-move] Move to `php-telegram-bot/telegram-bot-manager` on packagist.
- [:exclamation:][1.0.0-bc-move] Move to `TelegramBot\TelegramBotManager` namespace.

## [0.44.0] - 2017-05-05
### Notes
- [:ledger: View file changes][0.44.0]
### Added
- Ability to define custom valid IPs to access webhook.
- Execute commands via cron, using `cron` action and `g` parameter.
### Changed
- [:exclamation:][0.44.0-bc-parameter-structure] Remodelled the parameter array to a more flexible structure.
- `bot_username` and `secret` are no longer vital parameters.
### Fixed
- Initialise loggers before anything else, to allow logging of all errors.
### Security
- Enforce non-empty secret when using webhook.

## [0.43.0] - 2017-04-17
### Notes
- [:ledger: View file changes][0.43.0]
### Added
- PHP CodeSniffer introduced and cleaned code to pass tests.
- Custom exceptions for better error handling.
- Request limiter options.

## [0.42.0.1] - 2017-04-11
### Notes
- [:ledger: View file changes][0.42.0.1]
### Added
- Changelog.
### Changed
- :exclamation: Rename vital parameter `botname` to `bot_username` everywhere.
### Fixed
- Some code style issues.

## [0.42.0] - 2017-04-10
### Notes
- [:ledger: View file changes][0.42.0]
### Changed
- Move to PHP Telegram Bot organisation.
- Mirror version with core library.
- Update repository links.
### Fixed
- Readme formatting.

## [0.4.0] - 2017-02-26
### Notes
- [:ledger: View file changes][0.4.0]
### Added
- Latest Telegram Bot limiter functionality.
### Fixed
- Travis tests, using MariaDB instead of MySQL.

## [0.3.1] - 2017-01-04
### Notes
- [:ledger: View file changes][0.3.1]
### Fixed
- Make CLI usable again after setting up Telegram API IP address limitations.

## [0.3.0] - 2016-12-25
### Notes
- [:ledger: View file changes][0.3.0]
### Added
- Latest changes from PHP Telegram API bot.
### Security
- Request validation to secure the script to allow only Telegram API IPs of executing the webhook handle.

## [0.2.1] - 2016-10-16
### Notes
- [:ledger: View file changes][0.2.1]
### Added
- Interval between updates can be set via parameter.

## [0.2] - 2016-09-16
### Notes
- [:ledger: View file changes][0.2]
### Changed
- Force PHP7.

## [0.1.1] - 2016-08-20
### Notes
- [:ledger: View file changes][0.1.1]
### Fixed
- Tiny conditional fix to correct the output.

## [0.1] - 2016-08-20
### Notes
- [:ledger: View file changes][0.1]
### Added
- First minor version that contains the basic functionality.

[Tidelift]: https://tidelift.com/subscription/pkg/packagist-php-telegram-bot-telegram-bot-manager?utm_source=packagist-php-telegram-bot-telegram-bot-manager&utm_medium=referral&utm_campaign=changelog

[1.6.0-bc-remove-botmanager-initlogging]: https://github.com/php-telegram-bot/telegram-bot-manager/wiki/Breaking-backwards-compatibility#remove-botmanagerinitlogging "Remove BotManager::initLogging()"
[1.0.0-bc-move]: https://github.com/php-telegram-bot/telegram-bot-manager/wiki/Breaking-backwards-compatibility#namespace-and-package-name-changed "Namespace and package name changed"
[0.44.0-bc-parameter-structure]: https://github.com/php-telegram-bot/telegram-bot-manager/wiki/Breaking-backwards-compatibility#parameter-structure-changed "Parameter structure changed"

[Unreleased]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/master...develop
[2.1.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.7.0...2.0.0
[1.7.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.6.0...1.7.0
[1.6.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.5.0...1.6.0
[1.5.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.4.0...1.5.0
[1.4.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.2.2...1.3.0
[1.2.2]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.44.0...1.0.0
[0.44.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.43.0...0.44.0
[0.43.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.42.0.1...0.43.0
[0.42.0.1]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.42.0...0.42.0.1
[0.42.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.4.0...0.42.0
[0.4.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.3.1...0.4.0
[0.3.1]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.3.0...0.3.1
[0.3.0]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.2.1...0.3.0
[0.2.1]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.2...0.2.1
[0.2]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.1.1...0.2
[0.1.1]: https://github.com/php-telegram-bot/telegram-bot-manager/compare/0.1...0.1.1
