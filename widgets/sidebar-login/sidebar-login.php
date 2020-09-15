
<div class="card border-success mb-3 login-information" style="display: none;">
    <div class="card-header bg-transparent border-success">Blog title</div>
    <div class="card-body text-success">


        <a class="user-profile-photo profile-photo-size circle" href="/?page=user.profile"></a>

        <h5 class="card-title nickname"></h5>
        <p class="card-text">Blog description. In the long history of the world, only a few generation have been granted the role of </p>
    </div>
    <div class="card-footer bg-transparent border-success">
        <a class="btn btn-secondary" href="/?page=user.logout">Logout</a>
        <a class="btn btn-secondary" data-button="profile" href="/?page=user.profile">Profile</a>
    </div>
</div>





<form class="login login-form" onsubmit="return onLoginFormSubmit(this)" style="display: none;">
    <div class="mb-3">
        <label class="form-label">Email address</label>
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

