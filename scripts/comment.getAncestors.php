<?php
include '../../../../wp-load.php';

$re = comment()->getAncestors(126);
print_r($re);


