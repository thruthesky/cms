<?php
$user = $apiLib->userResponse(sessionID());

//dog($user);
?>


<h1>Profile</h1>

<div class="container pb-3">
    <div class="row">
        <div class="col-2">
            Email
        </div>
        <div class="col">
            <?=$user['user_email']?>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            Name
        </div>
        <div class="col">
            <?=$user['first_name']?> <?=$user['middle_name']?> <?=$user['last_name']?>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            Nickname
        </div>
        <div class="col">
            <?=$user['nickname']?>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            Mobile
        </div>
        <div class="col">
            <?=$user['mobile']?>
        </div>
    </div>
</div>
<div class="container pb-3">
    <a class="btn btn-secondary" data-button="profile-update" href="/?page=user.update">Update Profile</a>
    <a class="btn btn-danger" href="/?page=user.resign">Resign</a>
</div>

