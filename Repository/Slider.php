<?php

namespace VersoBit\ResponsiveSlider\Repository;

use VersoBit\ResponsiveSlider\Entity\Slide;
use XF\Http\Upload;
use XF\Mvc\Entity\Repository;
use XF\Util\File;

class Slider extends Repository
{
	const CDN_PATH = 'pub-data/slider';
	const VARIANT_WIDTHS = [640, 960, 1280, 1920];

	public function findSlides()
	{
		return $this->finder('VersoBit\ResponsiveSlider:Slide')
			->order(['display_order', 'slide_id']);
	}

	public function getActiveSlides()
	{
		return $this->findSlides()
			->where('active', 1)
			->fetch();
	}

	public function getSlideImageUrl(Slide $slide, ?array $variant = null): string
	{
		$path = $variant['path'] ?? $slide->image_path;
		if ($path)
		{
			return \XF::app()->applyExternalDataUrl($path);
		}

		return $slide->image_url;
	}

	public function getSlideSrcset(Slide $slide): string
	{
		$srcset = [];
		foreach ($slide->image_variants AS $variant)
		{
			if (empty($variant['path']) || empty($variant['width']))
			{
				continue;
			}

			$srcset[] = $this->getSlideImageUrl($slide, $variant) . ' ' . intval($variant['width']) . 'w';
		}

		return implode(', ', $srcset);
	}

	public function getSlideSizes(): string
	{
		return '(max-width: 700px) 100vw, (max-width: 1200px) 75vw, 900px';
	}

	public function saveImageFromUpload(Slide $slide, Upload $upload): void
	{
		$errors = [];
		if (!$upload->isValid($errors))
		{
			throw new \RuntimeException(implode(' ', $errors) ?: 'Uploaded image is not valid.');
		}

		$this->saveImageFromFile($slide, $upload->getTempFile());
	}

	public function saveImageFromFile(Slide $slide, string $sourceFile): void
	{
		$image = \XF::app()->imageManager()->imageFromFile($sourceFile);
		if (!$image)
		{
			throw new \RuntimeException('Uploaded file is not a valid image.');
		}

		$this->deleteSlideImages($slide);

		$baseName = $this->getRandomBaseName();
		$variants = [];
		$largest = null;
		$seenWidths = [];

		foreach (self::VARIANT_WIDTHS AS $width)
		{
			$tempFile = $this->makeJpegVariant($sourceFile, $width, $variantWidth, $variantHeight);
			if (!$tempFile)
			{
				continue;
			}

			if (isset($seenWidths[$variantWidth]))
			{
				@unlink($tempFile);
				continue;
			}
			$seenWidths[$variantWidth] = true;

			$targetPath = self::CDN_PATH . '/' . $baseName . '-' . $variantWidth . '.jpg';
			File::copyFileToAbstractedPath($tempFile, 'data://' . $targetPath);

			$variant = [
				'path' => $targetPath,
				'width' => $variantWidth,
				'height' => $variantHeight,
				'size' => filesize($tempFile) ?: 0,
				'mime' => 'image/jpeg'
			];

			$variants[] = $variant;
			$largest = $variant;

			@unlink($tempFile);
		}

		if (!$largest)
		{
			$targetPath = self::CDN_PATH . '/' . $baseName . '.jpg';
			File::copyFileToAbstractedPath($sourceFile, 'data://' . $targetPath);
			$largest = [
				'path' => $targetPath,
				'width' => $image->getWidth(),
				'height' => $image->getHeight(),
				'size' => filesize($sourceFile) ?: 0,
				'mime' => 'image/jpeg'
			];
			$variants[] = $largest;
		}

		$slide->image_path = $largest['path'];
		$slide->image_url = '';
		$slide->image_variants = $variants;
		$slide->width = $largest['width'];
		$slide->height = $largest['height'];
		$slide->file_size = $largest['size'];
		$slide->upload_date = \XF::$time;
	}

	public function deleteSlideImages(Slide $slide): void
	{
		$paths = [];
		if ($slide->image_path)
		{
			$paths[] = $slide->image_path;
		}

		foreach ($slide->image_variants AS $variant)
		{
			if (!empty($variant['path']))
			{
				$paths[] = $variant['path'];
			}
		}

		foreach (array_unique($paths) AS $path)
		{
			try
			{
				\XF::fs()->delete('data://' . $path);
			}
			catch (\Throwable $e) {}
		}
	}

	protected function makeJpegVariant(string $sourceFile, int $targetWidth, ?int &$variantWidth = null, ?int &$variantHeight = null): ?string
	{
		$image = \XF::app()->imageManager()->imageFromFile($sourceFile);
		if (!$image)
		{
			return null;
		}

		$image->resizeWidth($targetWidth, false);
		$variantWidth = $image->getWidth();
		$variantHeight = $image->getHeight();

		$tempFile = File::getTempFile();
		if (!$image->save($tempFile, IMAGETYPE_JPEG, 82))
		{
			@unlink($tempFile);
			return null;
		}

		return $tempFile;
	}

	protected function getRandomBaseName(): string
	{
		return bin2hex(random_bytes(16));
	}
}
