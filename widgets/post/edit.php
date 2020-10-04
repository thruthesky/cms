<?php
/**
 * @file edit.php
 * @widget-type post_edit_theme
 * @widget-name Vue.js - post edit
 */

$ID   = in( 'ID' );
$slug = in( 'slug' );


if ( ! $slug && ! $ID ) {
	return include page( 'error.display', [ 'code' => ERROR_WRONG_SLUG ] );
}
if ( ! isMobile() ) {
	addRichTextEditor( '#post-create-content' );
}


?>
<script>
    $$(function () {
        vm.onPostEdit({
            route: 'post.edit',
            slug: '<?=$slug?>',
            ID: '<?=$ID ?? '' ?>',
        });
    })
    function insertImageIntoEditor(file) {
        file = app.getProxyData(file);
        console.log('insertImageIntoEditor(): ', file);
        const html = '<img id="uploaded-file'+file.ID+'" class="mw-100" src="'+file.url+'">';
        tinymce.activeEditor.insertContent(html);
    }
</script>

<div class="post-edit p-3">

    <div class="center" v-show="form.loading">
        <div class="spinner size-sm"></div>
        <div class="ml-3"><?= tr( waitWhileLoading ) ?></div>
    </div>

    <form @submit.prevent="onPostEditFormSubmit" v-show="!form.loading">
        <div class="mt-3">
            <label for="post-create-title">Title</label>
            <input type="text" class="form-input" id="post-create-title" name="post_title"
                   value="" v-model="form.post_title" aria-describedby="Title"
                   placeholder="<?= tr( inputTitle ) ?>">
        </div>

        <div class="mt-2">
            <label for="post-create-content">Content</label>
			<?php if ( isMobile() ) { ?>
                <textarea class="form-input h-lg fs-md" name="post_content"  id="post-create-content"
                          v-model="form.post_content" aria-describedby="Content"
                          placeholder="<?= tr( inputContent ) ?>"></textarea>
			<?php } else { ?>
				<?php
                /// #post-content
                /// If the user is using desktop browser, then we use tinymce. And Vue binding is not working with tinymce.
				$post_content = '';
				if ( $ID ) {
					$post         = post()->postGet( [ 'ID' => $ID, 'post_count' => false ] );
					$post_content = $post['post_content'];
				}
				?>
                <textarea name="post_content" id="post-create-content" aria-describedby="Content"
                          placeholder="<?= tr( inputContent ) ?>"><?= $post_content ?></textarea>
			<?php } ?>
        </div>

        <section class="content-between">

            <div class="relative size-md overflow-hidden pointer">
                <input class="absolute fs-xl opacity-01 pointer" type="file" name="userfile"
                       @change="onFileChange($event.target.name, $event.target.files);">
                <img class="size-md p-1" src="<?= svg( 'camera' ) ?>">
            </div>


            <div class="v-center">
                <a class="fs-xs" href="/?page=post.list&slug=<?= $slug ?>"
                   onclick="return confirm('<?= tr( doYouWantToCancelPosting ) ?>');">Cancel</a>
                <!--                <button class="btn-primary ml-2 px-4" type="submit">Submit</button>-->
                <app-submit-button :cssClass="'mt-0 ml-2'" :button="'Submit'"
                                   :loading="'Loading...'"></app-submit-button>
            </div>

        </section>

    </form>


    <div class="progress-bar" v-show="uploadPercentage">
        <div class="percentage-bar" :style="{width: uploadPercentage + '%'}"></div>
    </div>


    <div class="uploaded-files wrap p-1 bg-lighter" v-if="form.files">
        <div class="uploaded-file relative w-33 w-sm-25 w-md-20" v-for="file in form.files">
            <img class="block p-1 w-100" :src="file.thumbnail_url">
            <div class="absolute top-sm right-sm p-1 size-md circle bg-light pointer opacity-75" @click="onClickDeleteFile(file)">
                <img class="size-sm" src="<?= svg( 'close', 'grey' ) ?>"
                     v-if="!file.deleting">
                <div class="spinner size-sm" v-if="file.deleting"></div>
            </div>
            <div class="absolute left-sm bottom-sm size-md circle bg-white pointer opacity-50" @click="callback('insertImageIntoEditor', file)">
                <img class="size-md" src="<?=svg('arrow-up', 'grey')?>">
            </div>
        </div>
    </div>

</div>


