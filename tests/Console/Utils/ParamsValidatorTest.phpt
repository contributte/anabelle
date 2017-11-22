<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Tests\Console\Utils;

require_once __DIR__ . '/../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;
use Ublaboo\Anabelle\Console\Utils\Exception\ParamsValidatorException;
use Ublaboo\Anabelle\Console\Utils\ParamsValidator;
use Ublaboo\Anabelle\Http\AuthCredentials;

class ParamsValidatorTest extends TestCase
{

	public function testDirectories(): void
	{
		$auth = new AuthCredentials(null, null);
		$paramsValidator = new ParamsValidator('');

		Assert::exception(function() use ($paramsValidator, $auth): void {
			$paramsValidator->validateInputParams(
				'nonExistingDir',
				'',
				$auth,
				false
			);
		}, ParamsValidatorException::class, 'Input documentation directory does not exist');

		Assert::exception(function() use ($paramsValidator, $auth): void {
			$paramsValidator->validateInputParams(
				__FILE__,
				'',
				$auth,
				false
			);
		}, ParamsValidatorException::class, 'Given path is not a directory');

		Assert::exception(function() use ($paramsValidator, $auth): void {
			$paramsValidator->validateInputParams(
				__DIR__,
				'',
				$auth,
				false
			);
		}, ParamsValidatorException::class, 'Missing file ' . __DIR__ . '/index.md');

		$m = "Output directory path already exists."
				. " Delete it or use option [-o] as for \"overwrite\" output directory";

		Assert::exception(function() use ($paramsValidator, $auth, $m): void {
			$paramsValidator->validateInputParams(
				TEST_DOCU_DIR,
				TEST_DOCU_DIR,
				$auth,
				false
			);
		}, ParamsValidatorException::class, $m);
	}
}

(new ParamsValidatorTest())->run();
