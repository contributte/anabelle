<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Cases\Markdown\Macro\Index;

use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;
use Contributte\Anabelle\Markdown\Macro\Index\IndexSection;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../../bootstrap.php';

/**
 * @testCase
 */
class IndexSectionTest extends TestCase
{

	public function testBasicFunctionality(): void
	{
		$section = IndexSection::createFromLine('@@ user.login:methods/user.login.md');

		Assert::same('@@ user.login:methods/user.login.md', $section->getContentString());

		$section = IndexSection::createFromLine('@ user.login:methods/user.login.md');

		Assert::same('@ user.login:methods/user.login.md', $section->getContentString());

		Assert::exception(
			function(): void {
				IndexSection::createFromLine('## Title');
			},
			PartDidNotMatchException::class
		);

		Assert::exception(
			function(): void {
				IndexSection::createFromLine('@ user.login.methods/user.login.md');
			},
			PartDidNotMatchException::class
		);

		Assert::exception(
			function(): void {
				IndexSection::createFromLine('@ user.login:methods.user.login.html');
			},
			PartDidNotMatchException::class
		);
	}
}

(new IndexSectionTest())->run();
