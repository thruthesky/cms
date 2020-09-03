<?php
//
$user = [
    'session_id' => '',
    'user_email' => '',
    'first_name' => '',
    'middle_name' => '',
    'last_name' => '',
    'nickname' => '',
    'mobile' => '',
    'photoURL' => ''
];
if ( loggedIn()) {
    $user = $apiLib->userResponse(sessionId());
}
?>

<script>

    //    function registerUrl(form) {
    //        const method = "<?//= loggedIn() ? 'update' : 'register'?>//";
    //        return apiUrl + '?route=user.' + method + '&' + $( form ).serialize();
    //    }

    function onRegisterFormSubmit(form) {

        const method = "<?= loggedIn() ? 'update' : 'register'?>";

        let data = objectifyForm(form);
        data['route'] = 'user.' + method;

        if ($(form).find('.userPhoto').attr('src') !== "<?=ANONYMOUS_PROFILE_PHOTO?>") {
            data['photoURL'] = $(form).find('.userPhoto').attr('src')
        }

        $.ajax( {
            method: 'GET',
            url: apiUrl,
            data: data
        }).done(function(re) {
            if ( isBackendError(re) ) {
                alert(re);
            }
            else {
                console.log('re', re);
                setLogin(re);
                move(homePage);
            }
        })
            .fail(function() {
                alert( "Server error" );
            });

        return false;
    }
</script>
<div class="container py-3">
    <div class="card">
        <div class="card-body">
            <h1><?= loggedIn() ? "User Update" : "User Registration"  ?></h1>
            <form class="register" onsubmit="return onRegisterFormSubmit(this)">

                <? if (loggedIn()) { ?>
                    <div class="d-flex justify-content-center">
                        <div class="upload-profile-box circle wh120x120">
                            <input type="file" name="file" onchange="onChangeUserPhoto(this, {where: $(this).parents().find('.userPhoto'), progress: $(this).parents().find('.progress')})">
                            <img class="userPhoto w-100" src="<?=!empty($user['photoURL']) ? $user['photoURL'] : ANONYMOUS_PROFILE_PHOTO?>" alt="user photo">
                            <div class="progress mt-2" style="display: none">
                                <div class="progress-bar progress-bar-striped" role="progressbar"  aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>

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

                <button type="submit" class="btn btn-primary" data-button="submit">Submit</button>
            </form>
        </div>
    </div>
</div>