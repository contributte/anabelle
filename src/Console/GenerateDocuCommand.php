<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ublaboo\Anabelle\Console\Utils\Exception\ParamsValidatorException;
use Ublaboo\Anabelle\Console\Utils\Logger;
use Ublaboo\Anabelle\Console\Utils\ParamsValidator;
use Ublaboo\Anabelle\Generator\DocuGenerator;
use Ublaboo\Anabelle\Generator\Exception\DocuFileGeneratorException;
use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Http\AuthCredentials;

final class GenerateDocuCommand extends Command
{

	/**
	 * @var ParamsValidator
	 */
	private $paramsValidator;

	/**
	 * @var string
	 */
	private $inputDirectory;

	/**
	 * @var string
	 */
	private $outputDirectory;

	/**
	 * @var AuthCredentials
	 */
	private $authCredentials;

	/**
	 * @var bool
	 */
	private $overwriteOutputDir;

	/**
	 * @var Logger
	 */
	private $logger;


	public function __construct(string $binDir)
	{
		parent::__construct();

		$this->paramsValidator = new ParamsValidator($binDir);
	}


	protected function configure(): void
	{
		$this->setName('anabelle')
			->setDescription('Generates a documentation from target directory')
			->setHelp($this->getDescription());

		$this->addArgument(
			'inputDirectory',
			InputArgument::REQUIRED,
			'Input documentation directory'
		);

		$this->addArgument(
			'outputDirectory',
			InputArgument::REQUIRED,
			'Output documentation directory'
		);

		$this->addOption(
			'httpAuthUser',
			'-u',
			InputOption::VALUE_OPTIONAL,
			'Should be there any HTTP authentication?'
		);

		$this->addOption(
			'httpAuthPass',
			'-p',
			InputOption::VALUE_OPTIONAL,
			'Should be there any HTTP authentication?'
		);

		$this->addOption(
			'overwriteOutputDir',
			'-o',
			InputOption::VALUE_NONE,
			'Should be the output directory overwritten with ne documentation?'
		);
	}


	public function initialize(InputInterface $input, OutputInterface $output): void
	{
		$input->validate();

		if (!is_string($input->getArgument('inputDirectory'))) {
			throw new \UnexpectedValueException;
		}

		if (!is_string($input->getArgument('outputDirectory'))) {
			throw new \UnexpectedValueException;
		}

		$this->inputDirectory = $input->getArgument('inputDirectory');
		$this->outputDirectory = $input->getArgument('outputDirectory');

		if (!is_string($input->getOption('httpAuthUser'))) {
			throw new \UnexpectedValueException;
		}

		if (!is_string($input->getOption('httpAuthPass'))) {
			throw new \UnexpectedValueException;
		}

		$this->authCredentials = new AuthCredentials(
			$input->getOption('httpAuthUser'),
			$input->getOption('httpAuthPass')
		);

		if (!is_bool($input->getOption('overwriteOutputDir'))) {
			throw new \UnexpectedValueException;
		}

		$this->overwriteOutputDir = $input->getOption('overwriteOutputDir');

		$this->logger = new Logger($output);

		/**
		 * Validate input params (documentation directory structure)
		 */
		try {
			$this->paramsValidator->validateInputParams(
				$this->inputDirectory,
				$this->outputDirectory,
				$this->authCredentials,
				$this->overwriteOutputDir
			);
		} catch (ParamsValidatorException $e) {
			$this->printError($output, $e->getMessage());
			exit(1);
		}
	}


	protected function execute(InputInterface $input, OutputInterface $output): void
	{
		$docuGenerator = new DocuGenerator(
			$this->inputDirectory,
			$this->outputDirectory,
			$this->authCredentials,
			$this->logger
		);

		try {
			$docuGenerator->run();
		} catch (DocuGeneratorException $e) {
			$this->printError($output, $e->getMessage());
			exit(1);
		} catch (DocuFileGeneratorException $e) {
			$this->printError($output, $e->getMessage());
			exit(1);
		}
	}


	private function printError(OutputInterface $output, string $message): void
	{
		$formatter = $this->getHelper('formatter');

		if ($formatter instanceof FormatterHelper) {
			$block = $formatter->formatBlock($message, 'error', true);

			$output->writeln("\n{$block}\n");
		}
	}
}
