<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Console\Utils;

use Symfony\Component\Console\Output\OutputInterface;

final class Logger
{

	/**
	 * @var OutputInterface
	 */
	private $output;


	public function __construct(OutputInterface $output)
	{
		$this->output = $output;
	}


	public function logProcessingFile(string $path): void
	{
		$path = str_replace('/./', '/', $path);

		$this->output->writeln("Processing file [$path]...");
	}
}
