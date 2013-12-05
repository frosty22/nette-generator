<?php

namespace NetteGenerator;

use Nette\Utils\PhpGenerator\ClassType;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class FileType extends \Nette\Object {


	/**
	 * @var ClassType
	 */
	private $classType;


	/**
	 * Namespace
	 * @var string
	 */
	private $namespace;


	/**
	 * List of uses
	 * @var array
	 */
	private $uses = array();


	/**
	 * Author
	 * @var array
	 */
	private $author = array(
		'name'	=> '',
		'nick'	=> '',
		'email'	=> ''
	);


	/**
	 * @param ClassType $classType
	 */
	public function __construct(ClassType $classType)
	{
		$this->classType = $classType;
	}


	/**
	 * @param string $namespace
	 * @return $this
	 */
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}


	/**
	 * @param string $namespace
	 * @param null|string $alias
	 * @return $this
	 */
	public function addUse($namespace, $alias = NULL)
	{
		$this->uses[$namespace] = $alias;
		return $this;
	}


	/**
	 * @param string $name
	 * @param string $nick
	 * @param string $email
	 * @return $this
	 */
	public function setAuthor($name, $nick, $email)
	{
		$this->author = array(
			'name'	=> $name,
			'nick'	=> $nick,
			'email'	=> $email
		);
		return $this;
	}


	/**
	 * Save output to the file
	 * @param string $dir
	 * @return string
	 * @throws FileAlreadyExistsException
	 * @throws DirNotFoundException
	 */
	public function save($dir)
	{
		if (!is_dir($dir))
			throw new DirNotFoundException('Dir not found: ' . $dir);

		$path = $dir . "/" . $this->classType->name . ".php";

		if (file_exists($path))
			throw new FileAlreadyExistsException('File already exists: ' . $path);

		file_put_contents($path, $this->__toString());
		return $path;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		$usesOutput = "";
		foreach ($this->uses as $use => $alias)
			$usesOutput .= "use $use".($alias ? " as $alias" : "").";\n";

		return "<?php\n\n" .
			($this->namespace ? "namespace {$this->namespace};\n\n" : "") .
			($usesOutput ? $usesOutput . "\n\n" : "") .
			"/**\n *\n" .
			" * @copyright Copyright (c) " . date('Y') . " {$this->author['name']}\n" .
			" * @author {$this->author['name']}, {$this->author['nick']} <{$this->author['email']}>\n" .
			" *\n */\n" . $this->classType;

	}


}