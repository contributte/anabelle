<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ublaboo\Anabelle\Console\Utils\Exception\ParamsValidatorException;
use Ublaboo\Anabelle\Console\Utils\ParamsValidator;

class GenerateDocuCommand extends Command
{

	/**
	 * @var string
	 */
	private $binDir;

	/**
	 * @var ParamsValidator
	 */
	private $paramsValidator;

	/**
	 * @var string
	 */
	private $docuDirectory;


	public function __construct(string $binDir)
	{
		parent::__construct();

		$this->paramsValidator = new ParamsValidator($binDir);
		$this->binDir = $binDir;
	}


	protected function configure(): void
	{
		$this->setName('anabelle')
			->setDescription('Generates a documentation from target directory')
			->setHelp($this->getDescription());

		 $this->addArgument('directory', InputArgument::REQUIRED, 'Documentation directory');
	}


	public function initialize(InputInterface $input, OutputInterface $output): void
	{
		$this->docuDirectory = $input->getArgument('directory');

		try {
			$this->paramsValidator->validateInputDirectory($this->docuDirectory);
		} catch (ParamsValidatorException $e) {
			$this->printError($output, $e->getMessage());
			exit(1);
		}
	}


	protected function execute(InputInterface $input, OutputInterface $output): void
	{
		// Code here
	}


	private function printError(OutputInterface $output, string $message): void
	{
		$block = $this->getHelper('formatter')->formatBlock($message, 'error', true);

		$output->writeln("\n{$block}\n");
	}
}
