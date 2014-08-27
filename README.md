PHPushbullet
============

A PHP library for the [Pushbullet](https://www.pushbullet.com/) API.

## Installation

Using [composer](https://packagist.org/packages/joetannenbaum/climate):

```
{
    "require": {
        "joetannenbaum/phpushbullet": "0.1.*"
    }
}
```

PHPushbullet takes one optional parameter, your [Pushbullet access token](https://www.pushbullet.com/account):

```php
require_once('vendor/autoload.php');

$pushbullet = new JoeTannenbaum\PHPushbullet\PHPushbullet('YOUR_ACCESS_TOKEN_HERE');
```

If you do not wish to put your access token in your code (understandable), simply set it to the environment variable `pushbullet.access_token` and PHPushbullet will automatically pick it up.

## Listing Devices

To list the available devices on your account:

```php
$pushbullet->devices();
```

## Pushing to Devices

When pushing a to a device, simply use the device's `nickname` or their `iden` from the list above.

To push to a single device:

```php
$pushbullet->device('Chrome')->note('Remember', 'Buy some eggs.');
```

To push to multiple devices:

```php
$pushbullet->device('Chrome')->device('Galaxy S4')->note('Remember', 'Buy some eggs.');
// or
$pushbullet->device('Chrome', 'Galaxy S4')->note('Remember', 'Buy some eggs.');
```


###
