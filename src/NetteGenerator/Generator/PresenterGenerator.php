<?php

namespace NetteGenerator\Generator;

use Nette\Utils\PhpGenerator\ClassType;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @property string $name
 * @property bool $secured
 * @property string $abstract
 * @property string $abstractSecure
 * @property string $module
 * @property string $modulePrefix
 * @property string $factory
 * @property string $dao
 *
 */
class PresenterGenerator extends AbstractGenerator {


	/**
	 * @return string
	 */
	public function execute()
	{
			$classType = new ClassType($this->name);
			$classType->addExtend($this->secured ? $this->abstractSecure : $this->abstract);

			foreach ($this->methods as $name => $arguments) {
				$method = $classType->addMethod($name);
				foreach ($arguments as $argument => $type) {
					$parameter = $method->addParameter($argument);
					$parameter->setTypeHint($type);
				}
			}

			foreach ($this->properties as $name => $type) {
				if (mb_substr($name, 0, 1) === '!') {
					$name = mb_substr($name, 1);

					$property = $classType->addProperty($name);
					$property->addDocument('@autowire(factory="' . $this->factory . '", "' . $type . '")');
					$property->addDocument("@var " . $this->dao);
				} else {
					$property = $classType->addProperty($name);
					$property->addDocument('@autowire');
					$property->addDocument('@var ' . $type);
				}
			}

			$fileType = $this->createFileType($classType);
			$fileType->setNamespace($this->modulePrefix . $this->module);
			return $this->save($fileType);
	}



}