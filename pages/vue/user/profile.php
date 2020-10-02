<?php
$options = get_page_options();


?>
<script>
    $$(function () {
        vm.loadProfile()
            .then(function (res) {
                //edit('fullname', "<?//=tr( 'name' )?>//")
            })
    });

    function submitButton() {
        const obj = $('.edit-input-box');
        const name = obj.name;
        const value = obj.value;
        vm.updateUserField(name, value, function () {
            vm.closeDialog();
        });
    }

    function edit(field, fieldName) {
        vm.showDialog({
            header: 'Update ' + fieldName,
            body: '' +
                '<div>' +
                '   <input class="form-input edit-input-box" name="' + field + '" value="' + vm.user[field] + '">' +
                '</div>' +
                '<button onclick="submitButton()">Update</button>'
        });
    }
</script>
<?php if ( isset( $options['messageCode'] ) ) { ?>
    <div class="alert alert-danger"><?= tr( $options['messageCode'] ) ?></div>
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


        <div class="mt-5 w-xxs pointer" onclick="edit('user_email', '<?= tr( emailAddress ) ?>')">
            <div><?= tr( emailAddress ) ?></div>
            <div class="flex align-items-center justify-content-between">
                <div>{{ user.user_email }}</div>
                <i class="fa fa-edit fs-sm pl-2"></i>
            </div>
        </div>


        <div class="mt-5 w-xxs pointer" onclick="edit('nickname', '<?= tr( nickname ) ?>')">
            <div><?= tr( nickname ) ?></div>
            <div class="flex align-items-center justify-content-between">
                <div>{{ user.nickname }}</div>
                <i class="fa fa-edit fs-sm pl-2"></i>
            </div>
        </div>

        <div class="mt-3 w-xxs pointer" onclick="app.open(mobilePage)">
            <div><?= tr( mobileNo ) ?></div>
            <div class="flex align-items-center justify-content-between">
                <div>{{ user.mobile }}</div>
                <i class="fa fa-edit fs-sm pl-2"></i>
            </div>
        </div>

    </section>


    <div class="d-flex justify-content-between fs-xs">
        <span v-on:click="changePassword">Change password</span>
        <a href="/?user.logout">Logout</a>
    </div>

	<?php include widget( 'user.logged-with' ) ?>

</div>

<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content radius-3px">
            <div class="modal-body position-relative pt-34">
                <span class="position-absolute top right px-10 fs-xl pointer" data-dismiss="modal">&times;</span>
                <form id="register-form" onsubmit="return onRegisterFormSubmit()">
                    <input type="hidden" name="session_id" value="<?= login( 'session_id' ) ?>">
                    <label class="modal-title form-label mb-34 fs-14 gray100"></label>
                    <input type="text" class="form-control smat-input mb-34" id="field" name="field" value="">
                    <div class="d-flex justify-content-end mb-6 roboto">
                        <button type="submit"
                                class="btn btn-lg bg-lightgray200 blue text-uppercase"><?= tr( 'submit' ) ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // $$(function() {
    //     $('#profileModal').on('show.bs.modal', function (e) {
    //         const button = $(e.relatedTarget); // Button that triggered the modal
    //         const title = button.data('title');
    //         const field = button.data('field');
    //         const value = button.data('value');
    //
    //         const modal = $(this);
    //         modal.find('.modal-title').text(title);
    //         const input = modal.find('.modal-body input#field');
    //         input.val(value);
    //         input.attr('name', field);
    //     })
    // });
    //
    //
    // function onRegisterFormSubmit() {
    //     apiUserRegister(objectifyForm($('#register-form')), function(res) {
    //         $('#profileModal').modal('hide');
    //         alertModal(tr('profile update'), tr('profile update success'), function (dialog) {
    //             dialog.on('hidden.bs.modal', function (e) {
    //                 location.reload(); // update through jquery
    //                 console.log('do something here');
    //             })
    //         });
    //     }, function(res) {
    //         alertError(res);
    //     });
    //     return false;
    // }


</script>
