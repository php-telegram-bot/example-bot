# Telegram Payments

With the files in this folder, you can create and send invoices to your users, require their shipping details, define custom / flexible shipping methods and send a confirmation message after payment.

## Enable payments for your bot

Read all about Telegram Payments and follow the setup guide here:
https://core.telegram.org/bots/payments

## Configuring the `/payment` command

First of all, as a bare minimum, you need to copy the [`PaymentCommand.php`](PaymentCommand.php) and [`PrecheckoutqueryCommand.php`](PrecheckoutqueryCommand.php) files in this folder to your custom commands folder.

If you want to allow flexible shipping options, you will also need to copy [`ShippingqueryCommand.php`](ShippingqueryCommand.php) to your custom commands folder.

Should you want to send a message on a successful payment, you will need to copy the [`GenericmessageCommand.php`](GenericmessageCommand.php) file as well.
If you already have a `GenericmessageCommand.php` file, you'll need to copy the code from the `execute` method into your file.

Next, you will need to add the Payment Provider Token (that you received in the previous step when linking your bot), to your `hook.php` or `manager.php` config.

For `hook.php`:
```php
$telegram->setCommandConfig('payment', ['payment_provider_token' => 'your_payment_provider_token_here']);
```

For `manager.php` or using a general `config.php`, in the config array add:
```php
...
'commands' => [
    'configs' => [
        'payment' => ['payment_provider_token' => 'your_payment_provider_token_here'],
    ],
],
...
```

Now, when sending the `/payment` command to your bot, you should receive an invoice.

Have fun with Telegram Payments!
