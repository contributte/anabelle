<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Console\Utils;

use Contributte\Anabelle\Console\Utils\Exception\ParamsValidatorException;
use Contributte\Anabelle\Http\AuthCredentials;

final class ParamsValidator
{

	public function __construct(private string $binDir) {}


	/**
	 * Validate directory structure (== check for <directory>/index.md)
	 *
	 * @throws ParamsValidatorException
	 */
	public function validateInputParams(
		string $inputDirectory,
		string $outputDirectory,
		?string $specialCssFile,
		AuthCredentials $authCredentials,
		bool $overwriteOutputDir
	): void
	{
		$this->validateInputDirectory($inputDirectory, $outputDirectory, $overwriteOutputDir);
		$this->validateIndexFile($inputDirectory);
		$this->validateAuthCredentials($authCredentials);
		$this->validateSpecialCssFile($specialCssFile);
	}


	/**
	 * @throws ParamsValidatorException
	 */
	private function validateSpecialCssFile(?string $specialCssFile): void
	{
		if ($specialCssFile === null) {
			return;
		}

		if (!file_exists($specialCssFile) || !is_file($specialCssFile)) {
			throw new ParamsValidatorException('CSS file does not exist');
		}
	}


	/**
	 * @throws ParamsValidatorException
	 */
	private function validateInputDirectory(
		string $inputDirectory,
		string $outputDirectory,
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

		if (file_exists($outputDirectory) && !$overwriteOutputDir) {
			throw new ParamsValidatorException(
				"Output directory path already exists."
				. " Delete it or use option [-o] as for \"overwrite\" output directory"
			);
		}
	}


	/**
	 * @throws ParamsValidatorException
	 */
	private function validateIndexFile(string $inputDirectory): void
	{
		/**
		 * Validate existence of <directory>/index.md
		 */
		if (!file_exists($inputDirectory . '/index.md')) {
			throw new ParamsValidatorException("Missing file {$inputDirectory}/index.md");
		}
	}


	/**
	 * @throws ParamsValidatorException
	 */
	private function validateAuthCredentials(AuthCredentials $authCredentials): void
	{
		/**
		 * Validate HTTP AUTH
		 */
		if ($authCredentials->getPass() === null && $authCredentials->getUser() !== null) {
			throw new ParamsValidatorException("Please set --httpAuthPass [-p]");
		}

		if ($authCredentials->getPass() !== null && $authCredentials->getUser() === null) {
			throw new ParamsValidatorException("Please set --httpAuthUser [-u]");
		}
	}
}
