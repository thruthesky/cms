
<div class="card border-success mb-3 login-information" style="display: none;">
    <div class="card-header bg-transparent border-success">Blog title</div>
    <div class="card-body text-success">
        <img class="userPhoto circle" src="" alt="user photo">
        <h5 class="card-title nickname"></h5>
        <p class="card-text">Blog description. In the long history of the world, only a few generation have been granted the role of </p>
    </div>
    <div class="card-footer bg-transparent border-success">
        <button type="button" onclick="setLogout(); move(homePage);">Logout</button>
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
        <button type="submit" class="btn btn-primary">Submit</button>
        <a class="btn btn-secondary" href="<?php echo Config::$registerPage?>">Register</a>
    </div>
</form>

<script>

    window.addEventListener('load', function() {
        resetLogin();
    });
    function resetLogin() {
        if ( loggedIn() ) {
            showLoginInformation();
        } else {
            showLoginForm();
        }
    }

    function showLoginInformation() {

        var $box = $('.login-information');
        $box.show();
        $box.find('.nickname').text( getCookie('nickname') );
        $box.find('.userPhoto').attr('src', getUserPhotoUrl());

    }
    function showLoginForm() {
        $('.login-form').show();
    }
    function hideLoginForm() {
        $('.login-form').hide();
    }

    function loginUrl(form) {
        var url = apiUrl + '?route=user.login&' + $( form ).serialize();
        console.log(url);
        return url;
    }

    function onLoginFormSubmit(form) {
        $.ajax( loginUrl(form) )
            .done(function(re) {

                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    setLogin(re);
                    console.log(getCookie('session_id'));
                    hideLoginForm();
                    showLoginInformation();
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }
</script>
