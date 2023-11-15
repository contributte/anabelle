<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Cases\Markdown\Macro\Index;

use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;
use Contributte\Anabelle\Markdown\Macro\Index\IndexSubTitle;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../../bootstrap.php';

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
