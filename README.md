# nette-translator

This translator is just extended version of [sakren/translator](https://github.com/sakren/php-translator) for Nette framework.

For full documentation, look [here](https://github.com/sakren/php-translator/blob/master/README.md).

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

* 1.0.1
	+ Just some typo

* 1.0.0
	+ First version