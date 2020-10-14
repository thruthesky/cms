<?php
include '../../../../wp-load.php';

$re = comment()->get_ancestor_tokens_for_push_notifications(128);
print_r($re);


