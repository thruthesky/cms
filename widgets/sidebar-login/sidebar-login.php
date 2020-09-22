
<?php if ( loggedIn() ) { ?>
    <div class="card border-success mb-3 login-information">
        <div class="card-header bg-transparent border-success">Blog title</div>
        <div class="card-body text-success">
            <a class="user-profile-photo profile-photo-size circle" href="/?page=user.profile"></a>
            <p class="card-text">Photo</p>
            <h5 class="card-title nickname">Nickname</h5>
            <div>No of posts, No of comments</div>
        </div>
        <div class="card-footer bg-transparent border-success">
            <a class="btn btn-secondary" href="/?page=user.logout">Logout</a>
            <a class="btn btn-secondary" data-button="profile" href="/?page=user.profile">Profile</a>
        </div>
    </div>
<?php } else { ?>
    <div class="login-form">

        <form class="mb-3" onsubmit="return apiUserLogin(this)">
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control"aria-describedby="emailHelp" name="user_email">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="user_pass">
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">LOGIN</button>
                <a class="btn btn-secondary" data-button="register" href="<?php echo Config::$registerPage?>">Register</a>
            </div>
        </form>

		<?php include widget('social-login/buttons'); ?>

    </div>


<?php } ?>

