<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros\Utils;

final class FileHash
{

	public static function md5File(string $destination): string
	{
		return md5_file($destination);
	}
}
