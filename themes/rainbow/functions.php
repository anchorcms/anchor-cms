<?php

function plural($num, $suffix = 's') {
	if(round($num) !== 1) {
		return $suffix;
	}

	return '';
}

function words() {
    return implode(func_get_args(), ' ');
}

function relative_time($time, $tense = 'ago') {
    if(!is_numeric($time)) $time = strtotime($time);

    $periods = ['second', 'minute', 'hour', 'day', 'week', 'month', 'year'];
    $lengths = [60, 60, 24, 7, 4.35, 12];
    $now = time();

    $difference = $now - $time;
    if($difference <= 10 && $difference >= 0) {
        return $tense = 'just now';
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    return words(round($difference), $periods[$j] . plural($difference), $tense);
}
