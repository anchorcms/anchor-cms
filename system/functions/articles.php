<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for articles
*/
function article_id() {
	if($itm = IoC::resolve('article')) {
		return $itm->id;
	}
	
	return '';
}

function article_title() {
	if($itm = IoC::resolve('article')) {
		return $itm->title;
	}
	
	return '';
}

function article_slug() {
	if($itm = IoC::resolve('article')) {
		return $itm->slug;
	}
	
	return '';
}

function article_url() {
	if($itm = IoC::resolve('article')) {
		$page = IoC::resolve('posts_page');
		return Url::make($page->slug . '/' . $itm->slug);
	}
	
	return '';
}

function article_description() {
	if($itm = IoC::resolve('article')) {
		return $itm->description;
	}
	
	return '';
}

function article_html() {
	if($itm = IoC::resolve('article')) {
		return Posts::parse($itm->html);
	}
	
	return '';
}

function article_css() {
	if($itm = IoC::resolve('article')) {
		return $itm->css;
	}
	
	return '';
}

function article_js() {
	if($itm = IoC::resolve('article')) {
		return $itm->js;
	}
	
	return '';
}

function article_time() {
	if($itm = IoC::resolve('article')) {
		return $itm->created;
	}
	
	return '';
}

function article_date() {
	if(article_time() !== '') {
	    return date(Config::get('metadata.date_format'), article_time());
	}
	
	return '';
}

function article_status() {
	if($itm = IoC::resolve('article')) {
		return $itm->status;
	}
	
	return '';
}

function article_category() {
	if($itm = IoC::resolve('article')) {
		return $itm->category;
	}
	
	return '';
}

function article_total_comments() {
	if($itm = IoC::resolve('article')) {
		return $itm->total_comments;
	}
	
	return 0;
}

function article_thumbnail_filename() {
	if($itm = IoC::resolve('article')) {
		return $itm->thumbnail;
	}
	
	return '';
}
function article_thumbnail_src() {
	return PATH . 'uploads/' . article_thumbnail_filename();
}

function article_thumbnail($width = 300, $height = 'auto', $fallback = false) {
	if(article_thumbnail_filename() !== '') {
	
		Thumbnail::set(article_thumbnail_src());
		$thumb = Thumbnail::resize($width, $height);
		
		if($thumb->src) {
		    return '<img width="' . $thumb->width . '" height="' . $thumb->height . '" src="'. $thumb->src . '">';
		}
	}
	
	return '';
}

function article_author() {
	if($itm = IoC::resolve('article')) {
		return $itm->author;
	}
	
	return '';
}

function article_author_bio() {
	if($itm = IoC::resolve('article')) {
		return $itm->bio;
	}
	
	return '';
}

function article_custom_fields() {
    if($itm = IoC::resolve('article')) {
    	if(isset($itm->custom_fields)) {
    	    // get associative array
    	    $data = json_decode($itm->custom_fields, true);
    	    return is_array($data) ? $data : array();
    	}
    }
    
    return array();
}

function article_custom_field($key, $default = '') {
    $fields = article_custom_fields();
    return isset($fields[$key]) ? $fields[$key]['value'] : $default;
}

function customised() {
	if($itm = IoC::resolve('article')) {
		return strlen($itm->css) > 0 or strlen($itm->js) > 0;
	}
	
	return false;
}
