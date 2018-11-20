<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros\Utils;

final class FileHash
{

	public static function md5File(string $destination): string
	{
		$hash = md5_file($destination);

		if ($hash === false) {
			throw new \UnexpectedValueException(sprintf('Could not md5_file(%s)', $destination));
		}

		return $hash;
	}
}
