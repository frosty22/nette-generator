<?php

namespace NetteGenerator\Command;

use NetteGenerator\DirNotFoundException;
use NetteGenerator\FileAlreadyExistsException;
use NetteGenerator\Generator\AbstractGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
abstract class AbstractCommand extends \Symfony\Component\Console\Command\Command {


	/**
	 * @param OutputInterface $output
	 * @return AbstractGenerator
	 */
	abstract protected function createGenerator(OutputInterface $output);


	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {
			$path = $this->createGenerator($output)->execute();
			$output->writeln('File created: ' . $path);
			return 0;

		} catch (FileAlreadyExistsException $e) {
			$output->writeln('ERROR: ' . $e->getMessage());
		} catch (DirNotFoundException $e) {
			$output->writeln('ERROR: ' . $e->getMessage());
		}

		return 1;
	}


}