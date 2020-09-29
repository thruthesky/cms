<?php
/**
 * @file view.php
 * @widget-type post_view_theme
 * @widget-name Photo post view
 */


$post =  $apiPost->postGet([
        'ID' => url_to_postid(get_page_uri()),
//        'session_id' => sessionId() // @bug. It produce - invalid session id.
]);
$slug = $post['slug'];
?>
<h1>photo view</h1>
<script>
    function onPostDelete(ID) {

        const re = confirm('Are you sure you want to delete this post?');

        if (!re) return;
        const data = {};
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




    function onClickLike(ID, choice , route = 'post'){
        console.log('choice:' + choice);
        let data = {};
        data['session_id'] = getUserSessionId();
        data['route'] = route + ".vote";
        data['ID'] = ID;
        data['choice'] = choice;
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
                    console.log(re);
                    let like = re['user_vote'] === "like"? 'Liked':'Like';
                    if (re['like']!=="0") {
                       like = re['like'] + " " + like;
                    }
                    let dislike = re['user_vote'] === "dislike"? 'Disliked':'Dislike';
                    if (re['dislike']!=="0") {
                        dislike = re['dislike'] + " " + dislike;
                    }
                    $('#like' + re['ID']).html(like);
                    $('#dislike' + re['ID']).html(dislike);
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
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
                <div class="circle wh42x42 overflow-hidden mr-3">
                    <img class='mw-100' src="<?=!empty($post['author_photo_url']) ? $post['author_photo_url']: ANONYMOUS_PROFILE_PHOTO?>" alt='user photo'>";
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
                    $$(function() {
                    attachUploadedFilesTo($('.post-view-files'), <?=json_encode($post['files']);?>, {extraClasses: 'col-4 col-sm-3'});
                    });
                </script>

            </div>
            <div class="mb-3">
                <button id="like<?=$post['ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$post['ID']?>, 'like')"><?=isset($post['like']) && $post['like']!=="0" ?$post['like']: '' ?> <?=$post['user_vote']== 'like'?'Liked':'Like'?></button>
                <button id="dislike<?=$post['ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$post['ID']?>, 'dislike')"><?=isset($post['dislike'])&&$post['dislike']!=="0" ?$post['dislike']: '' ?> <?=$post['user_vote']== 'dislike'?'Disliked':'Dislike'?></button>
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