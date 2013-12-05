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
class PresenterCommand extends AbstractCommand {


	protected function configure()
	{
		$this->setName('generate:presenter');
		$this->setDescription('Generate presenter');
	}


	/**
	 * @param OutputInterface $output
	 * @return PresenterGenerator
	 */
	protected function createGenerator(OutputInterface $output)
	{
		$dialog = $this->getHelperSet()->get('dialog');
		/** @var DialogHelper $dialog */

		$generator = $this->getHelper('container')->getByType('NetteGenerator\Generator\PresenterGenerator');
		/** @var \NetteGenerator\Generator\PresenterGenerator $generator */


		$generator->name = $dialog->askAndValidate($output, "<question>What's the name of presenter?</question> ",
			function($answer){
				if (!Strings::match($answer, '~^[A-Za-z]+$~'))
					throw new \RuntimeException('Name must be alpha-numeric string.');

				return ucfirst($answer) . "Presenter";
		});

		$generator->module = $dialog->askAndValidate($output, "<question>What's the module name?</question> ",
			function($answer){
				if (!$answer)
					return NULL;

				if (!Strings::match($answer, '~^[A-Za-z\\\]+$~'))
					throw new \RuntimeException("Name must be valid module's name.");

				return ucfirst($answer) . "Module";
		}, FALSE, NULL, array('Front', 'Admin', 'front', 'admin'));

		$generator->secured = $dialog->askConfirmation($output, "<question>Is secured?</question> ", FALSE);


		do {
			$result = $dialog->askAndValidate($output,
				"<question>Do you add autowired property? Type name space and class type: </question> ",
				function($answer){
					if ($answer == "")
						return NULL;

					if (!Strings::match($answer, '~^!?([a-z]+) ([a-z\\\]+)$~i'))
						throw new \RuntimeException("Type name space class type, example: user Nette\Security\User");

					return $answer;
				});

			if ($result) {
				list($name, $type) = Explode(" ", $result);
				$generator->addProperty($name, $type);
			}

		} while ($result !== NULL);


		do {
			$result = $dialog->askAndValidate($output,
				"<question>Do you add action? </question> ",
				function($answer){
					if ($answer == "")
						return NULL;

					if (!Strings::match($answer, '~^[A-Za-z]+$~'))
						throw new \RuntimeException('Name must be alpha-numeric string.');

					return 'action' . ucfirst($answer);
				});

			if ($result) {
				$generator->addMethod($result);
			}

		} while ($result !== NULL);


		return $generator;
	}

}