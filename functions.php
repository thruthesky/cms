<?php

$_theme_path = 'wp-content/themes/cms';
function theme_path() {
    global $_theme_path;
    echo $_theme_path;
}