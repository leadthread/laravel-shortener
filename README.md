# laravel-shortener
[![Latest Version](https://img.shields.io/github/release/zenapply/laravel-shortener.svg?style=flat-square)](https://github.com/zenapply/laravel-shortener/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/zenapply/laravel-shortener.svg?branch=master)](https://travis-ci.org/zenapply/laravel-shortener)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zenapply/laravel-shortener/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/zenapply/laravel-shortener/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/zenapply/laravel-shortener/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/zenapply/laravel-shortener/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/56f3252c35630e0029db0187/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56f3252c35630e0029db0187)
[![Total Downloads](https://img.shields.io/packagist/dt/zenapply/laravel-shortener.svg?style=flat-square)](https://packagist.org/packages/zenapply/laravel-shortener)

Laravel Shortener is a simple package for shortening URL's through various online services. 

Currently supported:
- [Bitly](https://bitly.com/)

## Installation

Install via [composer](https://getcomposer.org/) - In the terminal:
```bash
composer require zenapply/laravel-shortener
```

Now add the following to the `providers` array in your `config/app.php`
```php
Zenapply\Shortener\ShortenerServiceProvider::class
```

and this to the `aliases` array in `config/app.php`
```php
"Shortener" => "Zenapply\Shortener\Facades\Shortener",
```

Then you will need to run these commands in the terminal in order to copy the config file
```bash
php artisan vendor:publish
```

## Usage
First you must change your config file located at `config/shortener.php` with your API credentials
Then you can simply shorten a URL like this:
```php
$url = "https://github.com/zenapply/laravel-shortener";
$shortUrl = Shortener::shorten($url);
```

Laravel Shortener also takes advantage or Laravel's caching features. Just simply edit your config file to change the caching variables.

Dont forget to add this to the top of the file 
```php
//If you updated your aliases array in "config/app.php"
use Shortener;
//or if you didnt...
use Zenapply\Shortener\Facades\Shortener;
```
