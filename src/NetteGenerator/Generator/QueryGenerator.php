<?php

namespace NetteGenerator\Generator;

use Nette\Utils\PhpGenerator\ClassType;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @property string $name
 * @property string $namespace
 * @property string $extend
 * @property string $prefix
 *
 */
class QueryGenerator extends AbstractGenerator {


	/**
	 * @return string
	 */
	public function execute()
	{
		$classType = new ClassType($this->name);
		$classType->addExtend($this->extend);

		if (count($this->arguments)) {
			$method = $classType->addMethod('__construct');

			foreach ($this->arguments as $name => $type) {
				$method->addDocument('@param ' . $type . ' $' . $name);
				$method->addBody('$this->' . $name . ' = $' . $name . ';');
				$parameter = $method->addParameter($name);

				if ($type)
					$parameter->setTypeHint($type);

				$property = $classType->addProperty($name);
				$property->setVisibility('private');
				$property->addDocument('@var ' . $type);
			}
		}

		foreach ($this->properties as $name => $type) {
			$method = $classType->addMethod('set' . ucfirst($name));
			$method->addDocument("@param $type|NULL $" . $name);
			$method->setBody('$this->' . $name . ' = $' . $name . ';');

			$parameter = $method->addParameter($name);
			$parameter->setOptional(TRUE);

			if ($type)
				$parameter->setTypeHint($type);

			$property = $classType->addProperty($name);
			$property->setVisibility('private');

			if ($type)
				$property->addDocument('@var ' . $type);
		}

		$method = $classType->addMethod('doCreateQuery');
		$method->setVisibility('protected');
		$method->addDocument('@param \Kdyby\Persistence\Queryable $queryBuilder');
		$method->addDocument('@return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder');

		$method->addParameter('queryBuilder')
			->setTypeHint('\Kdyby\Persistence\Queryable');


		$fileType = $this->createFileType($classType);
		$fileType->setNamespace($this->prefix . ucfirst($this->namespace));
		return $this->save($fileType);
	}



}