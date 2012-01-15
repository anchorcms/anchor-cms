<?php echo isset($this->error) ? $this->error : ''; ?>

<form action="" method="post">
    <input placeholder="username" name="user">
    <input placeholder="password" name="pass" type="password">
    
    Remember me?
    <input type="checkbox" name="remember">
   
    <button name="submit" type="submit">Login</button>
</form>