<?php

namespace MusicoDev\WebImages\Plugin\MediaStorage;

use Magento\Framework\Exception\NotFoundException;
use Magento\MediaStorage\Service\ImageResize;
use MusicoDev\WebImages\Helper\ImageHelper;

/**
 * Plugin to skip resize for vector images (SVG) if they're already in the queue
 */
class ImageResizePlugin
{
	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * @param ImageHelper $imageHelper
	 */
	public function __construct(
		ImageHelper $imageHelper
	) {
		$this->imageHelper = $imageHelper;
	}

	/**
	 * Skip resizing for vector images
	 *
	 * @param ImageResize $subject
	 * @param callable $proceed
	 * @param string $originalImageName
	 * @return void
	 * @throws NotFoundException
	 */
	public function aroundResizeFromImageName(
		ImageResize $subject,
		callable $proceed,
		string $originalImageName
	) {
		if ($this->imageHelper->isVectorImage($originalImageName)) {
			return;
		}

		return $proceed($originalImageName);
	}
}
