

<?php

$options = get_page_options();

?>

<img class="w-100" src="<?=THEME_URL?>/img/error/error.jpg">

<h1 class="display-5"><?=$options['title']?></h1>
<hr>
<p class="lead"><?=$options['body']?></p>

