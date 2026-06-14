<?php

namespace VersoBit\ResponsiveSlider\Template\Callback;

use XF\Template\Templater;

class Slider
{
	public static function render($contents, array $params, Templater $templater): string
	{
		$repo = \XF::repository('VersoBit\ResponsiveSlider:Slider');
		$slides = $repo->getActiveSlides();

		if (!count($slides))
		{
			return '';
		}

		return $templater->renderTemplate('public:vbResponsiveSlider_render', [
			'slides' => $slides,
			'slideCount' => count($slides),
			'sliderRepo' => $repo
		]);
	}
}
