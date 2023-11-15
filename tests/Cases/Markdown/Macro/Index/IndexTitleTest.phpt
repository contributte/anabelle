<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Cases\Markdown\Macro\Index;

use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;
use Contributte\Anabelle\Markdown\Macro\Index\IndexTitle;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../../bootstrap.php';

/**
 * @testCase
 */
class IndexTitleTest extends TestCase
{

	public function testBasicFunctionality(): void
	{
		$section = IndexTitle::createFromLine('# Title');

		Assert::same('# Title', $section->getContentString());

		Assert::exception(
			function(): void {
				IndexTitle::createFromLine('## Title');
			},
			PartDidNotMatchException::class
		);

		Assert::exception(
			function(): void {
				IndexTitle::createFromLine('#Title');
			},
			PartDidNotMatchException::class
		);
	}
}

(new IndexTitleTest())->run();
