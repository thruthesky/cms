<?php
$cat = get_category_by_slug(in('slug'));




?>
<h4>Forum Settings</h4>
<h1><?=$cat->slug?></h1>
<p>ID: <?=$cat->cat_ID?>, <?=$cat->name?></p>
<p><?=$cat->description?></p>
<p>No of posts: <?=$cat->count?></p>


<form action="?" method="post">
    <input type="hidden" name="page" value="admin.forum.setting.submit">
    <input type="hidden" name="cat_ID" value="<?=$cat->cat_ID?>">

    <div class="form-group">
        <label for="form-name">Name</label>
        <input type="text" name="name" class="form-control" id="form-name" value="<?=$cat->name?>">
    </div>

    <div class="form-group">
        <label for="form-description">Description</label>
        <input type="text" name="description" class="form-control" id="form-description" value="<?=$cat->description?>">
    </div>



    <?=generate_select([
        'description' => 'List Theme',
        'default_select' => 'Select list theme',
        'name' => 'post_list_theme',
        'options' =>     $_options = generate_options(get_wiget_list('post_list_theme'), get_term_meta($cat->cat_ID, 'post_list_theme', true)),
        ])?>


    <?=generate_select([
        'description' => 'View Theme',
        'default_select' => 'Select view theme',
        'name' => 'post_view_theme',
        'options' =>     $_options = generate_options(get_wiget_list('post_view_theme'), get_term_meta($cat->cat_ID, 'post_view_theme', true)),
    ])?>



    <?=generate_select([
        'description' => 'Edit Theme',
        'default_select' => 'Select edit theme',
        'name' => 'post_edit_theme',
        'options' =>     $_options = generate_options(get_wiget_list('post_edit_theme'), get_term_meta($cat->cat_ID, 'post_edit_theme', true)),
    ])?>


    <div class="form-group form-check">
        <input name="post_list_under_view" value="Y" type="checkbox" class="form-check-input" id="form-list-under-view"

        <?php if ( get_term_meta($cat->cat_ID, 'post_list_under_view', true) == 'Y' ) echo 'checked'?>
        >
        <label class="form-check-label" for="form-list-under-view">Display post list under post view page.</label>
    </div>


    <button type="submit" class="btn btn-primary">Submit</button>
</form>