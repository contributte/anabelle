<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Markdown\Macro\Index;

require_once __DIR__ . '/../../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;
use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;
use Contributte\Anabelle\Markdown\Macro\Index\IndexTitle;
use Contributte\Anabelle\Markdown\Macro\MacroIndex;

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
