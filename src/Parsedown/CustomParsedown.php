<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Parsedown;

final class CustomParsedown extends \Parsedown
{

	function __construct()
	{
		$this->InlineTypes['@'][]= 'Section';

		$this->inlineMarkerList .= '@';
	}


	/**
	 * Either "section" or "home" element
	 */
	protected function inlineSection($excerpt): ?array
	{
		if (preg_match('/^(@@?) ?(.+[^:]):(.+\.php)/', $excerpt['text'], $matches)) {
			$class = strlen($matches[1]) == 1 ? 'section-method' : 'section-site';

			return [
				'extent' => strlen($matches[0]), 
				'element' => [
					'name' => 'section',
					'text' => $matches[2],
					'attributes' => [
						'data-section-href' => $matches[3],
						'class' => $class
					]
				]
			];
		}

		return null;
	}
}
