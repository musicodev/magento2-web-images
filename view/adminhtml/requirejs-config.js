var config = {
	map: {
		'*': {
			'Magento_Backend/js/media-uploader': 'MusicoDev_WebImages/js/media-uploader'
		}
	},
	config: {
		mixins: {
			'Magento_Ui/js/form/element/image-uploader': {
				'MusicoDev_WebImages/js/form/element/image-uploader-mixin': true
			}
		}
	}
};
