<?php

class Attachments {

	public function upload() {
		$image->stripImage();
		$image->resizeImage($newWidth, $newHeight, imagick::FILTER_LANCZOS, 0.9);

		if(in_array('WEBP', Imagick::queryFormats())) {
			$image->setImageFormat('webp');
			$image->setImageAlphaChannel(\Imagick::ALPHACHANNEL_ACTIVATE);
			$image->setBackgroundColor(new \ImagickPixel('transparent'));
		}

		$image->writeImage($resizedFilepath);
	}

}
