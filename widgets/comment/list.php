<?php
if (!isset($post) && empty($post)) return;

$viewTemplate = file_get_contents(__DIR__ . '/view-template.html');

?>
<style>
    [data-depth='1'] {}
    [data-depth='2'] { margin-left: 1rem; }
    [data-depth='3'] { margin-left: 2rem; }
    [data-depth='4'] { margin-left: 3rem; }
    [data-depth='5'] { margin-left: 3.5rem; }
    [data-depth='6'] { margin-left: 4rem; }
    [data-depth='7'] { margin-left: 4.5rem; }
    [data-depth='8'] { margin-left: 5rem; }
    [data-depth='9'] { margin-left: 5.5rem; }
    [data-depth='10'] { margin-left: 6rem; }
</style>
<?php if (!$post['comments'] || empty($post['comments'])) { ?>
    <div id="no-comment-yet<?=$post['ID']?>" class="pt-40 text-center roboto">
        <img class="w-100" src="<?=theme_url()?>/tmp/no_posts.png">
        <div class="fs-20 mb-12"><?=tr(NO_COMMENTS_YET_1)?></div>
        <div class="fs-14 mb-20"><?=tr(NO_COMMENTS_YET_2)?></div>
        <div class="fs-12 fw-medium mb-58 blue" onclick="appendCommendBoxToPost()"><?=tr(NO_COMMENTS_YET_3)?></div>
    </div>
<?php } ?>
<div id="comment-list" class="pl-20 pr-10 pb-40">
    <?php
    foreach($post['comments'] as $comment){
        include widget('comment.view', ['viewTemplate' => $viewTemplate]);
    }

//    dog(addslashes(str_replace("\n", ' ', $viewTemplate)));
    ?>


</div>

<script>
    $$(function() {
        commentList.init({
            mount: '#comment-list',
            comments: <?=json_encode($post['comments']);?>,
            template: `<?=addslashes(str_replace("\n", " ", $viewTemplate))?>`,
       });
        commentList.render();
   })
</script>