<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Tests\Markdown\Macro\Index;

require_once __DIR__ . '/../../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;
use Ublaboo\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;
use Ublaboo\Anabelle\Markdown\Macro\Index\IndexSubTitle;
use Ublaboo\Anabelle\Markdown\Macro\MacroIndex;

/**
 * @testCase
 */
class IndexSubTitleTest extends TestCase
{

	public function testBasicFunctionality(): void
	{
		$section = IndexSubTitle::createFromLine('## Title');

		Assert::same('## Title', $section->getContentString());

		Assert::exception(
			function(): void {
				IndexSubTitle::createFromLine('##@ Title');
			},
			PartDidNotMatchException::class
		);

		Assert::exception(
			function(): void {
				IndexSubTitle::createFromLine('#@Title');
			},
			PartDidNotMatchException::class
		);

		Assert::exception(
			function(): void {
				IndexSubTitle::createFromLine('#@@ Title');
			},
			PartDidNotMatchException::class
		);
	}
}

(new IndexSubTitleTest())->run();
