<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;

final class DocuGenerator
{

	/**
	 * @var string
	 */
	private $inputDirectory;

	/**
	 * @var string
	 */
	private $outputDirectory;


	public function __construct(string $inputDirectory, string $outputDirectory)
	{
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function run(): void
	{
		// Code here
	}
}
