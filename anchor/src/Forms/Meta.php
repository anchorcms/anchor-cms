<?php

namespace Forms;

use Forms\Traits\Filters;
use Forms\Traits\FilterRules;

class Meta extends Form {

	use Filters, FilterRules;

	public function init() {
		$this->setFilters([
			'token' => FILTER_SANITIZE_STRING,
			'sitename' => FILTER_SANITIZE_STRING,
			'description' => FILTER_SANITIZE_STRING,
			'posts_per_page' => FILTER_SANITIZE_NUMBER_INT,
			'admin_posts_per_page' => FILTER_SANITIZE_NUMBER_INT,
			'home_page' => FILTER_SANITIZE_NUMBER_INT,
			'posts_page' => FILTER_SANITIZE_NUMBER_INT,
			'theme' => FILTER_SANITIZE_STRING,
		]);

		$this->setRules([
			'token' => ['required'],
			'sitename' => ['required'],
			'description' => ['required'],
			'posts_per_page' => ['required'],
			'admin_posts_per_page' => ['required'],
			'home_page' => ['required'],
			'posts_page' => ['required'],
			'theme' => ['required'],
		]);

		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('sitename', [
			'label' => 'Site Name',
		]));

		$this->addElement(new \Forms\Elements\Input('description', [
			'label' => 'Site Description',
		]));

		$this->addElement(new \Forms\Elements\Input('posts_per_page', [
			'label' => 'Posts Per Page',
		]));

		$this->addElement(new \Forms\Elements\Input('admin_posts_per_page', [
			'label' => 'Admin Posts Per Page',
		]));

		$this->addElement(new \Forms\Elements\Select('home_page', [
			'label' => 'Home Page',
		]));

		$this->addElement(new \Forms\Elements\Select('posts_page', [
			'label' => 'Posts Page',
		]));

		$this->addElement(new \Forms\Elements\Select('theme', [
			'label' => 'Theme',
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save changes',
			'attributes' => ['class' => 'button'],
		]));
	}

}
