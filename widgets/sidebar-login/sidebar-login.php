
<?php if ( loggedIn() ) { ?>
    <div class="">
        <a class="d-flex" href="#" onclick="loginOrProfile()">
            <img class="user-profile-photo icon-md-size circle" src="<?=loginProfilePhotoUrl()?>">
            <div class="ml-3 fs-xl">
                <div><?=mb_strcut(loginNickname(), 0, 10)?></div>
                <div class="fs-xs">login in with <?=login(SOCIAL_LOGIN)?></div>
            </div>
        </a>

        <?php if ( admin() ) {?>
        <div>
            <a href="?page=admin.home">Admin Page</a>
        </div>
        <?php } ?>

        <div>
            <a href="/?page=user.logout">Logout</a>
        </div>
    </div>
<?php } else { ?>
    <div class="fs-xs">
        <form class="mb-3" onsubmit="return apiUserLogin(this)">
            <div class="">
                <label class="form-label mb-1">Email address</label>
                <input class="form-control rounded-0" type="email"  aria-describedby="Input your email address" name="user_email">
            </div>
            <div class="mt-3">
                <label class="form-label mb-1">Password</label>
                <input class="form-control rounded-0" type="password" name="user_pass">
            </div>
            <div class="d-flex justify-content-between">
                <button class="pl-0 pr-3 border-0 bg-transparent bold" style="height: 30px;" type="submit">Login</button>
                <a class="pt-2" data-button="register" href="<?php echo Config::$registerPage?>">Register</a>
            </div>
        </form>
		<?php include widget('social-login/buttons'); ?>
    </div>
<?php } ?>

