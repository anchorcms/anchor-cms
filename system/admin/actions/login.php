<?php    
    //  Check for a form submit
    if(isset($_POST['submit'])) {
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
        $pass = User::encrypt($_POST['pass']);
        
        if(!empty($user) && !empty($pass)) {
            $check = User::verify($user, $pass);
            
            //  User exists, and details are right. Is it a verified account?
            if($check[0]) {
                if($check[0]->status > 1) {
                    header('location: ../admin');
                } else {
                    $error = '<p class="error">This account does not have the neccessary status level to administer this site.</p>';
                }
            } else {
                $error = '<p class="error">Incorrect login details (' . $user . ' and ' . $pass . ')</p>';
            }
        } else {
            $error = '<p class="error">Please fill in all the fields.</p>';
        }
    }