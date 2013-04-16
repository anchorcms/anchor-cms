<?php

abstract class Plugin {

	//public function install();

	//public function uninstall();

	protected function error($status) {
		return Response::create(new Template('404'), 404);
	}

}