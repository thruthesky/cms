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
    <input type="hidden" name="slug" value="<?=$slug?>">
    <div class="form-group">
        <label for="post-create-title">Title</label>
        <input type="email" class="form-control" id="post-create-title" aria-describedby="Title" placeholder="Enter title">
    </div>

    <div class="form-group">
        <label for="post-create-content">Content</label>
        <input type="email" class="form-control" id="post-create-content" aria-describedby="Content" placeholder="Enter content">
    </div>


    <button type="submit" class="btn btn-primary">Submit</button>
</form>

