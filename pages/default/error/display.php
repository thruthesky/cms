<?php
/**
 *
 */
/**
 * @param $options['code'] is the code. It is mandatory
 * @param $options['message'] is the message. It is optional. If message is not set, then it displays the translation text of the code.
 */
$options = get_page_options();
if ( !isset($options['message']) || empty($options['message']) ) {
	$options['message'] = tr($options['code']);
	$options['code'] = 'error';
}

?>
<div class="px-40 pt-60 helvetica text-center">

    <img class="w-100 mb-58 mw-60 mx-auto" src="<?=THEME_URL?>/img/error/error.jpg">

    <div class="pb-6 dark-red fw-light"><?=$options['code']?></div>
    <hr>
    <div class="pt-8 mb-58 blackgray"><?=$options['message']?></div>
</div>

<?php

if ( !isset($options['message']) ) {
    dog(debug_backtrace());
}