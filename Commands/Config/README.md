# Config

Custom configurations can be passed to commands that support them.

This feature is mainly used to pass secrets or special values to the commands.

## Adding configurations to your config

It is very easy to add configurations to `config.php`:
```php
'commands' => [
    'configs' => [
        'yourcommand' => ['your_config_key' => 'your_config_value'],
    ],
],
```

Alternatively, you can set them directly via code in your `hook.php`:
```php
$telegram->setCommandConfig('yourcommand', ['your_config_key' => 'your_config_value']);
```

## Reading configurations in your command

To read any command configurations, you can fetch them from within your command like this:
```php
$my_config = $this->getConfig('your_config_key'); // 'your_config_value'
```
