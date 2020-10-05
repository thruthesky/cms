<?php
/**
 * @file list.php
 */
/*
 * [2] => WP_Term Object
        (
            [term_id] => 2
            [name] => This is category name.
            [slug] => newcategory
            [term_group] => 0
            [term_taxonomy_id] => 2
            [taxonomy] => category
            [description] => oo
            [parent] => 0
            [count] => 6
            [filter] => raw
            [cat_ID] => 2
            [category_count] => 0
            [category_description] => oo
            [cat_name] => This is category name.
            [category_nicename] => newcategory
            [category_parent] => 0
        )
 */
?>
<h1>Admin Forum List</h1>
<a class="btn btn-primary" href="/?page=admin.forum.setting&mode=create">Add</a>
<?php
$cats = get_categories(['hide_empty' => false]);
foreach ($cats as $cat) {
    ?>
<div class="row">
    <div class="col"><?=$cat->slug?></div>
    <div class="col"><?=$cat->name?></div>
    <div class="col"><?=$cat->count?></div>
    <div class="col buttons">
        <a href="/?page=admin.forum.setting&slug=<?=$cat->slug?>">Setting</a>
        <a href="/?page=post.list&slug=<?=$cat->slug?>">Open</a>
    </div>
</div>
<?php

}