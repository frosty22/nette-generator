<?php

namespace NetteGenerator\Command;

use Nette\Utils\Strings;
use NetteGenerator\Generator\PresenterGenerator;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class QueryCommand extends AbstractCommand {


	protected function configure()
	{
		$this->setName('generate:query');
		$this->setDescription('Generate query object');
	}


	/**
	 * @param OutputInterface $output
	 * @return PresenterGenerator
	 */
	protected function createGenerator(OutputInterface $output)
	{
		$dialog = $this->getHelperSet()->get('dialog');
		/** @var DialogHelper $dialog */

		$generator = $this->getHelper('container')->getByType('NetteGenerator\Generator\QueryGenerator');
		/** @var \NetteGenerator\Generator\QueryGenerator $generator */


		$generator->namespace = $dialog->askAndValidate($output, "<question>What's the namespace of class?</question> ",
			function($answer){
				if (!Strings::match($answer, '~^[A-Za-z\\\]+$~'))
					throw new \RuntimeException('Namespace must be alpha-numeric string delimiter backslash.');

				return ucfirst($answer);
			});


		$generator->name = $dialog->askAndValidate($output, "<question>What's the name of query?</question> ",
			function($answer){
				if (!Strings::match($answer, '~^[A-Za-z]+$~'))
					throw new \RuntimeException('Name must be alpha-numeric string.');

				return ucfirst($answer) . "Query";
			});


		do {
			$result = $dialog->askAndValidate($output,
				"<question>Do you add construct argument? Type name space and class type: </question> ",
				function($answer){
					if ($answer == "")
						return NULL;

					if (!Strings::match($answer, '~^!?([a-z]+) ?([a-z\\\]*)$~i'))
						throw new \RuntimeException("Type name space class type, example: user App\User\User");

					return $answer;
				});

			if ($result) {
				$parts = Explode(" ", $result);
				$generator->addConstructArgument($parts[0], isset($parts[1]) ? $parts[1] : NULL);
			}

		} while ($result !== NULL);


		do {
			$result = $dialog->askAndValidate($output,
				"<question>Do you add setter argument? Type name space and class type: </question> ",
				function($answer){
					if ($answer == "")
						return NULL;

					if (!Strings::match($answer, '~^!?([a-z]+) ?([a-z\\\]*)$~i'))
						throw new \RuntimeException("Type name space class type, example: user App\User\User");

					return $answer;
				});

			if ($result) {
				$parts = Explode(" ", $result);
				$generator->addProperty($parts[0], isset($parts[1]) ? $parts[1] : NULL);
			}

		} while ($result !== NULL);


		return $generator;
	}

}