<div class="px-40 pt-60">
    <div class="fs-20 black"><?=tr(LOGIN_HEAD)?></div>
    <h1 class=" mb-56 fs-40 font-weight-bold black"><?=tr(LOGIN)?></h1>
    <form class="login-form" onsubmit="apiUserLogin(this); return false;">
        <label class="form-label fs-14 gray100"><?=tr('emailAddress')?></label>
        <div class="input-group mb-34">
            <input type="email" class="form-control smat-input" aria-label="emailHelp" name="user_email">
            <div class="input-group-append">
                <span class="input-group-text smat-input-group-text px-0">
                    <i class="fa fa-user fs-xl"></i>
                </span>
            </div>
        </div>

        <label class="form-label fs-14 gray100"><?=tr(PASSWORD)?></label>
        <div class="input-group mb-48">
            <input type="password" class="form-control smat-input" name="user_pass" autocomplete="new-password">
            <div class="input-group-append show pointer" onclick="showPassword()">
                <span class="input-group-text smat-input-group-text px-0">
                    <i class="fa fa-eye-slash fs-lg"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn bg-lightblue white btn-lg w-100 text-uppercase mb-3"><?=tr(LOGIN)?></button>

        <div class="d-flex justify-content-between mb-56 fs-12">
            <a class="black" href="<?php echo Config::$registerPage?>">Forgot password?</a>
            <a class="black" href="<?php echo Config::$registerPage?>">Register</a>
        </div>

        <div class="mb-56 text-center" style="height: 14px; border-bottom: 1px solid #AFAFAF">
              <span class="px-10 bg-white lightgray">
                <?=tr('or')?>
              </span>
        </div>
    </form>

    <div class="mb-56">
        <?php include widget('social-login/buttons') ?>
    </div>
</div>



