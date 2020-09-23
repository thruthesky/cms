<div class="px-40 mt-60">
    <div class="fs-20"><?=tr(LOGIN_HEAD)?></div>
    <h1 class="fs-40 font-weight-bold mb-56"><?=tr(LOGIN)?></h1>
    <form class="login-form" onsubmit="apiUserLogin(this); return false;">
        <label class="form-label fs-14 color-light"><?=tr('emailAddress')?></label>
        <div class="input-group mb-34">
            <input type="email" class="form-control smat-input" aria-label="emailHelp" name="user_email">
            <div class="input-group-append">
                <span class="input-group-text smat-input-group-text"><i class="fa fa-user"></i></span>
            </div>
        </div>

        <label class="form-label fs-14 color-light"><?=tr(PASSWORD)?></label>
        <div class="input-group mb-48">
            <input type="password" class="form-control smat-input" name="user_pass">
            <div class="input-group-append show pointer">
                <span class="input-group-text smat-input-group-text"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>

        <button type="submit" class="btn sbtn-primary text-white btn-lg w-100 text-uppercase mb-3"><?=tr(LOGIN)?></button>

        <div class="d-flex justify-content-between mb-56 fs-12">
            <a class="color-black" href="<?php echo Config::$registerPage?>">Forgot password?</a>
            <a class="color-black" href="<?php echo Config::$registerPage?>">Register</a>
        </div>

        <div class="mb-56 text-center" style="height: 14px; border-bottom: 1px solid #B1B1B1">
              <span class="px-10 bg-white color-lighter">
                <?=tr('or')?>
              </span>
        </div>
    </form>

    <div class="mb-56">
        <?php include widget('social-login/buttons') ?>
    </div>
</div>



