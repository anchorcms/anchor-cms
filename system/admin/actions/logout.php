<?php
    User::logout();
    header('location: ' . $this->get('base_path') . 'admin/login');