<?php
global $slug;

addRichTextEditor('#post-create-content');
?>

<script>
    function onFileUploadSuccess(res) {
        console.log('onFileUploadSuccess', res);
        const html = '<img id="uploaded-file'+res.ID+'" class="mw-100" src="'+res.url+'">';
        tinymce.activeEditor.insertContent(html);
        console.log('res.url', res.url);
        console.log(html);
    }
</script>

<div class="post-edit container py-3">

	<form id="post-form" onsubmit="return submitPostEdit(this)" >
		<input type="hidden" name="route" value="post.edit">
		<input type="hidden" name="slug" value="<?=$slug?>">

		<input type="hidden" name="ID" value="<?=$post['ID']??''?>">

		<div class="form-group">
			<label for="post-create-title">Title</label>
			<input type="text" class="form-control" name="post_title" id="post-create-title" aria-describedby="Title" placeholder="Enter title" value="<?=$post['post_title']??''?>">
		</div>

		<div class="form-group">
			<label for="post-create-content">Content</label>
			<textarea class="form-control" name="post_content" id="post-create-content" aria-describedby="Content" placeholder="Enter content"><?=$post['post_content']??''?></textarea>
		</div>

		<div class="position-relative icon-size overflow-hidden">
			<input class="position-absolute fs-xxxl opacity-01" type="file" name="file"
			       onchange="onChangeFile(this, {append: $('.files'), extraClasses: 'col-4 col-sm-3', deleteButton: true, progress: $(this).parents('.post-edit').find('.progress'), success: onFileUploadSuccess})">
			<i class="fa fa-camera fs-xxl" role="button"></i>
		</div>

	</form>

	<div class="files edit row">
		<?php if(isset($post['files'])) foreach ($post['files'] as $file) { ?>
			<div id="file<?=$file['ID']?>" data-file-id="#file<?=$file['ID']?>" class="uploaded-file position-relative d-inline-block col-4 col-sm-3">
				<img class="w-100" src="<?=$file['thumbnail_url']?>">
				<i role="button" class="fa fa-trash position-absolute top right" onclick="onClickDeleteFile(<?=$file['ID']?>)"></i>
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
