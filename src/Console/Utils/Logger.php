<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Console\Utils;

use Symfony\Component\Console\Output\OutputInterface;

final class Logger
{

	public function __construct(private OutputInterface $output)
	{
	}

	public function logProcessingFile(string $path): void
	{
		$path = str_replace('/./', '/', $path);

		$this->output->writeln("Processing file [$path]...");
	}

}
