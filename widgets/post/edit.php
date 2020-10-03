<?php
/**
 * @file edit.php
 * @widget-type post_edit_theme
 * @widget-name Vue.js - post edit
 */

$ID = in('ID');
$slug = in('slug');
$post = [];
if ($ID) {
	$post =  post()->postGet(['ID' => $ID, 'post_count' => false]);
	$slug = $post['slug'];
}


?>

<div class="post-edit p-3">

	<form  onsubmit="submitPostEdit(this)" >
		<input type="hidden" name="route" value="post.edit">
		<input type="hidden" name="slug" value="<?=$slug?>">
		<input type="hidden" name="ID" value="<?=$post['ID']??''?>">

		<div class="mt-3">
			<label for="post-create-title">Title</label>
			<input type="text" class="form-input" name="post_title" id="post-create-title" aria-describedby="Title" placeholder="<?=tr(inputTitle)?>" value="<?=$post['post_title']??''?>">
		</div>

		<div class="mt-2">
			<label for="post-create-content">Content</label>
			<textarea class="form-input" name="post_content" id="post-create-content" aria-describedby="Content" placeholder="<?=tr(inputContent)?>"><?=$post['post_content']??''?></textarea>
		</div>

		<div class="relative size-md overflow-hidden">
			<input class="absolute fs-xl opacity-01" type="file" name="userfile"
			       @change="onFileChange($event.target.name, $event.target.files);"
			>
			<i class="fa fa-camera fs-lg p-1" role="button"></i>
		</div>

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

	<div class="mt-3">
		<button class="btn btn-primary mr-3" type="submit" form="post-form">Submit</button>
		<a class="btn btn-secondary"  href="<?='?page=post.list&slug=' . $slug?>">Cancel</a>
	</div>

</div>


