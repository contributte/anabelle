<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Generator\Exception;

use RuntimeException;
use Throwable;

class DocuFileGeneratorException extends RuntimeException
{

	public function __construct(string $fileName, string $message, ?int $code, Throwable $previous)
	{
		parent::__construct(
			$message . ' @ ' . str_replace('/./', '/', $fileName),
			$code ?? 0,
			$previous
		);
	}

}
