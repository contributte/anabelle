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


	protected function inlineSection($excerpt): ?array
	{
		if (preg_match('/^@ ?(.+[^:]):(.+\.php)/', $excerpt['text'], $matches)) {
			return [
				'extent' => strlen($matches[0]), 
				'element' => [
					'name' => 'section',
					'text' => $matches[1],
					'attributes' => [
						'data-section-href' => $matches[2],
					]
				]
			];
		}

		return null;
	}
}
