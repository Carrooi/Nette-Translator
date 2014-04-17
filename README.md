[![Build Status](https://travis-ci.org/sakren/nette-translator.png?branch=master)](https://travis-ci.org/sakren/nette-translator)

[![Donate](http://b.repl.ca/v1/donate-PayPal-brightgreen.png)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=S3EYEQST8ZD5Y)

# nette-translator

This translator is just extended version of [sakren/translator](https://github.com/sakren/php-translator) for Nette framework.

For full documentation, look [here](https://github.com/sakren/php-translator/blob/master/README.md).

## Installation

Preferred way is to install via [composer](http://getcomposer.org/).

```
php composer.phar require sakren/nette-translator
```

## Usage

You can enable this package in your neon configuration.

```
extensions:
	translator: DK\NetteTranslator\TranslatorExtension
```

or if you are using Nette < 2.1 put this into your bootstrap.php:

```
DK\NetteTranslator\TranslatorExtension::register($configurator);
```

## Configuration

config.neon:

```
translator:
	directory: /path/to/my/dictionaries		# required
	language: en							# required
	caching: true							# this will just use cacheStorage service registered in you DI
	replacements:							# list of replacements
		name: This is name of my website
```

## Changelog

* 1.1.0
	+ See changelog of [php-translator]() to see rest of changes (https://github.com/sakren/php-translator#changelog) (version 1.5.0 - 1.6.1)
	+ Updated dependencies
	+ Tests were not working in clean installation
	+ Updates because of base translator

* 1.0.3
	+ Updated base translator

* 1.0.2
	+ Info about installation

* 1.0.1
	+ Just some typo

* 1.0.0
	+ First version