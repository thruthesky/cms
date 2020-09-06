<?php


function patch_javascript_into_output($output) {

    global $__included_files;

    $v = Config::$appVersion;
    $scripts = '';
    $url = THEME_URL;
    foreach( $__included_files as $file ) {
        $path = str_replace(".php", ".js", $file);
        $arr = explode('/wp-content/', $path);
        $path_url = '/wp-content/' . $arr[1];
        if ( file_exists($path) ) $scripts .= <<<EOH
<script src="$path_url?v=$v"></script>
EOH;
    }

    return str_ireplace("</body>", "$scripts\n</body>", $output);
}

