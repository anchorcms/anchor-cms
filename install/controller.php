<?php

class Installation_controller {

	public function stage1() {
		if(is_post()) {
			if(Installer::stage1()) {
				return redirect('stage2');
			}
		}

		$langs = array();

		foreach(glob('../system/language/*/') as $file) {
			$langs[] = basename($file);
		}

		render('stage1', array('languages' => $langs));
	}

	public function stage2() {
		if(is_post()) {
			if(Installer::stage2()) {
				return redirect('stage3');
			}
		}

		$collations = array(
			'utf8_bin' => 'Unicode (multilingual), Binary',
			'utf8_czech_ci' => 'Czech, case-insensitive',
			'utf8_danish_ci' => 'Danish, case-insensitive',
			'utf8_esperanto_ci' => 'Esperanto, case-insensitive',
			'utf8_estonian_ci' => 'Estonian, case-insensitive',
			'utf8_general_ci' => 'Unicode (multilingual), case-insensitive',
			'utf8_hungarian_ci' => 'Hungarian, case-insensitive',
			'utf8_icelandic_ci' => 'Icelandic, case-insensitive',
			'utf8_latvian_ci' => 'Latvian, case-insensitive',
			'utf8_lithuanian_ci' => 'Lithuanian, case-insensitive',
			'utf8_persian_ci' => 'Persian, case-insensitive',
			'utf8_polish_ci' => 'Polish, case-insensitive',
			'utf8_roman_ci' => 'West European, case-insensitive',
			'utf8_romanian_ci' => 'Romanian, case-insensitive',
			'utf8_slovak_ci' => 'Slovak, case-insensitive',
			'utf8_slovenian_ci' => 'Slovenian, case-insensitive',
			'utf8_spanish2_ci' => 'Traditional Spanish, case-insensitive',
			'utf8_spanish_ci' => 'Spanish, case-insensitive',
			'utf8_swedish_ci' => 'Swedish, case-insensitive',
			'utf8_turkish_ci' => 'Turkish, case-insensitive',
			'utf8_unicode_ci' => 'Unicode (multilingual), case-insensitive'
		);

		render('stage2', array('collations' => $collations));
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