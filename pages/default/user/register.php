<?php
//
$user = [];
if ( loggedIn()) {
    $user = $apiLib->userResponse(sessionId());
}


?>

<script>

    function registerUrl(form) {
        var method = "<?= loggedIn() ? 'update' : 'register'?>";

        var url = apiUrl + '?route=user.' + method + '&' + $( form ).serialize();
        console.log(url);
        return url;
    }

    function onRegisterFormSubmit(form) {

        $.ajax( registerUrl(form) )
            .done(function(re) {
                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    console.log('re', re);
                    setLogin(re);
                    // move(homePage);
                }
            })
            .fail(function() {
                alert( "Server error" );
            });

        return false;
    }
</script>
<form onsubmit="return onRegisterFormSubmit(this)">

    <? if (loggedIn()) { ?>
        <input type="hidden" name="session_id" value="<?=$user['session_id']?>">
    <?}?>

    <div class="mb-3">
        <label  class="form-label">Email address</label>
        <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" value="<?=$user['user_email']?>">
    </div>

    <? if (!loggedIn()) { ?>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="user_pass">
        </div>
    <?}?>


    <div class="container">
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label  class="form-label">First name</label>
                    <input type="text" class="form-control" name="first_name" value="<?=$user['first_name']?>">
                </div>
            </div>
            <div class="col">

                <div class="mb-3">
                    <label class="form-label">Middle name</label>
                    <input type="text" class="form-control" name="middle_name" maxlength="1" value="<?=$user['middle_name']?>">
                </div>
            </div>
            <div class="col">

                <div class="mb-3">
                    <label class="form-label">Last name</label>
                    <input type="text" class="form-control" name="last_name" value="<?=$user['last_name']?>">
                </div>

            </div>
        </div>
    </div>


    <div class="mb-3">
        <label class="form-label">Nickname</label>
        <input type="text" class="form-control" name="nickname"  value="<?=$user['nickname']?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Mobile number</label>
        <input type="text" class="form-control" name="mobile"  value="<?=$user['mobile']?>">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

