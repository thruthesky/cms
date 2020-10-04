<?php
/**
 * @widget-type post_view_theme
 * @widget-name Vue.js - Post view
 */
/**
 * Get post of the page.
 */
$_post = get_page_post();
if ( empty($_post) ) return;
xlog($_post);
$post  = post()->postGet( [
	'ID' => $_post->ID
] );
$slug  = $post['slug'];
?>


<section class="p-3">

    <h3><?= forum( 'name' ) ?></h3>
    <div class="buttons flex flex-end">
        <a href="/?page=post.list&slug=<?= $slug ?>">Back</a>
        <a href="/?page=post.edit&slug=<?= $slug ?>">Create</a>
    </div>

    <div>
        <h1 class="title"><?= $post['post_title'] ?></h1>
        <div class="flex justify-content-between">
            <div class="flex">
                <div class="size-lg circle">
                    <img class='w-100' src="<?= getPostProfilePhotoUrl( $post ) ?>" alt='user photo'>";
                </div>
                <div class="ml-3">
                    <small>
                        <span class=""><?= $post['author_name'] ?></span>
                        <span class="pl-2">No: <?= $post['ID'] ?></span>
                        <span class="pl-2">Date: <?= $post['short_date_time'] ?></span>
                        <span class="pl-2">View: <?= $post['view_count'] ?></span>
                    </small>
                    <div class="block"><?= forum( 'name' ) ?></div>
                </div>
            </div>
            <div class="pt-10 pr-20 pl-10 pointer"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></div>
        </div>

	    <?php include widget(forum(POST_VIEW_BUTTON_THEME, 'post/view-button')); ?>
        <?php if ( Config::$showUploadedFilesOnTop )
			include widget( 'files/display-uploaded-files', [ 'post' => $post ] ) ?>
        <article class="mt-3 p-3 bg-light"><?= $post['post_content'] ?></article>
		<?php if ( Config::$showUploadedFilesAtBottom )
			include widget( 'files/display-uploaded-files', [ 'post' => $post ] ) ?>
	    <?php include widget(forum(POST_VIEW_BUTTON_THEME, 'post/view-button')); ?>
	    <?php include widget(forum(POST_VIEW_COMMENT_THEME, 'post/view-comment')); ?>

    </div>

</section>