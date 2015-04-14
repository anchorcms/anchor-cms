<?php

namespace Forms;

class Post extends Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('title', [
			'label' => 'Title',
		]));

		$this->addElement(new \Forms\Elements\Textarea('html', [
			'label' => 'Content',
			'cols' => 0,
			'rows' => 0,
			'attributes' => ['class' => 'editor', 'placeholder' => 'Just write.']
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save Changes',
			'attributes' => ['class' => 'button'],
		]));

	}


}
