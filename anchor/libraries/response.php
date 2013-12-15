<?php

class Response extends System\Response {

	public function send() {
		if(Config::db('profiling')) {
			$profile = View::create('profile', array('profile' => DB::profile()))->render();

			$this->output = preg_replace('#</body>#', $profile . '</body>', $this->output);
		}

		return parent::send();
	}

}