<?php

namespace MusicoDev\WebImages\Plugin\MediaStorage;

use Magento\Catalog\Model\Product\Media\ConfigInterface as MediaConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Service\ImageResizeScheduler;
use MusicoDev\WebImages\Helper\ImageHelper;

/**
 * Plugin to prevent scheduling resize for vector images (SVG)
 */
class ImageResizeSchedulerPlugin
{
	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * @var MediaConfig
	 */
	private $mediaConfig;

	/**
	 * @param ImageHelper $imageHelper
	 * @param Filesystem $filesystem
	 * @param MediaConfig $mediaConfig
	 */
	public function __construct(
		ImageHelper $imageHelper,
		Filesystem $filesystem,
		MediaConfig $mediaConfig
	) {
		$this->imageHelper = $imageHelper;
		$this->filesystem = $filesystem;
		$this->mediaConfig = $mediaConfig;
	}

	/**
	 * Prevent scheduling resize for vector images
	 *
	 * @param ImageResizeScheduler $subject
	 * @param callable $proceed
	 * @param string $imageName
	 * @return bool
	 */
	public function aroundSchedule(
		ImageResizeScheduler $subject,
		callable $proceed,
		string $imageName
	): bool {
		if ($this->isVectorImage($imageName)) {
			return true;
		}

		return $proceed($imageName);
	}

	/**
	 * Check if image is a vector image
	 *
	 * @param string $imageName
	 * @return bool
	 */
	private function isVectorImage(string $imageName): bool
	{
		if ($this->imageHelper->isVectorImage($imageName)) {
			return true;
		}

		try {
			$mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
			$mediastoragefilename = $this->mediaConfig->getMediaPath($imageName);
			$absolutePath = $mediaDirectory->getAbsolutePath($mediastoragefilename);

			if (file_exists($absolutePath)) {
				return $this->imageHelper->isVectorImage($absolutePath);
			}
		} catch (\Exception $e) {
			// If we can't check the file, assume it's not a vector image
		}

		return false;
	}
}
