<?php
/**
 * @file edit.php
 * @widget-type post_edit_theme
 * @widget-name Default post edit
 */

$ID = in('ID');
$slug = in('slug');

if ($ID) {
    $post =  post()->postGet(['ID' => $ID, 'post_count' => false]);
}
//
//dog($post);
?>


<div class="post-edit container py-3">

    <form class="post-form" data-bind="submit: submitPostEdit">
        <input type="hidden" name="route" value="post.edit">
        <input type="hidden" name="slug" value="<?=$slug?>">

        <input type="hidden" name="ID" value="<?=$post['ID']?>">

        <div class="form-group">
            <label for="post-create-title">Title</label>
            <input type="text" class="form-control" name="post_title" id="post-create-title" aria-describedby="Title" placeholder="Enter title" value="<?=$post['post_title']?>">
        </div>

        <div class="form-group">
            <label for="post-create-content">Content</label>
            <textarea class="form-control" name="post_content" id="post-create-content" aria-describedby="Content" placeholder="Enter content"><?=$post['post_content']?></textarea>
        </div>
<!--        <div class="upload-photo-box">-->
<!--            <input type="file" name="file"-->
<!--                   onchange="onChangeFile(this, {append: $('.files'), deleteButton: true, extraClasses: 'col-4 col-sm-3', progress: $(this).parents('.post-edit').find('.progress')})">-->
<!--            <i role="button" class="fa fa-camera"></i>-->
<!--        </div>-->

        <div class="position-relative icon-size overflow-hidden">
            <input class="position-absolute fs-xxxl opacity-01" type="file" name="file"
                   data-bind="event: {change: function() {changeFilePostEdit($element);} }">
            <i class="fa fa-camera fs-lg" role="button"></i>
        </div>


        <div class="progress mb-3" style="display: none">
            <div class="progress-bar progress-bar-striped" role="progressbar"  aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>


    <div class="files edit row" data-bind="foreach: filesInEdit">
        <div class="col-4 col-sm-3">
            <div class="position-relative">
                <i class='fa fa-trash position-absolute top right fs-xl' role='button' data-bind="click: $root.deleteFile" ></i>
                <img class="w-100" src="" data-bind="attr: {src: thumbnail_url}">
            </div>
        </div>
    </div>


    <script>
        const files_in_edit = <?=json_encode($post['files'])?>;
    </script>
</div>
