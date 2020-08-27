<?php
if (!isset($post) && empty($post)) return;
?>
<style>
    [data-depth='1'] {}
    [data-depth='2'] { margin-left: 1rem; }
    [data-depth='3'] { margin-left: 2rem; }

</style>
<div class="pb-3">
    <?php
    foreach($post['comments'] as $comment){
        include widget('comment.view');
    }
    ?>
</div>