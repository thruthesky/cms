<?php
$post =  $apiPost->postGet(['ID' => url_to_postid(get_page_uri())]);
$slug = $post['slug'];

//dog($post);
?>


<script>
    function onCommentDelete(comment_ID) {

        var re = confirm('Are you sure you want to delete this comment?');

        if (!re) return;
        var data = {};
        data['session_id'] = getUserSessionId();
        data['route'] = "comment.delete";
        data['comment_ID'] = comment_ID;

        console.log(data);
        $.ajax( {
            method: 'GET',
            url: apiUrl,
            data: data
        } )
            .done(function(re) {
                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    console.log('re', re);
                    move("<?=$post['guid']?>");
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
    }

    function onPostDelete(ID) {

        var re = confirm('Are you sure you want to delete this post?');

        if (!re) return;
        var data = {};
        data['session_id'] = getUserSessionId();
        data['route'] = "post.delete";
        data['ID'] = ID;

        console.log(data);
        $.ajax( {
            method: 'GET',
            url: apiUrl,
            data: data
        } )
            .done(function(re) {
                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    console.log('re', re);
                    move("/?page=post.list&slug=<?=$post['slug']?>");
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
    }

    function onCommentEditFormSubmit(form) {
        var data = objectifyForm(form);
        data['session_id'] = getUserSessionId();

        var files = $(form).parent().children('.files');

        if (files.children('.' + uploadedFileClass).length) {
            var file_ids = [];
            $.each(files.children('.' + uploadedFileClass),function(index, item) {
                file_ids.push( $(item).data('file-id'));
            });
            data['files'] = file_ids.join();
        }

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
                    console.log(re);
                    var commentBox = $('#comment' + data['comment_ID']);
                    if (commentBox.length) { // Update
                        commentBox.replaceWith(onCommentCreateOrUpdateApplyDepth(re['html'], commentBox));
                        $(form).parent().remove();
                    } else if ( re['comment_parent'] === "0" ) {
                        $('#newcomment' + data['comment_post_ID']).after(re['html']);
                        $(form).find('textarea').val('');
                        $(form).parent().find('files').empty();
                    } else {
                        var parent_comment = $('#comment' + re['comment_parent']);
                        parent_comment.after(onCommentCreateOrUpdateApplyDepth(re['html'], parent_comment, 1));
                        $(form).parent().remove();

                        // TODO: it's not working.
                        scrollIntoView('#comment' + re['comment_ID']);
                    }
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }

    function onCommentCreateOrUpdateApplyDepth(html, parentElement, incrementBy = 0) {
        var depth = parentElement.find('.display').data('depth') + incrementBy;
        return html.replace('data-depth="1"', 'data-depth="' + depth + '"');
    }


    function addCommentEditForm(comment_ID, comment_parent) {

        var fcp= $("[data-form-comment-parent=" + comment_parent + "]").length;
        // console.log(fcp);
        if(fcp) return;

        var data = {route: 'comment.inputBox', comment_ID: comment_ID, comment_parent: comment_parent};
        // console.log(data);
        $.ajax( {
            method: 'POST',
            url: apiUrl,
            data: data
        } )
            .done(function(re) {
                // console.log('re', re);
                var cmt;
                if ( comment_ID ) {
                    cmt = $('#comment' + comment_ID);
                    cmt.find('.display').after(re);
                    cmt.find('.display').hide();
                } else {
                    cmt = $('#comment' + comment_parent);
                    cmt.find('.display').after(re);
                }
                cmt.find('.input-box textarea').focus();
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }



    function onCommentEditText($this) {
        $($this).attr('rows', 4);
    }

    function onClickLike(ID){
        console.log('like');

        var data = {};
        data['session_id'] = getUserSessionId();
        data['route'] = "post.like";
        data['ID'] = ID;
        $.ajax( {
            method: 'GET',
            url: apiUrl,
            data: data
        } )
            .done(function(re) {
                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    console.log('re', re);
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
    }


    function onClickDislike(ID){

        console.log('dislike');
    }

</script>



<div class="p-3">
    <a class="btn btn-primary mr-3" href="/?page=post.list&slug=<?=$slug?>">Back</a>
    <a class="btn btn-secondary mr-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
</div>

<div class="container pb-3">
    <div class="post card mb-3">
        <div class="card-body mb-3">
            <div class="row no-gutters">
                <div class="mr-3">
                    <?php if (!empty($post['author_photo_url'])) {
                        echo "<img class='userPhoto circle' style='width: 50px' src='$post[author_photo_url]' alt='user photo'>";
                    } else {
                        echo "<img class='userPhoto circle' style='width: 50px' src='/wp-content/themes/cms/img/anonymous/anonymous.jpg' alt='user photo'>";
                    }  ?>
                </div>
                <div class="col">
                    <div><?=$post['author_name']?></div>
                    <span>Date: <?=$post['short_date_time']?></span>
                    <span>View: <?=$post['view_count']?></span>
                </div>
            </div>
            <div class="card-title fs-lg"><?=$post['post_title']?></div>
            <p class="card-text"><?=$post['post_content']?></p>
            <div class="post-view-files row py-3">

                <script>
                    var files = <?=json_encode($post['files']);?>;
                    $$(function() {
                        for ( var file of files ) {
                            $('.post-view-files').append(getUploadedFileHtml(file, {extraClasses: 'col-4 col-sm-3'}));
                        }
                    });
                </script>

            </div>
            <div class="mb-3">
                <button class="btn btn-primary mr-3" onclick="onClickLike(<?=$post['ID']?>)">Like</button>
                <button class="btn btn-primary mr-3" onclick="onClickDislike(<?=$post['ID']?>)">Dislike</button>
                <?php
                if($post['post_author'] == userId()) { ?>
                    <a class="btn btn-primary mr-3" href="/?page=post.edit&ID=<?=$post['ID']?>">Edit</a>
                    <button class="btn btn-primary mr-3" onclick="onPostDelete(<?=$post['ID']?>)">Delete</button>
                <?php } ?>
            </div>
            <?php
            include widget('comment.input-box');
            ?>
            <div id="newcomment<?=$post['ID']?>"></div>
            <?php
            include widget('comment.list');
            ?>
        </div>
    </div>

</div>