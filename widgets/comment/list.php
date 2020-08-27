<?php
if (!isset($post) && empty($post)) return;
?>
<div class="pb-3">
    <?php
    foreach($post['comments'] as $comment){
        include widget('comment.view');
    }
    ?>
</div>