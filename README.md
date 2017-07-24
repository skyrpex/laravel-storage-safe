# Laravel Storage Safe

This package offers a storage-safe application to substitute the one that comes with Laravel 5.2.

> A storage-safe application creates the required storage directories in a temporary directory. This directory is named using a hash of the user ID who executes the script and the absolute path to the original application.

## Installation

`composer require pallares/laravel-storage-safe`

## Usage

> It is NOT recommended to use this application directly in production environments, as it will slow down the startup process.

In order to use it with minimal impact, please amend the `bootstrap/app.php` file:

```php
// ...

// Laravel may fail to use the storage if the user who executes
// the script is different from the user who owns this file.
$app = function_exists('posix_geteuid') && (posix_geteuid() !== fileowner(__FILE__))
    ? new Pallares\Laravel\StorageSafe\Application(realpath(__DIR__.'/../'))
    : new Illuminate\Foundation\Application(realpath(__DIR__.'/../'));

// ...
```

The snippet above will use the storage-safe application on Posix systems and only when the user that executes the script is different than the user of the current file.

Of course, you can use your own methods to check this scenario. For example, I can safely tell that I'll need the storage-safe app if the directory where the code is begins with `/home/`. This check is faster than calling `posix_*` and `fileowner`.

## Why this package?

Chances are that you don't need this package. I do because I have certain environments where the Apache's daemon user writes inside my current app's storage.

Chmod'ing the storage directory won't solve my problem as I'm not a sudo'er, so I can't remove deamon's files 💩.
