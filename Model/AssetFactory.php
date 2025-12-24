<?php

namespace MusicoDev\WebImages\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem;
use Magento\MediaGalleryApi\Api\Data\AssetInterfaceFactory;
use MusicoDev\WebImages\Helper\ImageHelper;

class AssetFactory extends AssetInterfaceFactory
{
	/**
	 * @var AssetInterfaceFactory
	 */
	private $assetFactory;

	/**
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * AssetFactory constructor.
	 * @param AssetInterfaceFactory $assetFactory
	 * @param Filesystem $filesystem
	 * @param ImageHelper $imageHelper
	 */
	public function __construct(
		AssetInterfaceFactory $assetFactory,
		Filesystem $filesystem,
		ImageHelper $imageHelper
	) {
		$this->assetFactory = $assetFactory;
		$this->filesystem = $filesystem;
		$this->imageHelper = $imageHelper;
	}

	/**
	 * Set height and width for SVG images when saving to DB
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function create(array $data = [])
	{
		if ((empty($data['width']) || empty($data['height'])) && isset($data['path']) && $this->imageHelper->isVectorImage($data['path'])) {
			$absolutePath = $this->getMediaDirectory()->getAbsolutePath($data['path']);
			$dimensions = $this->imageHelper->getVectorImageDimensions($absolutePath);
			$data['height'] = $dimensions['height'];
			$data['width'] = $dimensions['width'];
		}

		return $this->assetFactory->create($data);
	}

	/**
	 * Retrieve media directory instance with read access
	 *
	 * @return ReadInterface
	 */
	private function getMediaDirectory()
	{
		return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
	}
}
