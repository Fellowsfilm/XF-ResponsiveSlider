<?php

namespace VersoBit\ResponsiveSlider;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
	{
		$this->createSliderTable();

		$this->createWidget('vbResponsiveSlider', 'html', [
			'positions' => ['forum_overview_top' => 1],
			'options' => [
				'advanced_mode' => true,
				'template_title' => '_widget_vbResponsiveSlider'
			]
		], '[VersoBit] Responsive Slider');
	}

	public function upgrade10000100Step1()
	{
		$this->createSliderTable();
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->dropTable('xf_vb_responsive_slider_slide');
		$this->deleteWidget('vbResponsiveSlider');
	}

	protected function createSliderTable()
	{
		$sm = $this->schemaManager();
		if ($sm->tableExists('xf_vb_responsive_slider_slide'))
		{
			return;
		}

		$sm->createTable('xf_vb_responsive_slider_slide', function (Create $table)
		{
			$table->addColumn('slide_id', 'int')->autoIncrement();
			$table->addColumn('display_order', 'int')->unsigned()->setDefault(10);
			$table->addColumn('active', 'tinyint')->unsigned()->setDefault(1);
			$table->addColumn('title', 'varchar', 150)->setDefault('');
			$table->addColumn('alt_text', 'varchar', 255)->setDefault('');
			$table->addColumn('link_url', 'varchar', 500)->setDefault('');
			$table->addColumn('open_new_window', 'tinyint')->unsigned()->setDefault(0);
			$table->addColumn('image_path', 'varchar', 255)->setDefault('');
			$table->addColumn('image_url', 'varchar', 500)->setDefault('');
			$table->addColumn('image_variants', 'mediumblob');
			$table->addColumn('width', 'int')->unsigned()->setDefault(0);
			$table->addColumn('height', 'int')->unsigned()->setDefault(0);
			$table->addColumn('file_size', 'int')->unsigned()->setDefault(0);
			$table->addColumn('upload_date', 'int')->unsigned()->setDefault(0);
			$table->addColumn('last_edit_date', 'int')->unsigned()->setDefault(0);
			$table->addPrimaryKey('slide_id');
			$table->addKey(['active', 'display_order'], 'active_display_order');
		});
	}
}
