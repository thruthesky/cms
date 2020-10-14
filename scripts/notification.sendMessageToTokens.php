<?php
include '../../../../wp-load.php';

$tokens = comment()->get_ancestor_tokens_for_push_notifications(128);

$re = sendMessageToTokens($tokens, 'title', 'body', 'url', 'iconUrl');

print_r($re);



