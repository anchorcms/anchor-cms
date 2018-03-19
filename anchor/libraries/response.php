<?php

use System\config;
use System\database as DB;
use System\view;

/**
 * response class
 */
class response extends System\Response
{
    /**
     * Sends a response and inserts profiling data if enabled
     *
     * @return void
     * @throws \ErrorException
     */
    public function send()
    {
        // if profiling is enabled, place the profiler info before the closing body tag
        if (Config::db('profiling')) {
            $profile = View::create('profile', ['profile' => DB::profile()])->render();

            $this->output = preg_replace('#</body>#', $profile . '</body>', $this->output);
        }

        parent::send();
    }
}
