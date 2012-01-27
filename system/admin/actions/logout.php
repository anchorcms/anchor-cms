<?php

if(User::logout()) {
    header('location: ' . $this->get('base_path') . 'admin/login');
} else {
    echo 'Unable to log you out.';
}
