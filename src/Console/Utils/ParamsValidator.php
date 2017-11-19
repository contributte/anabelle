<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Console\Utils;

use Ublaboo\Anabelle\Console\Utils\Exception\ParamsValidatorException;

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
	public function validateInputDirectory(string $docuDirectory): void
	{
		/**
		 * Absolute path?
		 */
		if (!file_exists($docuDirectory)) {
			/**
			 * Relative to the bin file?
			 */
			$docuDirectory = $this->binDir . '/' . $docuDirectory;

			if (!file_exists($docuDirectory)) {
				throw new ParamsValidatorException('Documentation directory does not exist');
			}
		}

		/**
		 * Validate $this->docuDirectory is a directory
		 */
		if (!is_dir($docuDirectory)) {
			throw new ParamsValidatorException('Given path is not a directory');
		}

		/**
		 * Validate existence of <directory>/index.md
		 */
		if (!file_exists($docuDirectory . '/index.md')) {
			throw new ParamsValidatorException("Missing file {$docuDirectory}/index.md");
		}
	}
}
