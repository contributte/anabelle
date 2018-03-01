<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

final class MacroInlineVariableOutput extends AbstractMacroVariable implements IMacro
{

	protected function runVariableMacro(string & $content, int $depth): void // Intentionally &
	{
		/**
		 * Remove lines with inline variables definition and put then into DocuScope
		 */
		$content = preg_replace_callback(
			'/\{\$([a-zA-Z_0-9]+)\}/m',
			function(array $input) use ($depth): string {
				$line = $this->docuScope->getInlineVariable($input[1]);

				if ($depth <= parent::MAX_EXECUTE_DEPTH) {
					foreach ($this->getMacrosToRunOnInlineVariables() as $macro) {
						$macro->runVariableMacro($line, $depth + 1);
					}
				}

				return $line;
			},
			$content
		);
	}
}
