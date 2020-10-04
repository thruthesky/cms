<?php
$options = get_page_options();


?>
<script>
    $$(function () {
        vm.loadProfile()
            .then(function (res) {
            });

        <?php if ( isset($options['code']) ) { ?>
        vm.toastWarning({
            body: '<?=tr($options['code'])?>'
        })
        <?php } ?>
    });

    function submitButton() {
        const obj = $('.edit-input-box');
        const name = obj.name;
        const value = obj.value;
        vm.updateUserField(name, value, function () {
            vm.closeDialog();
            vm.toastOk({body: 'Profile updated'});
        });
        return false;
    }

    function edit(field, fieldName) {
        const v = vm.user[field] ? vm.user[field] : '';
        vm.showDialog({
            header: 'Update ' + fieldName,
            body: '<h1>HELLo</h1>' +
                '<form onsubmit="return submitButton();">' +
                '<div>' +
                '   <input class="form-input edit-input-box" name="' + field + '" value="' + v + '">' +
                '</div>' +
                '<button type="submit">Update</button>' +
                '</form>'
        });
        setTimeout(setFocus, 100);
        setTimeout(setFocus, 300);
    }
    function setFocus() {
        if ( $('.edit-input-box') ) {
            $('.edit-input-box').focus();
        }
    }
</script>
<?php if ( isset( $options['code'] ) ) { ?>
    <div class="alert alert-danger"><?=tr($options['code'])?></div>
<?php } ?>
<div class="p-3">

    <div class="fs-12 mb-68 gray"><?= tr( PROFILE_HEAD ) ?></div>


    <div class="flex justify-content-center">
        <div class="relative size-xxl">
            <i class="fa fa-camera absolute left bottom p-2 fs-lg"></i>
            <img class="w-100 circle" src="<?= myProfilePhotoUrl() ?>">
        </div>
    </div>

    <div class="flex justify-content-center align-items-center pointer"
         onclick="edit('fullname', '<?= tr( 'name' ) ?>')">
        <div id="uname">{{ user.fullname ? user.fullname : '<?= tr( updateYourName ) ?>' }}</div>
        <i class="fa fa-edit fs-sm pl-2"></i>
    </div>

    <section class="bg-lighter p-3 rounded">


        <div class="mt-5 w-xxl pointer" onclick="edit('user_email', '<?= tr( emailAddress ) ?>')">
            <div><?= tr( emailAddress ) ?></div>
            <div class="flex align-items-center justify-content-between">
                <div>{{ user.user_email }}</div>
                <i class="fa fa-edit fs-sm pl-2"></i>
            </div>
        </div>


        <div class="mt-5 w-xxl pointer" onclick="edit('nickname', '<?= tr( nickname ) ?>')">
            <div><?= tr( nickname ) ?></div>
            <div class="flex align-items-center justify-content-between">
                <div>{{ user.nickname }}</div>
                <i class="fa fa-edit fs-sm pl-2"></i>
            </div>
        </div>

        <div class="mt-3 w-xxl pointer" onclick="app.open(mobilePage)">
            <div><?= tr( mobileNo ) ?></div>
            <div class="flex align-items-center justify-content-between">
                <div>{{ user.mobile }}</div>
                <i class="fa fa-edit fs-sm pl-2"></i>
            </div>
        </div>

    </section>


    <div class="d-flex justify-content-between fs-xs">
        <span v-on:click="changePassword">Change password</span>
        <button @click="logout">Logout</button>
    </div>

    <?php if ( loginSocialProviderName() ) { ?>
        <div>
            you are logged in with <?=loginSocialProviderName()?>
        </div>
    <?php } ?>
</div>
