<?php

class Image {

	private $src_image, $src_w, $src_h;
	private $dst_image;

	public function __construct($src_image, $src_w, $src_h) {
		$this->src_image = $src_image;
		$this->src_w = $src_w;
		$this->src_h = $src_h;
	}

	public function width() {
		return $this->src_w;
	}

	public function height() {
		return $this->src_h;
	}

	public static function open($file) {
		if(file_exists($file) === false) {
			return false;
		}

		list($width, $height, $type) = getimagesize($file);

		if($type === IMAGETYPE_PNG) {
			return new static(imagecreatefrompng($file), $width, $height);
		}

		if($type === IMAGETYPE_JPEG) {
			return new static(imagecreatefromjpeg($file), $width, $height);
		}

		if($type === IMAGETYPE_GIF) {
			return new static(imagecreatefromgif($file), $width, $height);
		}

		return false;
	}

	public function check_available_memory($file, $dst_w, $dst_h, $bloat = 1.68) {
		// Get maxmemory limit in Mb convert to bytes
		$max = ((int) ini_get('memory_limit') * 1024) * 1024;

		// get image size
		list($src_w, $src_h) = getimagesize($file);

		// Source GD bytes
		$src_bytes = ceil((($src_w * $src_h) * 3) * $bloat);

		// Target GD bytes
		$dst_bytes = ceil((($dst_w * $dst_h) * 3) * $bloat);

		$total = $src_bytes + $dst_bytes + memory_get_usage();

		if($total > $max) {
			return false;
		}

		return $src_bytes + $dst_bytes;
	}

	public function sharpen() {
		// resource
		$res = $this->src_image;

		if(is_resource($this->dst_image)) {
			$res = $this->dst_image;
		}

		// define matrix
		$sharpen = array(
			array(0.0, -1.0, 0.0),
			array(-1.0, 5.0, -1.0),
			array(0.0, -1.0, 0.0)
		);

		// calculate the divisor
		$divisor = array_sum(array_map('array_sum', $sharpen));

		// apply the matrix
		imageconvolution($res, $sharpen, $divisor, 0);

		return $this;
	}

	public function blur() {
		// resource
		$res = $this->src_image;

		if(is_resource($this->dst_image)) {
			$res = $this->dst_image;
		}

		// define matrix
		$gaussian = array(
			array(1.0, 2.0, 1.0),
			array(2.0, 4.0, 2.0),
			array(1.0, 2.0, 1.0)
		);

		// calculate the divisor
		$divisor = array_sum(array_map('array_sum', $gaussian));

		// apply the matrix
		imageconvolution($res, $gaussian, $divisor, 0);

		return $this;
	}

	public function grayscale() {
		// resource
		$res = $this->src_image;

		if(is_resource($this->dst_image)) {
			$res = $this->dst_image;
		}

		imagefilter($res, IMG_FILTER_GRAYSCALE);

		return $this;
	}

	public function resize($dst_w, $dst_h) {
		$ratio = 0;

		// landscape
		if($this->src_w > $this->src_h) {
			$ratio = $dst_w / $this->src_w;
			$dst_h = $this->src_h * $ratio;
		}
		// portrait
		if($this->src_w < $this->src_h) {
			$ratio = $dst_h / $this->src_h;
			$dst_w = $this->src_w * $ratio;
		}
		// square : use smaller value as the match
		if($this->src_w == $this->src_h) {
			if($dst_w > $dst_h) {
				$dst_w = $dst_h;
			}else{
				$dst_h = $dst_w;
			}
		}

		$this->dst_image = imagecreatetruecolor($dst_w, $dst_h);

		$params = array(
			'dst_image' => $this->dst_image,
			'src_image' => $this->src_image,
			'dst_x' => 0,
			'dst_y' => 0,
			'src_x' => 0,
			'src_y' => 0,
			'dst_w' => $dst_w,
			'dst_h' => $dst_h,
			'src_w' => $this->src_w,
			'src_h' => $this->src_h
		);

		call_user_func_array('imagecopyresampled', array_values($params));

		return $this;
	}

	public function crop($dst_w, $dst_h, $src_x, $src_y) {

		// if the source image is square use smaller dest size
		// @link http://srccd.com/posts/the-move-to-anchor-cms
		if($this->src_w == $this->src_h) {
			if($dst_w > $dst_h) {
				$dst_w = $dst_h;
			}
			else {
				$dst_h = $dst_w;
			}
		}

		$this->dst_image = imagecreatetruecolor($dst_w, $dst_h);

		$params = array(
			'dst_image' => $this->dst_image,
			'src_image' => $this->src_image,

			'dst_x' => 0,
			'dst_y' => 0,
			'src_x' => $src_x,
			'src_y' => $src_y,

			'dst_w' => $dst_w,
			'dst_h' => $dst_h,
			'src_w' => $dst_w,
			'src_h' => $dst_h
		);

		call_user_func_array('imagecopyresampled', array_values($params));

		return $this;
	}

	public function palette($percentages = array(0.2, 0.7, 0.5)) {

		// Now set dimensions on where to pull color values from (based on percentages).
		$dimensions[] = ($this->src_w - ($this->src_w * $percentages[0]));
		$dimensions[] = ($this->src_h - ($this->src_h * $percentages[0]));

		$dimensions[] = ($this->src_w - ($this->src_w * $percentages[0]));
		$dimensions[] = ($this->src_h - ($this->src_h * $percentages[1]));

		$dimensions[] = ($this->src_w - ($this->src_w * $percentages[1]));
		$dimensions[] = ($this->src_h - ($this->src_h * $percentages[1]));

		$dimensions[] = ($this->src_w - ($this->src_w * $percentages[1]));
		$dimensions[] = ($this->src_h - ($this->src_h * $percentages[0]));

		$dimensions[] = ($this->src_w - ($this->src_w * $percentages[2]));
		$dimensions[] = ($this->src_h - ($this->src_h * $percentages[2]));

		// Here we'll pull the color values of certain pixels around the image based on our dimensions set above.
		for($k = 0; $k < 10; $k++) {
			$newk = $k + 1;
			$rgb[] = imagecolorat($this->src_image, $dimensions[$k], $dimensions[$newk]);
			$k++;
		}

		// Almost done! Now we need to get the individual r,g,b values for our colors.
		foreach($rgb as $colorvalue) {
			$r[] = dechex(($colorvalue >> 16) & 0xFF);
			$g[] = dechex(($colorvalue >> 8) & 0xFF);
			$b[] = dechex($colorvalue & 0xFF);
		}

		return array(
			strtoupper($r[0] . $g[0] . $b[0]),
			strtoupper($r[1] . $g[1] . $b[1]),
			strtoupper($r[2] . $g[2] . $b[2]),
			strtoupper($r[3] . $g[3] . $b[3]),
			strtoupper($r[4] . $g[4] . $b[4]));
	}

	public function output($type = 'png', $file = null) {
		if($type == 'png') {
			imagepng($this->dst_image, $file, 9);
		}
		elseif($type == 'jpeg' or $type == 'jpg') {
			imagejpeg($this->dst_image, $file, 75);
		}
		elseif($type == 'gif') {
			imagegif($this->dst_image, $file);
		}

		imagedestroy($this->dst_image);
	}

}