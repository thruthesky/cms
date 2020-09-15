<?php

?>

<div class="mt-5">

    <h1>Login page</h1>

    <form class="login-form" onsubmit="apiUserLogin(this, openHome); return false;">
        <div class="mb-3">
            <label class="form-label"><?=tr('emailAddress')?></label>
            <input type="email" class="form-control"aria-describedby="emailHelp" name="user_email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="user_pass">
        </div>
        <div class="mb-3 form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="remember" value="on">
                Remember Me</label>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">LOGIN</button>
            <a class="btn btn-secondary" data-button="register" href="<?php echo Config::$registerPage?>">Register</a>
        </div>
    </form>

</div>


<?php include widget('social-login/buttons') ?>

