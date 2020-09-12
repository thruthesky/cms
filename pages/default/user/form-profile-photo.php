
<? if (loggedIn()) { ?>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="wh120x120 position-relative overflow-hidden pointer">

            <i class="fa fa-camera position-absolute z-index-middle fs-xxl right bottom"></i>
            <input
                    class="position-absolute z-index-high fs-xxxl opacity-01"
                    type="file" name="file"
                    onchange="onChangeFile(this, {html: $('.user-update-profile-photo'), deleteButton: true, progress: $(this).parents('.register').find('.progress'),
                                     success: function(res) {
                                        $app.userProfilePhotoSrc(res.url);
                                     }})">

            <div class="user-update-profile-photo position-relative z-index-low circle wh120x120 overflow-hidden">
                <img src="<?=ANONYMOUS_PROFILE_PHOTO?>">
            </div>

        </div>

    </div>
    <div class="progress w-120 m-auto" style="display: none">
        <div class="progress-bar progress-bar-striped" role="progressbar"  aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

<?}?>

