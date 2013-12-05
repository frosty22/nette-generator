<?php

namespace NetteGenerator\DI;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class GeneratorExtension extends \Nette\Config\CompilerExtension
{


	/**
	 * @var array
	 */
	private $defaults = array(
		'author'	=> array(
			'name'		=> 'John Doe',
			'nick'		=> 'john.doe',
			'email'		=> 'john@doe.com'
		),
		'commands'	=> array(
			'presenter'	=> 'NetteGenerator\Command\PresenterCommand',
			'query'		=> 'NetteGenerator\Command\QueryCommand'
		),
		'generators' => array(
			'presenter' => 'NetteGenerator\Generator\PresenterGenerator',
			'query'		=> 'NetteGenerator\Generator\QueryGenerator'
		),
		'config' => array(
			'presenter' => array(
				'dao'				=> '\Kdyby\Doctrine\EntityDao',
				'factory'			=> '\Ale\DaoFactory',
				'dir'				=> '%appDir%/{module}/presenters',
				'name'				=> 'TestPresenter',
				'abstract'			=> 'BasePresenter',
				'abstractSecure'	=> 'SecuredPresenter',
				'module'			=> 'FrontModule',
				'modulePrefix'		=> 'App\\',
				'secured'			=> FALSE
			),
			'query'		=> array(
				'name'				=> 'TestQuery',
				'namespace'			=> 'Test',
				'prefix'			=> 'App\\',
				'extend'			=> '\Kdyby\Doctrine\QueryObject',
				'dir'				=> '%appDir%/libs/{namespace}/queries'
			)
		)
	);


	/**
	 * Base configuration
	 */
	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		foreach ($config['commands'] as $name => $class) {
			$builder->addDefinition($this->prefix('command.' . $name))
				->setClass($class)
				->addTag('kdyby.console.command');
		}

		foreach ($config['generators'] as $name => $class) {
			$builder->addDefinition($this->prefix('generator.' . $name))
				->setClass($class, array($config['config'][$name], $config['author']));
		}

	}


}