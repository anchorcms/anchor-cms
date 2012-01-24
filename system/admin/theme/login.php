<?php echo isset($this->error) ? $this->error : ''; ?>

<form action="<?php echo $this->get('base_path'); ?>admin/login" method="post">
    <input placeholder="username" name="user">
    <input placeholder="password" name="pass" type="password">
    
    Remember me?
    <input type="checkbox" name="remember" value="1">
   
    <button type="submit">Login</button>
</form>
