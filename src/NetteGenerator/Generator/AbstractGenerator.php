<?php

namespace NetteGenerator\Generator;

use Nette\Utils\PhpGenerator\ClassType;
use NetteGenerator\FileType;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
abstract class AbstractGenerator {


	/**
	 * @var array
	 */
	private $settings = array();


	/**
	 * @var array
	 */
	private $author = array();


	/**
	 * @var array
	 */
	protected $methods = array();


	/**
	 * @var array
	 */
	protected $properties = array();


	/**
	 * @var array
	 */
	protected $arguments = array();


	/**
	 * @param array $settings
	 * @param array $author
	 */
	public function __construct(array $settings, array $author)
	{
		$this->settings = $settings;
		$this->author = $author;
	}


	/**
	 * @param string $name
	 * @param array $arguments
	 * @return $this
	 */
	public function addMethod($name, array $arguments = array())
	{
		$this->methods[$name] = $arguments;
		return $this;
	}


	/**
	 * @param string $name
	 * @param string $type
	 * @param string|NULL $factory
	 * @return $this
	 */
	public function addProperty($name, $type, $factory = NULL)
	{
		if (mb_substr($type, 0, 1) !== "\\" && $type != "") $type = "\\" . $type;
		$this->properties[$name] = $factory ? array($type, $factory) : $type;
		return $this;
	}


	/**
	 * @param string $name
	 * @param null|string $type
	 * @return $this
	 */
	public function addConstructArgument($name, $type = NULL)
	{
		$this->arguments[$name] = $type;
		return $this;
	}


	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return isset($this->settings[$name]) ? $this->settings[$name] : NULL;
	}


	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->settings[$name] = $value;
	}


	/**
	 * @return
	 */
	abstract public function execute();


	/**
	 * @param ClassType $classType
	 * @return FileType
	 */
	protected function createFileType(ClassType $classType)
	{
		$fileType = new FileType($classType);
		$fileType->setAuthor($this->author['name'], $this->author['nick'], $this->author['email']);
		return $fileType;
	}


	/**
	 * @param FileType $fileType
	 * @return string
	 */
	protected function save(FileType $fileType)
	{
		$replacements = array();
		foreach ($this->settings as $key => $value) {
			if (is_string($value)) {
				$value = str_replace('\\', '/', $value);
				$replacements["{{$key}}"] = $value;
			}
		}

		$dir = str_replace(array_keys($replacements), $replacements, $this->dir);
		return $fileType->save($dir);
	}

}