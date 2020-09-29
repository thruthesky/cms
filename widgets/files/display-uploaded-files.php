<?php
/**
 *
 */
/**
 * $post - is the post.
 */
?>
<div class="post-view-files first-image-w-100 d-flex flex-wrap">
	<?php foreach ($post['files'] as $file) {

	    if ( Config::$hidePhotosInContent ) {
	        $id = get_uploaded_file_id($file['ID']);
	        if ( strpos($post['post_content'], $id) !== false ) {
		        continue;
	        }
        }

	    ?>
		<div class="mw-33">
			<img class="w-100 border-1-solid" src="<?=$file['thumbnail_url']?>">
		</div>
	<?php } ?>
</div>
