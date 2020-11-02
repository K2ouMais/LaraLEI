<img src="https://banners.beyondco.de/LaraLEI.png?theme=light&packageName=k2oumais%2Flaralei&pattern=architect&style=style_1&description=A+simple+package+to+generate%2C+validate+and+search+for+a+LEI+%28Legal+Entity+Identifier%29.&md=1&fontSize=100px&images=calculator" alt="k2oumais/laralei">

# LaraLEI

A simple package to generate, validate and search for a LEI (Legal Entity Identifier).

- Generate a LEI number
- Check if a LEI number is valid.
- Search for a public LEI number.

### Install

```bash
composer require k2oumais/laralei
```

### Configuration

In order to generate a LEI with your own LOU (Local Operating Unit) prefix you can 
change it in the ```/config/LaraLEI.php``` after publishing the config file.
```bash
php artisan vendor:publish --provider="K2ouMais\LaraLEI\LEIServiceProvider"
```

### Usage

```php
use K2ouMais\LaraLEI\LEI;

$lei = LEI();

// Generate a new LEI
$lei->generate(); // string

// Validate LEI
$lei->validate("INR2EJN1ERAN0W5ZP974"); // bool

// Search for a LEI
$lei->search("INR2EJN1ERAN0W5ZP974"); // json
```

### Requirements

- PHP 7.*
- Laravel 7.*
