<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDocuCommand extends Command
{

	protected function configure(): void
	{
		$this->setName('anabelle')
			->setDescription('Generates a documentation from target directory')
			->setHelp($this->getDescription());

		 $this->addArgument('directory', InputArgument::REQUIRED, 'Documentation directory');
	}


	public function initialize(InputInterface $input, OutputInterface $output): void
	{
		// Code here
	}


	protected function execute(InputInterface $input, OutputInterface $output): void
	{
		// Code here
	}
}
