<?php

?>

<div class="py-5 px-5">
    <h4 class="mb-0"><?=tr(LOGIN_HEADER)?></h4>
    <h1 class="font-weight-bold mb-5"><?=tr(LOGIN)?></h1>
    <form class="login-form" onsubmit="apiUserLogin(this); return false;">
        <label class="form-label"><?=tr('emailAddress')?></label>
        <div class="input-group mb-3">
            <input type="email" class="form-control" aria-label="emailHelp" name="user_email">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
        </div>

        <label class="form-label"><?=tr(PASSWORD)?></label>
        <div class="input-group mb-5">
            <input type="password" class="form-control" name="user_pass">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-key"></i></span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 text-uppercase mb-3"><?=tr(LOGIN)?></button>

        <div class="d-flex justify-content-between mb-4 fs-sm">
            <a href="<?php echo Config::$registerPage?>">Forgot password?</a>
            <a href="<?php echo Config::$registerPage?>">Register</a>
        </div>

        <div class="mb-5 text-center" style="height: 14px; border-bottom: 2px solid lightgrey">
              <span class="px-2 bg-white text-black-50">
                OR
              </span>
        </div>
    </form>

    <?php include widget('social-login/buttons') ?>
</div>



