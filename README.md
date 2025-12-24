# Upload SVG, AVIF and WebP images in Magento 2

## 📝 Overview

Magento 2 extension that modernizes your store's image capabilities by adding support for next-generation image formats. This module allows administrators to upload and manage **SVG**, **WebP**, and **AVIF** images directly in the product media gallery, formats that are not natively supported by standard Magento installations.

By using these modern formats, you can significantly improve your website's performance and user experience through faster load times and higher visual quality at smaller file sizes.

This project is a fork of [MagestyApps_WebImages](https://github.com/MagestyApps/module-web-images).

The following improvements/changes have been made:

-   Support for PHP 8.4
-   Updated GD Adapter with the latest changes
-   Support for AVIF format
-   Several bugs fixed

## ✅ Features

-   **Next-Gen Format Support**: Seamlessly upload `WebP` and `AVIF` images to product galleries.
-   **SVG Support**: Enable vector graphics (`.svg`) for crisp, scalable product images.
-   **Enhanced Image Processing**:
    -   Supports resizing, cropping, rotating, and watermarking for WebP and AVIF files.
    -   Preserves alpha transparency for WebP and AVIF images.
-   **Gallery Integration**: Plugs into the product gallery processor to bypass default restriction checks, allowing these new file types to be uploaded via the admin panel.

## ⛓️‍💥 Compatibility

-   **PHP**: >= 8.2
-   **Magento**: >= 2.4.5
-   **Extension**: `gd` (The server's GD extension must be compiled with WebP and AVIF support for full functionality).

## 🖥️ Installation

### Via Composer (Recommended)

```bash
composer require musicodev/module-web-images
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:flush
```

## ⚙️ Configuration

This module works primarily out-of-the-box. There are no extensive configuration settings required in the Admin Panel. Once installed, the allowed file extensions (SVG, WebP, AVIF) are automatically registered with the system.

## 🤝 Contribution

Want to contribute to this extension? The quickest way is to [open a pull request](https://help.github.com/articles/about-pull-requests/) on GitHub.

## 🛠️ Support

If you encounter any problems or bugs, please [open an issue](https://github.com/musicodev/magento2-web-images/issues) on GitHub.
