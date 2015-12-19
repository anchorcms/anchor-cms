<?php

namespace Controllers\Admin;

class Meta extends Backend {

	public function getIndex() {
		$meta = $this->meta->where('key', 'NOT LIKE', 'custom_%')->get();

		$form = new \Forms\Meta([
			'method' => 'post',
			'action' => $this->url->to('/admin/meta/update'),
		]);
		$form->init();

		$form->getElement('token')->setValue($this->csrf->token());

		$options = $this->pages->dropdownOptions();

		$form->getElement('home_page')->setOptions($options);

		$form->getElement('posts_page')->setOptions($options);

		$options = $this->themes->dropdownOptions();

		$form->getElement('theme')->setOptions($options);

		$values = [];

		foreach($meta as $row) {
			$values[$row->key] = $row->value;
		}

		$form->setValues($this->session->getFlash('input', $values));

		$vars['title'] = 'Site Metadata';
		$vars['form'] = $form;

		return $this->renderTemplate('layout', 'meta/edit', $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$meta = $this->meta->where('key', '=', $id)->fetch();

		$form = new \Forms\Meta;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', $this->url->to('/admin/meta'));
		}

		unset($input['token']);

		foreach($input as $key => $value) {
			$this->meta->where('key', '=', $key)->update([
				'value' => $value,
			]);
		}

		$this->messages->success('Metadata updated');
		return $this->response->withHeader('location', $this->url->to('/admin/meta'));
	}

}
