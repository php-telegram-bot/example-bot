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
 * User "/payment" command
 *
 * This command creates an invoice for the user using the Telegram Payments.
 *
 * You will have to set up a payment provider with @BotFather
 * Select your bot and then "Payments". Then choose the provider of your choice.
 *
 * @BotFather will then present you with a payment provider token.
 *
 * Copy this token and set it in your config.php file:
 * ['commands']['configs']['payment'] => ['payment_provider_token' => 'your_payment_provider_token_here']
 *
 * You will also need to copy the `Precheckoutquerycommand.php` file.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Payments\LabeledPrice;
use Longman\TelegramBot\Entities\Payments\SuccessfulPayment;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class PaymentCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'payment';

    /**
     * @var string
     */
    protected $description = 'Create an invoice for the user using Telegram Payments';

    /**
     * @var string
     */
    protected $usage = '/payment';

    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        // Who to send this invoice to. (Use the current user.)
        $chat_id = $this->getMessage()->getFrom()->getId();

        // The currency of this invoice.
        // Supported currencies: https://core.telegram.org/bots/payments#supported-currencies
        $currency = 'EUR';

        // List all items that will be shown on your invoice.
        // Amounts are in cents. So 1 Euro would be put as 100.
        $prices = [
            new LabeledPrice(['label' => 'Small thing', 'amount' => 100]),   //   1€
            new LabeledPrice(['label' => 'Bigger thing', 'amount' => 2000]), //  20€
            new LabeledPrice(['label' => 'Huge thing', 'amount' => 50000]),  // 500€
        ];

        // Request a shipping address if necessary.
        $need_shipping_address = false;

        // If you have flexible pricing, depending on the shipping method chosen, set this to true.
        // You will also need to copy and adapt the `ShippingqueryCommand.php` file.
        $is_flexible = false;

        // Send the actual invoice!
        // Adjust any parameters to your needs.
        return Request::sendInvoice([
            'chat_id'               => $chat_id,
            'title'                 => 'Payment with PHP Telegram Bot',
            'description'           => 'A simple invoice to test Telegram Payments',
            'payload'               => 'payment_demo',
            'start_parameter'       => 'payment_demo',
            'provider_token'        => $this->getConfig('payment_provider_token'),
            'currency'              => $currency,
            'prices'                => $prices,
            'need_shipping_address' => $need_shipping_address,
            'is_flexible'           => $is_flexible,
        ]);
    }

    /**
     * Send "Thank you" message to user who paid
     *
     * You will need to add some code to your custom `GenericmessageCommand::execute()` method.
     * Check the `GenericmessageCommand.php` file included in this folder.
     *
     * @param SuccessfulPayment $payment
     * @param int               $user_id
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public static function handleSuccessfulPayment($payment, $user_id): ServerResponse
    {
        // Send a message to the user after they have completed the payment.
        return Request::sendMessage([
            'chat_id' => $user_id,
            'text'    => 'Thank you for your order!',
        ]);
    }
}
