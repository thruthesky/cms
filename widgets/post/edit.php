<?php
/**
 * @file edit.php
 * @widget-type post_edit_theme
 * @widget-name Vue.js - post edit
 */

$ID = in('ID');
$slug = in('slug');
$post = [];
//if ($ID) {
//	$post =  post()->postGet(['ID' => $ID, 'post_count' => false]);
//	$slug = $post['slug'];
//}

if ( !$slug && !$ID ) {
    return include page('error.display', ['code' => ERROR_WRONG_SLUG]);
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
</script>

<div class="post-edit p-3">

	<form  @submit.prevent="onPostEditFormSubmit" >
		<div class="mt-3">
			<label for="post-create-title">Title</label>
			<input type="text" class="form-input" id="post-create-title" name="post_title" value="<?=$post['post_title']??''?>" v-model="form.post_title" aria-describedby="Title" placeholder="<?=tr(inputTitle)?>">
		</div>

		<div class="mt-2">
			<label for="post-create-content">Content</label>
			<textarea class="form-input h-lg fs-md" name="post_content" id="post-create-content"  v-model="form.post_content" aria-describedby="Content" placeholder="<?=tr(inputContent)?>"></textarea>
		</div>

        {{ uploadPercentage }}
        <div class="progress-bar" v-if="uploadPercentage">
            <div class="percentage-bar" :style="{width: uploadPercentage + '%'}"></div>
        </div>

        <section class="content-between">

            <div class="relative size-md overflow-hidden">
                <input class="absolute fs-xl opacity-01" type="file" name="userfile"
                       @change="onFileChange($event.target.name, $event.target.files);"
                >
                <i class="fa fa-camera fs-lg p-1" role="button"></i>
            </div>


            <div class="v-center">
                <a class="fs-xs"  href="/?page=post.list&slug=<?=$slug?>" onclick="return confirm('<?=tr(doYouWantToCancelPosting)?>');">Cancel</a>
                <button class="btn-primary ml-2 px-4" type="submit">Submit</button>
            </div>

        </section>

    </form>

	<div class="uploaded-files">
		<?php if(isset($post['files'])) foreach ($post['files'] as $file) { ?>
			<div id="file<?=$file['ID']?>" data-file-id="#file<?=$file['ID']?>" class="uploaded-file relative">
				<img class="w-100" src="<?=$file['thumbnail_url']?>">
				<i role="button" class="fa fa-trash absolute top right" onclick="onClickDeleteFile(<?=$file['ID']?>)"></i>
			</div>
		<?php } ?>
	</div>
	<div class="progress mb-3" style="display: none">
		<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
	</div>

</div>


