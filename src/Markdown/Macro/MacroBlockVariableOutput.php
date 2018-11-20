<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macro;

final class MacroBlockVariableOutput extends AbstractMacroVariable implements IMacro
{

	protected function runVariableMacro(string & $content, int $depth): void // Intentionally &
	{
		/**
		 * Remove lines with inline variables definition and put then into DocuScope
		 */
		$content = preg_replace_callback(
			'/(^.*)?\{\$\$([a-zA-Z_0-9]+)\}/m',
			function(array $input) use ($depth): string {
				$whitespacePrefix = '';

				/**
				 * Prefix the whole block with current identation
				 */
				foreach (str_split($input[1]) as $character) {
					if ($character === "\t") {
						$whitespacePrefix .= $character;

						continue;
					}

					break;
				}

				$block = $this->docuScope->getBlockVariable($input[2]);

				if ($depth <= parent::MAX_EXECUTE_DEPTH) {
					foreach ($this->getMacrosToRunOnBlockVariables() as $macro) {
						$macro->runVariableMacro($block, $depth + 1);
					}
				}

				return $input[1] . preg_replace('/\n/m', "\n{$whitespacePrefix}", $block);
			},
			$content
		);
	}
}
