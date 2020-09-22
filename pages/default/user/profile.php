<?php
?>
<div class="container py-3">
    <div class="card">
        <div class="card-body">

            <h1>User Profile</h1>
            <div class="d-flex justify-content-center mb-3">
                <img class="userPhoto circle w-100 wh120x120" src="<?=myProfilePhotoUrl()?>" alt="user photo">
            </div>

            <?php if ( login(SOCIAL_LOGIN) == null ) { ?>
            <div class="form-group row">
                <label for="user_email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="user_email" value="<?=login('user_email')?>">
                </div>
            </div>
            <?php } ?>

            <div class="form-group row">
                <label for="fullname" class="col-sm-2 col-form-label"><?=tr('name')?></label>
                <div class="col-sm-10">
                    <input type="text" name="fullname" class="form-control-plaintext" id="fullname" value="<?=login('fullname')?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="nickname" class="col-sm-2 col-form-label">Nickname</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="nickname" value="<?=login('nickname')?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="mobile" class="col-sm-2 col-form-label">Mobile No.</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="mobile" value="<?=login('mobile')?>">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a class="btn btn-secondary" data-button="profile-update" href="/?page=user.update">Update Profile</a>
                <a class="btn btn-secondary" href="/?page=user.logout">Logout</a>
                <a class="btn btn-danger" href="/?page=user.resign">Resign</a>
            </div>
        </div>

    </div>

	<?php include widget('user.logged-with') ?>

</div>

