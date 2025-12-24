<?php

namespace MusicoDev\WebImages\Plugin\Controller\Adminhtml\Wysiwyg\Images;

use Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\Thumbnail;
use Magento\Cms\Helper\Wysiwyg\Images;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Exception\LocalizedException;
use MusicoDev\WebImages\Helper\ImageHelper;

class ThumbnailPlugin
{
	/**
	 * @var Images
	 */
	private $wysiwygImages;

	/**
	 * @var RawFactory
	 */
	private $resultRawFactory;

	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * ThumbnailPlugin constructor.
	 * @param Images $wysiwygImages
	 * @param RawFactory $resultRawFactory
	 * @param ImageHelper $imageHelper
	 */
	public function __construct(
		Images $wysiwygImages,
		RawFactory $resultRawFactory,
		ImageHelper $imageHelper
	) {
		$this->wysiwygImages = $wysiwygImages;
		$this->resultRawFactory = $resultRawFactory;
		$this->imageHelper = $imageHelper;
	}

	/**
	 * Handle vector and web images for media storage thumbnails
	 *
	 * @param Thumbnail $subject
	 * @param callable $proceed
	 * @return Raw
	 */
	public function aroundExecute(Thumbnail $subject, callable $proceed)
	{
		try {
			$file = $subject->getRequest()->getParam('file');
			$file = $this->wysiwygImages->idDecode($file);
			$thumb = $subject->getStorage()->resizeOnTheFly($file);

			if (!$this->imageHelper->isVectorImage($thumb) && !$this->imageHelper->isWebImage($thumb)) {
				throw new LocalizedException(__('This is not a vector or web image'));
			}

			/** @var Raw $resultRaw */
			$resultRaw = $this->resultRawFactory->create();

			// Set appropriate content type based on image type
			if ($this->imageHelper->isVectorImage($thumb)) {
				$resultRaw->setHeader('Content-Type', 'image/svg+xml');
			} else {
				$extension = strtolower(pathinfo($thumb, PATHINFO_EXTENSION));
				if ($extension === 'webp') {
					$resultRaw->setHeader('Content-Type', 'image/webp');
				} elseif ($extension === 'avif') {
					$resultRaw->setHeader('Content-Type', 'image/avif');
				}
			}

			$resultRaw->setContents(file_get_contents($thumb));

			return $resultRaw;
		} catch (\Exception $e) {
			return $proceed();
		}
	}
}
