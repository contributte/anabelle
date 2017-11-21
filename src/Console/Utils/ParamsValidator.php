<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Console\Utils;

use Ublaboo\Anabelle\Console\Utils\Exception\ParamsValidatorException;
use Ublaboo\Anabelle\Http\AuthCredentials;

final class ParamsValidator
{

	/**
	 * @var string
	 */
	private $binDir;


	public function __construct(string $binDir)
	{
		$this->binDir = $binDir;
	}


	/**
	 * Validate directory structure (== check for <directory>/index.md)
	 *
	 * @throws ParamsValidatorException
	 */
	public function validateInputParams(
		string $inputDirectory,
		string $outputDirectory,
		AuthCredentials $authCredentials,
		bool $overwriteOutputDir
	): void
	{
		/**
		 * Absolute path?
		 */
		if (!file_exists($inputDirectory)) {
			/**
			 * Relative to the bin file?
			 */
			$inputDirectory = $this->binDir . '/' . $inputDirectory;

			if (!file_exists($inputDirectory)) {
				throw new ParamsValidatorException('Input documentation directory does not exist');
			}
		}

		/**
		 * Validate $this->inputDirectory is a directory
		 */
		if (!is_dir($inputDirectory)) {
			throw new ParamsValidatorException('Given path is not a directory');
		}

		/**
		 * Validate existence of <directory>/index.md
		 */
		if (!file_exists($inputDirectory . '/index.md')) {
			throw new ParamsValidatorException("Missing file {$inputDirectory}/index.md");
		}

		if (file_exists($outputDirectory) && !$overwriteOutputDir) {
			throw new ParamsValidatorException(
				"Output directory path already exists."
				. " Delete it or use option [-o] as for \"overwrite\" output directory"
			);
		}

		/**
		 * Validate HTTP AUTH
		 */
		if (empty($authCredentials->getPass()) && !empty($authCredentials->getUser())) {
			throw new ParamsValidatorException("Please set --httpAuthPass [-p]");
		} elseif (!empty($authCredentials->getPass()) && empty($authCredentials->getUser())) {
			throw new ParamsValidatorException("Please set --httpAuthUser [-u]");
		}
	}
}
