<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Parsedown;

use Nette\Utils\Strings;
use Parsedown;

final class CustomParsedown extends Parsedown
{

	public function __construct()
	{
		$this->InlineTypes['@'][] = 'Section';

		$this->inlineMarkerList .= '@';
	}


	/**
	 * Either "section" or "home" element
	 *
	 * @param array<mixed> $excerpt
	 * @return array<mixed>|null
	 */
	protected function inlineSection(array $excerpt): ?array
	{
		if (preg_match('/^(@@?) ?(.+[^:]):(.+\.(php|html))/', $excerpt['text'], $matches) === 1) {
			$class = strlen($matches[1]) === 1
				? 'section-site'
				: 'section-method';
			$element = strlen($matches[1]) === 1
				? 'a'
				: 'button';

			return [
				'extent' => strlen($matches[0]),
				'element' => [
					'name' => $element,
					'text' => $matches[2],
					'attributes' => [
						'data-section-src' => $matches[3],
						'class' => sprintf('%s search-item', $class),
						'data-target' => Strings::webalize($matches[2]),
					],
				],
			];
		}

		return null;
	}

}
