<?php

namespace VersoBit\ResponsiveSlider\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $slide_id
 * @property int $display_order
 * @property bool $active
 * @property string $title
 * @property string $alt_text
 * @property string $link_url
 * @property bool $open_new_window
 * @property string $image_path
 * @property string $image_url
 * @property array $image_variants
 * @property int $width
 * @property int $height
 * @property int $file_size
 * @property int $upload_date
 * @property int $last_edit_date
 */
class Slide extends Entity
{
	protected function _preSave()
	{
		if (!$this->alt_text && $this->title)
		{
			$this->alt_text = $this->title;
		}

		$this->last_edit_date = \XF::$time;
	}

	public static function getStructure(Structure $structure)
	{
		$structure->shortName = 'VersoBit\ResponsiveSlider:Slide';
		$structure->table = 'xf_vb_responsive_slider_slide';
		$structure->primaryKey = 'slide_id';
		$structure->columns = [
			'slide_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'display_order' => ['type' => self::UINT, 'default' => 10],
			'active' => ['type' => self::BOOL, 'default' => true],
			'title' => ['type' => self::STR, 'maxLength' => 150, 'default' => ''],
			'alt_text' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
			'link_url' => ['type' => self::STR, 'maxLength' => 500, 'default' => ''],
			'open_new_window' => ['type' => self::BOOL, 'default' => false],
			'image_path' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
			'image_url' => ['type' => self::STR, 'maxLength' => 500, 'default' => ''],
			'image_variants' => ['type' => self::JSON_ARRAY, 'default' => []],
			'width' => ['type' => self::UINT, 'default' => 0],
			'height' => ['type' => self::UINT, 'default' => 0],
			'file_size' => ['type' => self::UINT, 'default' => 0],
			'upload_date' => ['type' => self::UINT, 'default' => 0],
			'last_edit_date' => ['type' => self::UINT, 'default' => 0],
		];

		return $structure;
	}
}
