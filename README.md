# Qvapay Laravel
An easy to use Qvapay API wrapper for Laravel 7, 8 and 9.

## Introduction
Qvapay-Laravel makes working with the Qvapay API a breeze. It provides straight forward methods for each endpoint.

The package supports all Qvapay API endpoints that are accessible with the [App Credentials](https://qvapay.com/apps/create).

## Installation
Install the package using Composer. The package will automatically register itself.

```bash
composer require ovillafuerte94/qvapay-laravel
```

Publish the config of the package.

```bash
php artisan vendor:publish --provider="Ovillafuerte94\QvapayLaravel\Providers\Qvapay"
```

The following config will be published to `config/qvapay.php`.

```php
return [

    /*
    |--------------------------------------------------------------------------
    | App Credentials
    |--------------------------------------------------------------------------
    */

    'app_id' => env('QVAPAY_APP_ID'),
    'app_secret' => env('QVAPAY_APP_SECRET'),
];
```

Set the `APP ID` and `APP Secret` of your [Qvapay App](https://qvapay.com/apps) in your `.env` file.

```env
QVAPAY_APP_ID=********************************
QVAPAY_APP_SECRET=********************************
```

## Usage Example
Import the package at the top of your file. All of the following examples use the [Facade](https://laravel.com/docs/master/facades).

```php
use Ovillafuerte94\QvapayLaravel\Facades\Qvapay;

# Get your app info
Qvapay::info()

# Create an invoice
Qvapay::create_invoice([
    'amount' => 10,
    'description' => 'Ebook',
    'remote_id' => 'EE-BOOk-123' # example remote invoice id
]);

# Get transaction
Qvapay::transaction($uuid);

# Get transactions
Qvapay::transactions();

# Get your account balance
Qvapay::balance();
```

You can also read the QvaPay API documentation: [https://qvapay.com/docs](https://qvapay.com/docs).

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Omar Villafuerte](https://github.com/ovillafuerte94)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
