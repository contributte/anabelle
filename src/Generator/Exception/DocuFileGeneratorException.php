<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator\Exception;

class DocuFileGeneratorException extends \RuntimeException
{

	public function __construct(string $fileName, string $message, ?int $code, \Throwable $previous)
	{
		parent::__construct(
			$message . ' @ ' . str_replace('/./', '/', $fileName),
			$code ?? 0,
			$previous
		);
	}
}
