<?php

    //  Check to see if the user's logged in.
    if(!User::current()) {
        header('location: /admin/login');
    }