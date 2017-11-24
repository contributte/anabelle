<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

final class MacroBlockVariable extends AbstractMacroVariable implements IMacro
{

	protected function runVariableMacro(string & $content): void // Intentionally &
	{
		/**
		 * Remove lines with inline variables definition and put then into DocuScope
		 */
		$content = preg_replace_callback(
			'/^\$\$([a-zA-Z_0-9]+)\n(.+)\$\$$/ms',
			function(array $input): string {
				$this->docuScope->addBlockVariable($input[1], $input[2]);

				return '';
			},
			$content
		);
	}
}
