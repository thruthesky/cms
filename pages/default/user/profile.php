<?php
$options = get_page_options();
?>
<?php if ( isset($options['messageCode']) ) { ?>
    <div class="alert alert-danger"><?=tr($options['messageCode'])?></div>
<?php } ?>
<div class="px-30 mt-26 mb-56 helvetica">

            <div class="fs-12 mb-68 gray"><?=tr(PROFILE_HEAD)?></div>

            <div class="mb-34">
                <? include 'form-profile-photo.php'?>
            </div>

            <div class="position-relative mb-34 text-center">
                <div class="fs-20 gray"><?=login('fullname')?></div>
                <a href="#profileModal" data-toggle="modal"
                   data-field="fullname"
                   data-title="<?=tr([en=>"Input your name.", ko =>'Input your name.'])?>"
                   data-value="<?=login('fullname')?>"
                ><i class="fa fa-edit gray900 position-absolute top right p-6px"></i></a>
            </div>

    <div class="bg-lightgray px-10 pt-17 pb-20 mb-48">
        <?php if ( login(SOCIAL_LOGIN) == null ) { ?>
                <label class="fs-12 gray"><?=tr(EMAIL)?></label>
                <div class="position-relative mb-22">
                    <div class="gray"><?=login('user_email')?></div>
                    <a href="#profileModal" data-toggle="modal"
                       data-field="user_email"
                       data-title="<?=tr([en=>"Input your email.", ko =>'Input your email.'])?>"
                       data-value="<?=login('user_email')?>"
                    ><i class="fa fa-edit gray900 position-absolute top right p-6px"></i></a>
                </div>
        <?php } ?>

        <label class="fs-12 gray">Nickname</label>
        <div class="position-relative mb-22">
            <div class="gray"><?=login('nickname')?></div>
            <a href="#profileModal" data-toggle="modal"
               data-field="nickname"
               data-title="<?=tr([en=>"Input your nickname.", ko =>'Input your nickname.'])?>"
               data-value="<?=login('nickname')?>"
            ><i class="fa fa-edit gray900 position-absolute top right p-6px"></i></a>
        </div>

        <?php if ( Config::$mobileRequired ) { ?>
            <label class="fs-12 gray">Mobile No.</label>
            <div class="gray"><?=login('mobile')?></div>
        <?php } ?>


    </div>

    <div class="d-flex justify-content-between px-10 gray">
        <div class="text-center w-100 mr-26">
                <div class="fs-36">10</div>
                <hr class="border-light">
                <div class="fs-12">Post</div>
        </div>
        <div class="text-center w-100">
                <div class="fs-36">273</div>
                <hr class="border-light">
                <div class="fs-12">Comment</div>
        </div>

    </div>


<!--            <div class="d-flex justify-content-between">-->
<!--                <a class="btn btn-secondary" data-button="profile-update" href="/?page=user.update">Update Profile</a>-->
<!--                <a class="btn btn-secondary" href="/?page=user.logout">Logout</a>-->
<!--                <a class="btn btn-danger" href="/?page=user.resign">Resign</a>-->
<!--            </div>-->

	<?php include widget('user.logged-with') ?>

</div>

<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body pt-34">
                <form class="mb-56" id="register-form" onsubmit="return onRegisterFormSubmit()">
                    <input type="hidden" name="session_id" value="<?=login('session_id')?>">
                    <label class="modal-title form-label mb-34 fs-14 gray100"></label>
                    <input type="text" class="form-control smat-input mb-34" id="field" name="field" value="">
                    <div class="d-flex justify-content-end">
<!--                        <button type="button" class="btn btn-lg bg-gray" data-dismiss="modal">--><?//=tr('cancel')?><!--</button>-->
                        <button type="submit" class="btn btn-lg bg-lightgray100 blue text-uppercase roboto"><?=tr('submit')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $$(function() {
        $('#profileModal').on('show.bs.modal', function (e) {
            const button = $(e.relatedTarget); // Button that triggered the modal
            const title = button.data('title');
            const field = button.data('field');
            const value = button.data('value');

            const modal = $(this);
            modal.find('.modal-title').text(title);
            const input = modal.find('.modal-body input#field');
            input.val(value);
            input.attr('name', field);
        })

        $('#profileModal').on('hidden.bs.modal', function (e) {
            console.log(e);
        })
    });


    function onRegisterFormSubmit() {
        apiUserRegister(objectifyForm($('#register-form')), function(res) {
            toast(tr('profile update'), '', tr('profile update success'))
        }, function(res) {
            alertError(res);
        });
        return false;
    }

</script>
