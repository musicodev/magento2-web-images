<?php

namespace MusicoDev\WebImages\Plugin\Wysiwyg\Images;

use Magento\Cms\Model\Wysiwyg\Images\Storage;
use MusicoDev\WebImages\Helper\ImageHelper;

class StoragePlugin
{
	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * StoragePlugin constructor.
	 * @param ImageHelper $imageHelper
	 */
	public function __construct(
		ImageHelper $imageHelper
	) {
		$this->imageHelper = $imageHelper;
	}

	/**
	 * Skip resizing vector and web images (webp, avif)
	 *
	 * @param Storage $storage
	 * @param callable $proceed
	 * @param $source
	 * @param bool $keepRatio
	 * @return mixed
	 */
	public function aroundResizeFile(Storage $storage, callable $proceed, $source, $keepRatio = true)
	{
		if ($this->imageHelper->isVectorImage($source) || $this->imageHelper->isWebImage($source)) {
			return $source;
		}

		return $proceed($source, $keepRatio);
	}

	/**
	 * Return original file path as thumbnail for vector and web images
	 *
	 * @param Storage $storage
	 * @param callable $proceed
	 * @param $filePath
	 * @param false $checkFile
	 */
	public function aroundGetThumbnailPath(Storage $storage, callable $proceed, $filePath, $checkFile = false)
	{
		if ($this->imageHelper->isVectorImage($filePath) || $this->imageHelper->isWebImage($filePath)) {
			return $filePath;
		}

		return $proceed($filePath, $checkFile);
	}
}
