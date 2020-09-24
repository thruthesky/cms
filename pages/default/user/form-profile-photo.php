<? if (loggedIn()) { ?>
    <input class="profile-photo-url" type="hidden" name="photo_url" value="">
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="wh145x145 position-relative pointer overflow-hidden p-2">

            <i class="fa fa-camera position-absolute z-index-middle fs-xxl left bottom darkergray"></i>
            <input
                    class="position-absolute z-index-high fs-xxxl opacity-01"
                    type="file" name="file"
                    onchange="onChangeFile(this, {
                        html: $('.user-profile-photo'),
                        deleteButton: true,
                        progress: $('.profile-photo-progress'),
                        success: function(res) { $('input.profile-photo-url').val(res['url']);  },
            }
            )">

            <div class="user-profile-photo position-relative z-index-low circle w-100 overflow-hidden border-shadow">
                <img class="w-100" src="<?=myProfilePhotoUrl()?>">
            </div>

        </div>

    </div>
    <div class="profile-photo-progress progress w-120 m-auto" style="display: none">
        <div class="progress-bar progress-bar-striped" role="progressbar"  aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

<?}?>

