<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Console;

use Contributte\Anabelle\Console\Utils\Exception\ParamsValidatorException;
use Contributte\Anabelle\Console\Utils\Logger;
use Contributte\Anabelle\Console\Utils\ParamsValidator;
use Contributte\Anabelle\Generator\DocuGenerator;
use Contributte\Anabelle\Generator\Exception\DocuFileGeneratorException;
use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Http\AuthCredentials;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use UnexpectedValueException;

final class GenerateDocuCommand extends Command
{

	/** @var ParamsValidator */
	private $paramsValidator;

	/** @var string */
	private $inputDirectory;

	/** @var string|null */
	private $addCss;

	/** @var string */
	private $outputDirectory;

	/** @var AuthCredentials */
	private $authCredentials;

	/** @var bool */
	private $overwriteOutputDir;

	/** @var Logger */
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

		$this->addOption(
			'addCss',
			null,
			InputOption::VALUE_REQUIRED,
			'Include special css file?'
		);
	}


	public function initialize(InputInterface $input, OutputInterface $output): void
	{
		$input->validate();

		$inputDirectory = $input->getArgument('inputDirectory');
		$outputDirectory = $input->getArgument('outputDirectory');
		$httpAuthUser = $input->getOption('httpAuthUser');
		$httpAuthPass = $input->getOption('httpAuthPass');
		$overwriteOutputDir = $input->getOption('overwriteOutputDir');
		$addCss = $input->getOption('addCss');

		if (!is_string($inputDirectory)) {
			throw new UnexpectedValueException();
		}

		if (!is_string($outputDirectory)) {
			throw new UnexpectedValueException();
		}

		if (!is_string($httpAuthUser) && $httpAuthUser !== null) {
			throw new UnexpectedValueException();
		}

		if (!is_string($httpAuthPass) && $httpAuthPass !== null) {
			throw new UnexpectedValueException();
		}

		if (!is_bool($overwriteOutputDir)) {
			throw new UnexpectedValueException();
		}

		if (!is_string($addCss) && $addCss !== null) {
			throw new UnexpectedValueException();
		}

		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;
		$this->addCss = $addCss;

		$this->authCredentials = new AuthCredentials(
			$httpAuthUser,
			$httpAuthPass
		);

		$this->overwriteOutputDir = $overwriteOutputDir;

		$this->logger = new Logger($output);

		/**
		 * Validate input params (documentation directory structure)
		 */
		try {
			$this->paramsValidator->validateInputParams(
				$this->inputDirectory,
				$this->outputDirectory,
				$this->addCss,
				$this->authCredentials,
				$this->overwriteOutputDir
			);
		} catch (ParamsValidatorException $e) {
			$this->printError($output, $e->getMessage());
			exit(1);
		}
	}


	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$docuGenerator = new DocuGenerator(
			$this->inputDirectory,
			$this->outputDirectory,
			$this->addCss,
			$this->authCredentials,
			$this->logger
		);

		try {
			$docuGenerator->run();
		} catch (DocuGeneratorException $e) {
			$this->printError($output, $e->getMessage());
			return 1;
		} catch (DocuFileGeneratorException $e) {
			$this->printError($output, $e->getMessage());
			return 1;
		}

		return 0;
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
