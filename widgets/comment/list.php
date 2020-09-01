<?php
if (!isset($post) && empty($post)) return;
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
<div class="pb-3">
    <?php

    foreach($post['comments'] as $comment){
        include widget('comment.view');
    }

    ?>


</div>