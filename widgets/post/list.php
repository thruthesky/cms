<?php
$slug = in('slug');
$posts =  $apiPost->postSearch(['slug' => $slug]);

//dog($posts)
?>
<a class="btn btn-secondary m-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
<div class="container pb-3">
    <?php
    foreach($posts as $post){
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div>
                        <?php if (!empty($post['author_photo_url'])) {
                            echo "<img class='userPhoto circle' style='width: 50px' src='$post[author_photo_url]' alt='user photo'>";
                        } else {
                            echo "<img class='userPhoto circle' style='width: 50px' src='/wp-content/themes/cms/img/anonymous/anonymous.jpg' alt='user photo'>";
                        }  ?>
                    </div>
                    <div class="col">
                        <div>
                            <?php if (!empty($post['files'])) echo "<i class='fa fa-images'></i>" ?>

                            <span><?=$post['author_name']?></span>
                            <span>Date: <?=$post['short_date_time']?></span>
                            <span>View: <?=$post['view']?? 1?></span>
                        </div>
                        <a class="card-title fs-lg" href="<?=$post['guid']?>"><?=$post['post_title']?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>