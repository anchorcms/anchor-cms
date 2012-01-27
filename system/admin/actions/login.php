<?php

//  Check for a form submit
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// get post data
	$post = array();

	foreach(array('user', 'pass', 'remember') as $field) {
		$post[$field] = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
	}
	
	if(empty($post['user']) or empty($post['pass'])) {
		$this->error = '<p class="error">Please fill in all the fields.</p>';
	}
	
    if(empty($this->error)) {
        $user = User::verify($post['user'], $post['pass'], $post['remember']);

        //  User exists, and details are right. Is it a verified account?
        if($user) {
            if($user->status > 1) {
                header('location: ' . $this->get('base_path') . 'admin');
            } else {
                $this->error = '<p class="error">This account does not have the neccessary status level to administer this site.</p>';
            }
        } else {
            $this->error = '<p class="error">Incorrect login details for ' . $post['user'] . '.</p>';
        }
    }
}
