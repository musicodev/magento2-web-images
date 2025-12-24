<?php

namespace MusicoDev\WebImages\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class ImageHelper extends AbstractHelper
{
	const XML_PATH_WEB_IMAGE_EXTENSIONS = 'musicodev_webimages/extensions/web_image';
	const XML_PATH_VECTOR_EXTENSIONS = 'musicodev_webimages/extensions/vector';

	/**
	 * Check if the file is a vector image
	 *
	 * @param string $file
	 * @return bool
	 */
	public function isVectorImage($file)
	{
		return $this->isImageOfType($file, $this->getVectorExtensions());
	}

	/**
	 * Check if the file is a web image
	 *
	 * @param string $file
	 * @return bool
	 */
	public function isWebImage($file)
	{
		return $this->isImageOfType($file, $this->getWebImageExtensions());
	}

	/**
	 * Determines if the given file matches any of the provided extensions
	 *
	 * @param string $file
	 * @param array $allowedExtensions
	 * @return bool
	 */
	private function isImageOfType($file, array $allowedExtensions)
	{
		$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		if (empty($extension) && file_exists($file)) {
			$mimeType = mime_content_type($file);
			if ($mimeType && strpos($mimeType, 'image/') === 0) {
				$extension = str_replace('image/', '', $mimeType);
			}
		}

		return !empty($extension) && in_array($extension, $allowedExtensions, true);
	}

	/**
	 * Get vector image extensions
	 *
	 * @return array
	 */
	public function getVectorExtensions()
	{
		return $this->scopeConfig->getValue(self::XML_PATH_VECTOR_EXTENSIONS, 'store') ?: [];
	}

	/**
	 * Get web image extensions
	 *
	 * @return array
	 */
	public function getWebImageExtensions()
	{
		return $this->scopeConfig->getValue(self::XML_PATH_WEB_IMAGE_EXTENSIONS, 'store') ?: [];
	}

	/**
	 * Get dimensions of a vector image
	 *
	 * @param string $file
	 * @return array
	 */
	public function getVectorImageDimensions($file)
	{
		$width = 300;
		$height = 150;

		if (!file_exists($file)) {
			return ['width' => $width, 'height' => $height];
		}

		$useInternalErrors = libxml_use_internal_errors(true);

		try {
			$svg = simplexml_load_file($file);

			if ($svg) {
				if (isset($svg['width']) && isset($svg['height'])) {
					$width = (int) $svg['width'];
					$height = (int) $svg['height'];
				} elseif (isset($svg['viewBox'])) {
					$viewBox = preg_split('/[\s,]+/', trim((string) $svg['viewBox']));
					if (count($viewBox) == 4) {
						$width = (int) $viewBox[2];
						$height = (int) $viewBox[3];
					}
				}
			}
		} catch (\Exception $e) {
			// Ignore errors
		} finally {
			libxml_use_internal_errors($useInternalErrors);
		}

		return ['width' => $width, 'height' => $height];
	}
}
