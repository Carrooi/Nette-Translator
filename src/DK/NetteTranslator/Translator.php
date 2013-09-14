<?php

namespace DK\NetteTranslator;

use DK\Translator\Translator as DKTranslator;
use Nette\Localization\ITranslator;
use Nette\Caching\IStorage;
use Nette\Caching\Cache;

/**
 *
 * @author David Kudera
 */
class Translator extends DKTranslator implements ITranslator
{


	/** @var \Nette\Caching\Cache */
	private $cache;


	/**
	 * @param \Nette\Caching\IStorage $cacheStorage
	 * @return \DK\NetteTranslator\Translator
	 */
	public function setCacheStorage(IStorage $cacheStorage)
	{
		$this->cache = new Cache($cacheStorage, 'translator');
		return $this;
	}


	/**
	 * @return \Nette\Caching\Cache
	 */
	public function getCache()
	{
		return $this->cache;
	}


	/**
	 * @param \Nette\Caching\Cache $cache
	 * @return \DK\NetteTranslator\Translator
	 */
	public function setCache(Cache $cache = null)
	{
		$this->cache = $cache;
		return $this;
	}


	/**
	 * @param string $path
	 * @param string $categoryName
	 * @return array
	 */
	protected function load($path, $categoryName)
	{
		if ($this->cache === null) {
			return parent::load($path, $categoryName);
		} else {
			$language = $this->getLanguage();
			$data = $this->cache->load($language. ':'. $categoryName);

			if ($data === null) {
				$data = parent::load($path, $categoryName);
				$conds = array();
				if (!isset($data['# version #'])) {
					$conds[Cache::FILES] = $path;
				}
				$this->cache->save($language. ':'. $categoryName, $data, $conds);
			} elseif (isset($data['# version #'])) {
				$file = $this->loadFromFile($path);
				if (!isset($file['# version #']) || $data['# version #'] !== $file['# version #']) {
					$data = $this->normalizeTranslations($file);
					$this->cache->save($language. ':'. $categoryName, $data);
				}
			}

			return $data;
		}
	}


	/**
	 * @param array $translations
	 * @return array
	 */
	protected function normalizeTranslations($translations)
	{
		$result = array();
		foreach ($translations as $name => $translation) {
			$list = false;
			if (preg_match('~^--\s(.*)~', $name, $match)) {
				$name = $match[1];
				$list = true;
			}
			if ($name === '# version #') {
				$result[$name] = $translation;
			} elseif (is_string($translation)) {
				$result[$name] = [$translation];
			} elseif (is_array($translation)) {
				$result[$name] = array();
				foreach ($translation as $t) {
					if (is_array($t)) {
						$buf = array();
						foreach ($t as $sub) {
							if (!preg_match('~^\#.*\#$~', $sub)) {
								$buf[] = $sub;
							}
						}
						$result[$name][] = $buf;
					} else {
						if (!preg_match('~^\#.*\#$~', $t)) {
							if ($list === true && !is_array($t)) {
								$t = array($t);
							}
							$result[$name][] = $t;
						}
					}
				}
			}
		}
		return $result;
	}

}