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

####To add a new Subscriber to a list you should add name, surname, email and the list id

```php
Interspire::addSubscriberToList('John', 'Smith', 'jsmith@gmail.com', 1);
```

####To delete an existing Subscriber you need only the email address:

```php
Interspire::deleteSubscriber('jsmith@gmail.com');
```

####To check if a subscriber is already on a specific list:

```php
Interspire::isSubscriberOnList('jsmith@gmail.com', 2)
```

####To add a subscriber to the banned list:

```php
Interspire::addBannedSubscriber('jsmith@gmail.com', 2)
```
if you leave the list_id parameter empty, the ban will be global
	
####To tag a subscriber as bounced

```php
Interspire::bounceSubscriber('jsmith@gmail.com', 2)
```
####To unsubscribe a subscriber

```php
Interspire::unsubscribeSubscriber('jsmith@gmail.com', 2)
```

####To get an array of list(s) that an email is subscribed to

```php
Interspire::getAllListsForEmailAddress('jsmith@gmail.com', '2,3,5')
```
You can filter the lists checked with a coma separated string but if you leave it empty it will check all availables list with the `getLists()` method

####To get all lists that exist

```php
Interspire::getLists()
```


####That's all for now !
