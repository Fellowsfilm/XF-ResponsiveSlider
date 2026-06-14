<?php

namespace VersoBit\ResponsiveSlider\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Redirect;
use XF\Pub\Controller\AbstractController;

class Manage extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		if (!\XF::visitor()->canManageResponsiveSlider())
		{
			throw $this->exception(new Redirect($this->buildLink('forums'), 'temporary'));
		}
	}

	public function actionIndex()
	{
		return $this->redirectPermanently($this->buildLink('tools/responsive-slider'));
	}

	public function actionResponsiveSlider()
	{
		return $this->viewSliderManage();
	}

	public function actionResponsiveSliderEdit()
	{
		return $this->viewSliderManage($this->assertSlideExists($this->filter('slide_id', 'uint')), true);
	}

	public function actionResponsiveSliderSave()
	{
		return $this->actionSave();
	}

	public function actionResponsiveSliderDelete()
	{
		return $this->actionDelete();
	}

	protected function viewSliderManage($editSlide = null, bool $isEdit = false)
	{
		$viewParams = [
			'slides' => $this->getSliderRepo()->findSlides()->fetch(),
			'editSlide' => $editSlide ?: $this->em()->create('VersoBit\ResponsiveSlider:Slide'),
			'isEdit' => $isEdit,
			'sliderRepo' => $this->getSliderRepo()
		];

		return $this->view('VersoBit\ResponsiveSlider:Manage\Index', 'vb_responsive_slider_manage', $viewParams);
	}

	public function actionEdit()
	{
		$slideId = $this->filter('slide_id', 'uint');
		return $this->redirectPermanently($this->buildLink('tools/responsive-slider/edit', null, ['slide_id' => $slideId]));
	}

	public function actionSave()
	{
		$this->assertPostOnly();

		$input = $this->filter([
			'slide_id' => 'uint',
			'display_order' => 'uint',
			'active' => 'bool',
			'title' => 'str',
			'alt_text' => 'str',
			'link_url' => 'str',
			'open_new_window' => 'bool'
		]);

		$slide = $input['slide_id']
			? $this->assertSlideExists($input['slide_id'])
			: $this->em()->create('VersoBit\ResponsiveSlider:Slide');

		unset($input['slide_id']);
		$slide->bulkSet($input);

		$upload = $this->request->getFile('slider_image', false, false);
		if ($upload)
		{
			try
			{
				$this->getSliderRepo()->saveImageFromUpload($slide, $upload);
			}
			catch (\RuntimeException $e)
			{
				return $this->error($e->getMessage());
			}
		}

		if (!$slide->image_path && !$slide->image_url)
		{
			return $this->error('Upload an image before saving this slide.');
		}

		$slide->save();

		return $this->redirect($this->buildLink('tools/responsive-slider'), 'Slider saved.');
	}

	public function actionDelete()
	{
		$this->assertPostOnly();

		$slide = $this->assertSlideExists($this->filter('slide_id', 'uint'));
		$this->getSliderRepo()->deleteSlideImages($slide);
		$slide->delete();

		return $this->redirect($this->buildLink('tools/responsive-slider'), 'Slider deleted.');
	}

	protected function assertSlideExists(int $slideId)
	{
		return $this->assertRecordExists('VersoBit\ResponsiveSlider:Slide', $slideId);
	}

	protected function getSliderRepo(): \VersoBit\ResponsiveSlider\Repository\Slider
	{
		return $this->repository('VersoBit\ResponsiveSlider:Slider');
	}
}
