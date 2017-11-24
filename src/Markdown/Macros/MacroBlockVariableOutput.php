<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

final class MacroBlockVariableOutput extends AbstractMacroVariable implements IMacro
{

	protected function runVariableMacro(string & $content): void // Intentionally &
	{
		/**
		 * Remove lines with inline variables definition and put then into DocuScope
		 */
		$content = preg_replace_callback(
			'/\{\$\$([a-zA-Z_0-9]+)\}/m',
			function(array $input): string {
				return $this->docuScope->getBlockVariable($input[1]);
			},
			$content
		);
	}
}
