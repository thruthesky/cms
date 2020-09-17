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

<!--    <div class="form-group">-->
<!--        <label for="form-name">Display Name</label>-->
<!--        <input type="text" name="name" class="form-control" id="form-name" value="--><?//=$cat->name?><!--">-->
<!--    </div>-->

<!--    <div class="form-group">-->
<!--        <label for="form-description">Description</label>-->
<!--        <input type="text" name="description" class="form-control" id="form-description" value="--><?//=$cat->description?><!--">-->
<!--    </div>-->




    <?=form_input(['label' => 'Display Name', 'name' => 'name', 'value' => forum('name')])?>
    <?=form_input(['label' => 'Description', 'name' => 'description', 'value' => forum('description')])?>
    <?=form_input(['label' => 'No of posts in list page', 'name' => NO_OF_POSTS_PER_PAGE, 'value' => forum(NO_OF_POSTS_PER_PAGE)])?>



    <?=generate_select([
        'label' => 'Select list theme',
        'name' => 'post_list_theme',
        'options' => generate_options(get_wiget_list('post_list_theme'), forum(POST_LIST_THEME, 'post.list')),
        ])?>


    <?=generate_select([
        'label' => 'Select view theme',
        'name' => 'post_view_theme',
        'options' =>     $_options = generate_options(get_wiget_list('post_view_theme'), forum(POST_VIEW_THEME, 'post.view')),
    ])?>



    <?=generate_select([
        'label' => 'Select edit theme',
        'name' => 'post_edit_theme',
        'options' =>     $_options = generate_options(get_wiget_list('post_edit_theme'), forum(POST_EDIT_THEME, 'post.edit')),
    ])?>


    <div class="form-group form-check">
        <input name="post_list_under_view" value="Y" type="checkbox" class="form-check-input" id="form-list-under-view"

        <?php if ( forum('post_list_under_view') == 'Y' ) echo 'checked'?>
        >
        <label class="form-check-label" for="form-list-under-view">Display post list under post view page.</label>
    </div>

    <div class="form-group form-check">
        <input name="post_show_vote" value="Y" type="checkbox" class="form-check-input" id="post-show-vote"

        <?php if ( forum(POST_SHOW_POST) == 'Y' ) echo 'checked'?>
        >
        <label class="form-check-label" for="form-list-under-view">Display post like and dislike button .</label>
    </div>


    <button type="submit" class="btn btn-primary">Submit</button>
    <a type="submit" class="btn btn-secondary" href="<?=Config::$adminForumList?>">Back</a>
</form>