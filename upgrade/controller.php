<?php

class Upgrade_controller {

	public function stage1() {
		if(Input::method() == 'POST') {

			// run patch containing the changes
			require PATH . 'upgrade/patch.php';

			Migrations::apply();

			Config::write(PATH . 'config.php', Config::get());

			return redirect('complete');
		}

		render('stage1');
	}

	public function complete() {
		render('complete');
	}

}