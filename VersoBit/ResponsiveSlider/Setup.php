<?php

namespace VersoBit\ResponsiveSlider;

use XF\AddOn\AbstractSetup;

class Setup extends AbstractSetup
{
	public function install()
	{
		$this->createWidget('vbResponsiveSlider', 'html', [
		'positions' => ['forum_overview_top' => 1],
		'options' => ['advanced_mode' => true,
				'template_title' => '[VersoBit] Responsive Slider',
			 ]
		 ]);
	}

	public function upgrade()
	{
		// TODO: Implement upgrade() method.
	}

	public function uninstall()
	{
		// TODO: Implement uninstall() method.
	}
}
