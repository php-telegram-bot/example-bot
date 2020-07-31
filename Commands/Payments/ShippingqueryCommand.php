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
 * Shipping query required for "/payment" command with flexible shipping method.
 *
 * In this command, you can perform any necessary verifications and checks
 * to adjust the available shipping options of the payment.
 *
 * For example, if the user has a "Free Delivery" subscription or something like that.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\Payments\LabeledPrice;
use Longman\TelegramBot\Entities\Payments\ShippingOption;
use Longman\TelegramBot\Entities\ServerResponse;

class ShippingqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'shippingquery';

    /**
     * @var string
     */
    protected $description = 'Shipping Query Handler';

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
        // Here you can check the shipping details and adjust the Shipping Options accordingly.
        // For this demo, let's simply define some fixed shipping options, a "Basic" and "Premium" shipping method.
        return $this->getShippingQuery()->answer(true, [
            'shipping_options' => [
                new ShippingOption([
                    'id'     => 'basic',
                    'title'  => 'Basic Shipping',
                    'prices' => [
                        new LabeledPrice(['label' => 'Basic Shipping', 'amount' => 800]),
                    ],
                ]),
                new ShippingOption([
                    'id'     => 'premium',
                    'title'  => 'Premium Shipping',
                    'prices' => [
                        new LabeledPrice(['label' => 'Premium Shipping', 'amount' => 1500]),
                        new LabeledPrice(['label' => 'Extra speedy', 'amount' => 300]),
                    ],
                ]),
            ],
        ]);

        // If we do make certain checks, you can define the error message displayed to the user like this.
        // return $this->getShippingQuery()->answer(false, [
        //     'error_message' => 'We do not ship to your location :-(',
        // ]);
    }
}
