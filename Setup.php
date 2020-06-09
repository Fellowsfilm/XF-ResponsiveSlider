<?php

namespace VersoBit\ResponsiveSlider;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
    {
			$this->createWidget('vbResponsiveSlider', 'html', [
			'positions' => ['forum_overview_top' => 1],
			'options' => ['advanced_mode' => true,
					'template_title' => '_widget_vbResponsiveSlider',
				 ]
			 ], '[VersoBit] Responsive Slider');
    }
  }

  public function uninstallStep1()
	  {
			$this->deleteWidget('vbResponsiveSlider');
		}
