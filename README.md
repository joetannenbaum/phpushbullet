# PHPushbullet

[![Build Status](https://travis-ci.org/joetannenbaum/phpushbullet.svg?branch=master)](https://travis-ci.org/joetannenbaum/phpushbullet)

A PHP library for the [Pushbullet](https://www.pushbullet.com/) API.

## Installation

~~Using [composer](https://packagist.org/packages/joetannenbaum/climate):~~ (WIP, not quite available yet.)

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

This will return an array of objects with all of the device information.

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

### Notes

Arguments:

+ Title
+ Body

```php
$pushbullet->device('Chrome')->note('Musings', 'Why are fudgy brownies better than cakey brownies?');
```

### Links

Arguments:

+ Title
+ URL
+ Body (optional)

```php
$pushbullet->device('Chrome')->link('Look It Up', 'http://google.com', 'I hear this is a good site for finding things.');
```

### Addresses

Arguments:
+ Name
+ Address

```php
$pushbullet->device('Chrome')->address('The Hollywood Sign', '4059 Mt Lee Drive Hollywood, CA 90068');
```

Alternatively, you can pass in an associative array:

```php
$address = [
  'address' => '4059 Mt Lee Drive',
  'city'    => 'Hollywood',
  'state'   => 'CA',
  'zip'     => '90068',
];

$pushbullet->device('Chrome')->address('The Hollywood Sign', $address);
```

### Lists

Arguments:
+ Title
+ Items (array)

```php
$items = [
  'Socks',
  'Pants',
  'Keys',
  'Wallet',
];

$pushbullet->device('Chrome')->list('Do Not Forget', $items);
```

### Files

Arguments:
+ File Name
+ File URL (must be publicly available)
+ Body (optional)

```php
$pushbullet->device('Chrome')->file('The Big Presentation', 'http://example.com/do-not-lose-this.pptx', 'Final version of slides.');
```
