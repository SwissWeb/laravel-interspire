laravel-interspire (for Laravel 5.1)
==================

Interspire API Intergration... This is forked from aflipanci/laravel-interspire and made compatible with Laravel 5.1

## Installation

Add laravel-interspire to your composer.json file:

```
"require": {
  "swissweb/interspire": "dev-master"
}
```

Use composer to install this package.

```
$ composer update
```

### Registering the Package

Register the service provider within the ```providers``` array found in ```config/app.php```:

```php
'providers' => array(
	// ...
	
	Aglipanci\Interspire\InterspireServiceProvider::class,
)
```

Add an alias within the ```aliases``` array found in ```config/app.php```:


```php
'aliases' => array(
	// ...
	
	'Interspire'     => Aglipanci\Interspire\Facades\Interspire::class,
)
```

## Configuration

Create configuration file for package using artisan command

```
$ php artisan vendor:publish
```

And edit the config file with your Interspire API URL, Username and Token.


## Usage

### Basic usage

To add a new Subscriber to a list you should add name, surname, email and the list id (which you get from interspire);

```php
Interspire::addSubscriberToList('John', 'Smith', 'jsmith@gmail.com', 1);
```

To delete an existing Subscriber you need only the email address:

```php
Interspire::deleteSubscriber('jsmith@gmail.com');
```

To check if a subscriber is already on a specific list:

```php
Interspire::isOnList('jsmith@gmail.com', 2)
```

	
