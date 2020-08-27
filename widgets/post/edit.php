<?php
$ID = in('ID');
$slug = in('slug');

$post = [];
if ($ID) {
    $post =  $apiPost->postGet(['ID' => $ID]);


}
?>


<script>
    function onPostEditFormSubmit(form) {

        var data = objectifyForm(form);

        data['session_id'] = getUserSessionId();


        console.log(data);

        $.ajax( {
            method: 'POST',
            url: apiUrl,
            data: data
        } )
            .done(function(re) {
                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    console.log('re', re);
                    var slug = "<?=$slug?>";
                    if (!slug) slug= re['slug'];
                    move("?page=post.list&slug=" + slug);
                }
            })
            .fail(function() {
                alert( "Server error" );
            });



        return false;
    }
</script>

<div class="container py-3">

<form onsubmit="return onPostEditFormSubmit(this);">
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


    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>