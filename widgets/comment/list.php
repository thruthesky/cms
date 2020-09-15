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
<div id="comment-list" class="pb-3">
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
//        commentList.render();
   })
</script>