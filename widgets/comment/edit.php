<?php
if (!isset($post) && empty($post)) return;


?>


<?php
//echo $apiComment->commentInputBox($post, null, null);

include widget('comment.input-box');
?>
