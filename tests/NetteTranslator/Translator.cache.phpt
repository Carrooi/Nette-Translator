<?php

/**
 * Test: DK\NetteTranslator\Translator
 *
 * @testCase DKTests\NetteTranslator\TranslatorCacheTest
 * @author David Kudera
 */

namespace DKTests\NetteTranslator;

use Tester\TestCase;
use Tester\Assert;
use DK\NetteTranslator\Translator;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileJournal;
use Nette\Caching\Storages\FileStorage;

require_once __DIR__. '/bootstrap.php';

/**
 *
 * @author David Kudera
 */
class TranslatorCacheTest extends TestCase
{


	/** @var \DK\NetteTranslator\Translator */
	private $translator;


	protected function setUp()
	{
		$journal = new FileJournal(__DIR__. '/../cache');
		$storage = new FileStorage(__DIR__. '/../cache', $journal);
		$this->translator = new Translator(__DIR__. '/../data');
		$this->translator->setLanguage('en');
		$this->translator->setCacheStorage($storage);
	}


	protected function tearDown()
	{
		$cache = $this->translator->getCache();
		if ($cache !== null) {
			$cache->clean(array(Cache::ALL));
		}

		file_put_contents(__DIR__. '/../data/web/pages/homepage/en.cached.json', '{"# version #": 1, "variable": "1"}');
	}

	public function testSetCache()
	{
		$this->translator->setCache(null);
		Assert::null($this->translator->getCache());
	}


	public function testTranslate()
	{
		$this->translator->translate('web.pages.homepage.promo.title');
		$t = $this->translator->getCache()->load('en:web/pages/homepage/promo');
		Assert::type('array', $t);
		Assert::true(isset($t['title']));
	}


	public function testTranslate_withoutCache()
	{
		$this->translator->setCache(null);
		$t = $this->translator->translate('web.pages.homepage.promo.title');
		Assert::same('Title of promo box', $t);
	}


	public function testInvalidateOnChange()
	{
		$path = __DIR__. '/../data/web/pages/homepage/en.simple.json';
		$data = file_get_contents($path);
		$this->translator->translate('web.pages.homepage.simple.title');
		file_put_contents($path, $data);
		$this->translator->getCache()->release();
		Assert::null($this->translator->getCache()->load('en:web/pages/homepage/simple'));
	}


	public function testTranslate_withVersion()
	{
		$t = $this->translator->translate('web.pages.homepage.cached.variable');
		Assert::same('1', $t);
	}


	public function testTranslate_withVersionLoadOld()
	{
		$this->translator->translate('web.pages.homepage.cached.variable');
		$path = __DIR__. '/../data/web/pages/homepage/en.cached.json';
		file_put_contents($path, '{"# version #": 1, "variable": "2"}');
		$this->translator->invalidate();
		$t = $this->translator->translate('web.pages.homepage.cached.variable');
		Assert::same('1', $t);
	}


	public function testTranslate_withVersionLoadNew()
	{
		$this->translator->translate('web.pages.homepage.cached.variable');
		$path = __DIR__. '/../data/web/pages/homepage/en.cached.json';
		file_put_contents($path, '{"# version #": 2, "variable": "2"}');
		$this->translator->invalidate();
		$t = $this->translator->translate('web.pages.homepage.cached.variable');
		Assert::same('2', $t);
	}

}

\run(new TranslatorCacheTest);
