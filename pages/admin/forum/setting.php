<?php
//$cat = null;
//if (in('slug')) {
//    $cat = get_category_by_slug(in('slug'));
//}



?>
<h4>Forum Settings</h4>

<?php if ( forum('cat_ID') ) { ?>
<h1><?=forum('slug')?></h1>
<p>ID: <?=forum('cat_ID')?>, <?=forum('name')?></p>
<p><?=forum('description')?></p>
<p>No of posts: <?=forum('count')?></p>
<?php } ?>


<form action="?" method="post">
    <input type="hidden" name="page" value="admin.forum.setting.submit">
    <input type="hidden" name="cat_ID" value="<?=forum('cat_ID')?>">

    <?php
    if ( empty($cat) ) {
        echo form_input(['label' => 'Slug', 'name' => 'slug', 'value' => forum('slug')]);
    }
    ?>


    <?=form_input(['label' => 'Display Name', 'name' => 'name', 'value' => forum('name')])?>
    <?=form_input(['label' => 'Description', 'name' => 'description', 'value' => forum('description')])?>
    <?=form_input(['label' => 'No of posts in list page', 'name' => NO_OF_POSTS_PER_PAGE, 'value' => forum(NO_OF_POSTS_PER_PAGE)])?>



    <?=generate_select([
        'label' => 'Select list theme',
        'name' => POST_LIST_THEME,
        'options' => generate_options(get_wiget_list(POST_LIST_THEME), forum(POST_LIST_THEME, 'post.list')),
        ])?>


	<?=generate_select([
		'label' => 'Select view theme',
		'name' => POST_VIEW_THEME,
		'options' =>     $_options = generate_options(get_wiget_list(POST_VIEW_THEME), forum(POST_VIEW_THEME, 'post.view')),
	])?>


	<?=generate_select([
		'label' => 'Select view button theme',
		'name' => POST_VIEW_BUTTON_THEME,
		'options' =>     $_options = generate_options(get_wiget_list(POST_VIEW_BUTTON_THEME), forum(POST_VIEW_BUTTON_THEME, 'post.view-button')),
	])?>


	<?=generate_select([
		'label' => 'Select view comment theme',
		'name' => POST_VIEW_COMMENT_THEME,
		'options' =>     $_options = generate_options(get_wiget_list(POST_VIEW_COMMENT_THEME), forum(POST_VIEW_COMMENT_THEME, 'post.view-comment')),
	])?>



    <?=generate_select([
        'label' => 'Select edit theme',
        'name' => POST_EDIT_THEME,
        'options' =>     $_options = generate_options(get_wiget_list(POST_EDIT_THEME), forum(POST_EDIT_THEME, 'post.edit')),
    ])?>


    <div class="form-group form-check">
        <input name="post_list_under_view" value="Y" type="checkbox" class="form-check-input" id="form-list-under-view"

        <?php if ( forum('post_list_under_view') == 'Y' ) echo 'checked'?>
        >
        <label class="form-check-label" for="form-list-under-view">Display post list under post view page.</label>
    </div>

    <div class="form-group form-check">
        <input name="<?=POST_SHOW_LIKE?>" value="Y" type="checkbox" class="form-check-input" id="<?=POST_SHOW_LIKE?>"
        <?php if ( forum(POST_SHOW_LIKE) == 'Y' ) echo 'checked'?> >
        <label class="form-check-label" for="<?=POST_SHOW_LIKE?>">Display Like button on Post view.</label>
    </div>
    <div class="form-group form-check">
        <input name="<?=POST_SHOW_DISLIKE?>" value="Y" type="checkbox" class="form-check-input" id="<?=POST_SHOW_DISLIKE?>"
        <?php if ( forum(POST_SHOW_DISLIKE) == 'Y' ) echo 'checked'?> >
        <label class="form-check-label" for="<?=POST_SHOW_DISLIKE?>">Display Dislike button on Post view.</label>
    </div>
    <div class="form-group form-check">
        <input name="<?=COMMENT_SHOW_LIKE?>" value="Y" type="checkbox" class="form-check-input" id="<?=COMMENT_SHOW_LIKE?>"
        <?php if ( forum(COMMENT_SHOW_LIKE) == 'Y' ) echo 'checked'?> >
        <label class="form-check-label" for="<?=COMMENT_SHOW_LIKE?>">Display Like button on Comment view.</label>
    </div>
    <div class="form-group form-check">
        <input name="<?=COMMENT_SHOW_DISLIKE?>" value="Y" type="checkbox" class="form-check-input" id="<?=COMMENT_SHOW_DISLIKE?>"
        <?php if ( forum(COMMENT_SHOW_DISLIKE) == 'Y' ) echo 'checked'?> >
        <label class="form-check-label" for="<?=COMMENT_SHOW_DISLIKE?>">Display Dislike button on Comment view.</label>
    </div>

    <div class="d-flex  justify-content-between">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a type="submit" class="btn btn-secondary" href="<?=Config::$adminForumList?>">Back</a>
    </div>

</form>