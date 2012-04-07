<?php

class Installation_controller {

	public function stage1() {
		if(is_post()) {
			if(Installer::stage1()) {
				return redirect('stage2');
			}
		}

		render('stage1');
	}

	public function stage2() {
		if(is_post()) {
			if(Installer::stage2()) {
				return redirect('stage3');
			}
		}

		render('stage2');
	}

	public function stage3() {
		if(is_post()) {
			if(Installer::stage3()) {
				return redirect('stage4');
			}
		}

		render('stage3');
	}

	public function stage4() {
		if(is_post()) {
			if(Installer::stage4()) {
				return redirect('complete');
			}
		}

		render('stage4');
	}

	public function download() {
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename="config.php"');
		echo $_SESSION['config'];
	}

	public function complete() {
		render('complete');
	}

	public function compat() {
		$data['compat'] = Installer::compat_check();
		render('compat', $data);
	}

}