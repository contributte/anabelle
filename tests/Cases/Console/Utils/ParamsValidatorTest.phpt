<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Cases\Console\Utils;

use Contributte\Anabelle\Console\Utils\Exception\ParamsValidatorException;
use Contributte\Anabelle\Console\Utils\ParamsValidator;
use Contributte\Anabelle\Http\AuthCredentials;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

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
				null,
				$auth,
				false
			);
		}, ParamsValidatorException::class, 'Input documentation directory does not exist');

		Assert::exception(function() use ($paramsValidator, $auth): void {
			$paramsValidator->validateInputParams(
				__FILE__,
				'',
				null,
				$auth,
				false
			);
		}, ParamsValidatorException::class, 'Given path is not a directory');

		Assert::exception(function() use ($paramsValidator, $auth): void {
			$paramsValidator->validateInputParams(
				__DIR__,
				'',
				null,
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
				null,
				$auth,
				false
			);
		}, ParamsValidatorException::class, $m);

		Assert::exception(function() use ($paramsValidator, $auth, $m): void {
			$paramsValidator->validateInputParams(
				TEST_DOCU_DIR,
				TEST_DOCU_DIR,
				TEST_DOCU_DIR . '/styles.css-css-css-css',
				$auth,
				true
			);
		}, ParamsValidatorException::class, 'CSS file does not exist');
	}
}

(new ParamsValidatorTest())->run();
