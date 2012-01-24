<?php

//  Check to see if the user's logged in.
if(!User::current() !== false) {
    header('location: ' . $this->get('base_path') . 'admin/login');
}


