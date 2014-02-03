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

```
$translator = new \DK\NetteTranslator\Translator(__DIR__. '/path/to/my/dictionaries');
$translator->setLanguage('en');
```

## Caching

Turning on cache will make loading your dictionaries faster. They don't need to be parsed in any way, because parsed version
is already in cache.

This translator uses cache from Nette framework.

```
$fileJournal = new \Nette\Caching\Storages\FileJournal(__DIR__. '/path/to/cache');
$fileStorage = new \Nette\Caching\Storages\FileStorage(__DIR__. '/path/to/cache');

$translator->setCacheStorage($fileStorage);

// or directly

$translator->setCache($myOwnInstanceOfCache);
```

## Changelog

* 1.0.3
	+ Updated base translator

* 1.0.2
	+ Info about installation

* 1.0.1
	+ Just some typo

* 1.0.0
	+ First version