

<?php

$options = get_page_options();

?>

<div class="jumbotron">
    <h1 class="display-4"><?=$options['title']?></h1>
    <p class="lead"><?=$options['body']?></p>
    <hr class="my-4">
    <p>If the problem persists, please contact admin.</p>
    <p class="lead">
        <a class="btn btn-primary btn-lg" href="#" role="button">Contact Admin</a>
    </p>
</div>

<img class="w-100" src="<?=THEME_URL?>/img/error/error.jpg">