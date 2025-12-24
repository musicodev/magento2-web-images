<?php

namespace MusicoDev\WebImages\Plugin\Controller\Adminhtml\Wysiwyg;

use Magento\Cms\Controller\Adminhtml\Wysiwyg\Directive;
use Magento\Cms\Model\Template\Filter;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Url\DecoderInterface;
use MusicoDev\WebImages\Helper\ImageHelper;

class DirectivePlugin
{
	/**
	 * @var DecoderInterface
	 */
	private $urlDecoder;

	/**
	 * @var Filter
	 */
	private $filter;

	/**
	 * @var RawFactory
	 */
	private $resultRawFactory;

	/**
	 * @var ImageHelper
	 */
	private $imageHelper;

	/**
	 * DirectivePlugin constructor.
	 * @param DecoderInterface $urlDecoder
	 * @param Filter $filter
	 * @param RawFactory $resultRawFactory
	 * @param ImageHelper $imageHelper
	 */
	public function __construct(
		DecoderInterface $urlDecoder,
		Filter $filter,
		RawFactory $resultRawFactory,
		ImageHelper $imageHelper
	) {
		$this->urlDecoder = $urlDecoder;
		$this->filter = $filter;
		$this->resultRawFactory = $resultRawFactory;
		$this->imageHelper = $imageHelper;
	}

	/**
	 * Handle vector and web images for media storage thumbnails
	 *
	 * @param Directive $subject
	 * @param callable $proceed
	 * @return Raw
	 */
	public function aroundExecute(Directive $subject, callable $proceed)
	{
		try {
			$directive = $subject->getRequest()->getParam('___directive');
			$directive = $this->urlDecoder->decode($directive);
			$imagePath = $this->filter->filter($directive);

			if (!$this->imageHelper->isVectorImage($imagePath) && !$this->imageHelper->isWebImage($imagePath)) {
				throw new LocalizedException(__('This is not a vector or web image'));
			}

			/** @var Raw $resultRaw */
			$resultRaw = $this->resultRawFactory->create();

			// Set appropriate content type based on image type
			if ($this->imageHelper->isVectorImage($imagePath)) {
				$resultRaw->setHeader('Content-Type', 'image/svg+xml');
			} else {
				$extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
				if ($extension === 'webp') {
					$resultRaw->setHeader('Content-Type', 'image/webp');
				} elseif ($extension === 'avif') {
					$resultRaw->setHeader('Content-Type', 'image/avif');
				}
			}

			$resultRaw->setContents(file_get_contents($imagePath));

			return $resultRaw;
		} catch (\Exception $e) {
			return $proceed();
		}
	}
}
