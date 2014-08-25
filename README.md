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
Store your [Pushbullet access token](https://www.pushbullet.com/account) in your .env file, like so:
```php
return [
  ...
  'pushbullet' => [
  		'access_token' => 'YOUR_TOKEN_HERE',
  	],
]
```
Then just fire it up:

```php
require_once('vendor/autoload.php');

$pushbullet = new JoeTannenbaum\PHPushbullet\PHPushbullet;
```

## Listing Devices

To list the available devices on your account:

```php
$pushbullet->devices();
```
