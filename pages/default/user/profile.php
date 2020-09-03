<?php
$user = $apiLib->userResponse(sessionID());

?>
<div class="container pb-3">
    <h1>Profile</h1>
    <div class="no-gutters">
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?=$user['user_email']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Full Name</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?=$user['first_name']?> <?=$user['middle_name']?> <?=$user['last_name']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Nickname</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?=$user['nickname']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?=$user['mobile']?>">
            </div>
        </div>
    </div>
</div>
<div class="container pb-3">
    <a class="btn btn-secondary" data-button="profile-update" href="/?page=user.update">Update Profile</a>
    <a class="btn btn-danger" href="/?page=user.resign">Resign</a>
</div>

