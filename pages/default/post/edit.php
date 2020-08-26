<?php
$slug = in('slug');
?>


<script>
    function onPostEditFormSubmit(form) {


        $.ajax( {
            method: 'POST',
            url: apiUrl,
            data: objectifyForm(form)
        } )
            .done(function(re) {
                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    console.log('re', re);
                    move('?page=post.list&slig=<?=$slug?>');
                }
            })
            .fail(function() {
                alert( "Server error" );
            });



        return false;
    }
</script>

<form onsubmit="return onPostEditFormSubmit(this);">
    <input type="hidden" name="route" value="post.edit">
    <input type="hidden" name="slug" value="<?=$slug?>">
    <input type="hidden" name="session_id" value="<?=sessionId()?>">
    <div class="form-group">
        <label for="post-create-title">Title</label>
        <input type="text" class="form-control" name="post_title" id="post-create-title" aria-describedby="Title" placeholder="Enter title">
    </div>

    <div class="form-group">
        <label for="post-create-content">Content</label>
        <textarea class="form-control" name="post_content" id="post-create-content" aria-describedby="Content" placeholder="Enter content"></textarea>
    </div>


    <button type="submit" class="btn btn-primary">Submit</button>
</form>

