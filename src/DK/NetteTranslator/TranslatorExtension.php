<?php

namespace DK\NetteTranslator;

use Nette\DI\CompilerExtension;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\Diagnostics\Debugger;

/**
 *
 * @author David Kudera
 */
class TranslatorExtension extends CompilerExtension
{


	/** @var array  */
	private $defaults = array(
		'directory' => null,
		'language' => 'en',
		'caching' => false,
		'debugger' => false,
		'replacements' => array(),
	);


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$translator = $builder->addDefinition($this->prefix('translator'))
			->setClass('DK\NetteTranslator\Translator', array($config['directory']))
			->addSetup('setLanguage', array($config['language']));

		if ($config['caching']) {
			$translator->addSetup('setCacheStorage', array('@Nette\Caching\IStorage'));
		}

		foreach ($config['replacements'] as $name => $value) {
			$translator->addSetup('addReplacement', array($name, $value));
		}

		if ($config['debugger']) {
			$builder->addDefinition($this->prefix('panel'))
				->setClass('DK\NetteTranslator\Panel')
				->addSetup('register')
				->addTag('run');
		}
	}


	/**
	 * @param \Nette\Configurator $configurator
	 * @param string $name
	 */
	public static function register(Configurator $configurator, $name = 'translator')
	{
		$configurator->onCompile[] = function($config, Compiler $compiler) use ($name) {
			$compiler->addExtension($name, new TranslatorExtension);
		};
	}

}
 