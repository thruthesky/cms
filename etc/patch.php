<?php


function patch_javascript_into_output($output) {

    global $__included_files;

	$__included_files = array_unique($__included_files);



    $v = Config::$appVersion;

    $url = THEME_URL;
    $scripts = '';

    $path_url = THEME_PATH . '/pages/'. Config::$domain . '/first.js';
    if ( file_exists($path_url)) {
        $path_url = $url . '/pages/'. Config::$domain . '/first.js';
        $scripts = <<<EOH
<script src="$path_url?v=$v"></script>

EOH;
    }

    foreach( $__included_files as $file ) {
        $path = str_replace(".php", ".js", $file);
        $arr = explode('/wp-content/', $path);
        $path_url = '/wp-content/' . $arr[1];
        if ( file_exists($path) ) $scripts .= <<<EOH
<script src="$path_url?v=$v"></script>

EOH;
    }

    $path_url = THEME_PATH . '/pages/'. Config::$domain . '/init.js';
    if ( file_exists($path_url)) {
        $path_url = $url . '/pages/'. Config::$domain . '/init.js';
        $scripts .= <<<EOH
<script src="$path_url?v=$v"></script>

EOH;
    }

    global $__insert_at_the_bottom;
    $_text = $scripts;
    $_text = $__insert_at_the_bottom . $_text;

    return str_ireplace("</body>", "$_text\n</body>", $output);

}

