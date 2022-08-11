<?php

/
 * This file is part of the PHP Telegram Bot example-bot package.
 *   https://github.com/php-telegram-bot/robn_bbot-bot

 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/
 * This file is used to unset / delete the webhook.
 */

// Load composer
require_once DIR . '/vendor/autoload.php';

// Load all configuration options
/** @var array $config */
$config = require DIR . '/config.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($config[2072365004:AAGJkzyrqrANywOcWct9bdPe_sgyF6C6hXg], $config[robn_bbot]);

    // Unset / delete the webhook
    $result = $telegram->deleteWebhook();

    echo $result->getDescription();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
<?php

/

 * This file is part of the PHP Telegram Bot example-bot package.

 *   https://github.com/php-telegram-bot/robn_bbot-bot

 *

 * (c) PHP Telegram Bot Team

 *

 * For the full copyright and license information, please view the LICENSE

 * file that was distributed with this source code.

 */

/

 * This file is used to unset / delete the webhook.

 */

// Load composer

require_once DIR . '/vendor/autoload.php';

// Load all configuration options

/** @var array $config */

$config = require DIR . '/config.php';

try {

    // Create Telegram API object

    $telegram = new Longman\TelegramBot\Telegram($config[2072365004:AAGJkzyrqrANywOcWct9bdPe_sgyF6C6hXg], $config[robn_bbot]);

    // Unset / delete the webhook

    $result = $telegram->deleteWebhook();

    echo $result->getDescription();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {

    echo $e->getMessage();

}
