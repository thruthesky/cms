<?php
	header('Content-Type: text/css');

	$l_width = 1130;
	$l_content_width = 800;
	$l_space = 16;
	$l_sidebar_width = $l_width - $l_content_width;

	$color_primary = '#0055FF';
	$color_primary_text = 'white';

?>
/**
 * Base following Reboot
 */
*, ::after, ::before {
    box-sizing: border-box;
}
body {
    margin: 0;
    color: #212529;
    background-color: #FFFFFF;
    text-align: left;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
    font-weight: 400;
    font-size: 16px;
    line-height: 1.5;
}
h1, .page-title {
    margin-top: 0;
    margin-bottom: .5rem;
    font-size: 2.5rem;
    font-weight: 500;
    line-height: 1.2;
}
h2 {
    margin-top: 0;
    margin-bottom: .5rem;
    font-size: 2rem;
    font-weight: 500;
    line-height: 1.2;
}
h3 {
    margin-top: 0;
    margin-bottom: .5rem;
    font-size: 1.75rem;
    font-weight: 500;
    line-height: 1.2;
}
p {
    margin-top: 0;
    margin-bottom: 1rem;
}
a {
    color: #007bff;
    text-decoration: none;
    background-color:transparent;
}
b, strong {
    font-weight: bolder;
}

/**
 * Layout
 */
.l-width, .l-center {
    overflow: hidden;
    max-width: <?php echo $l_width?>px;
}
.l-center {
    margin: 0 auto;
}
.l-sidebar-width {
    overflow: hidden;
    width: <?php echo $l_sidebar_width?>px;
}

/**
 * Breaks
 */
.d-none {
    display: none;
}
.d-block {
    display: block;
}




/**
 * Flex, Position, Places
 */
.flex {
    display: flex;
}
.justify-content-between {
    justify-content: space-between;
}
.justify-content-center {
    justify-content: center;
}

.relative {
    position: relative;
}
.absolute {
    position: absolute;
}
.top {
    top: 0;
}
.right {
    right: 0;
}
.left {
    left: 0;
}
.bottom {
    bottom: 0;
}

/**
 * Width
 */
.w-100 {
    width: 100%;
}
/**
 * Margin, Padding, Height
 */
.m-0 {
    margin: 0;
}
.m-1 {
    margin: .25rem;
}
.mt-3 {
    margin-top: 1rem;
}

.ml-2 {
    margin-left: .5rem;
}
.ml-3 {
    margin-left: 1rem;
}

.p-1 {
    padding: .25rem;
}
.p-2 {
    padding: .5rem;
}

.p-3 {
    padding: 1rem;
}
.p-4 {
    padding: 1.5rem;
}
.p-5 {
    padding: 3rem;
}
.pl-3 {
    padding-left: 1rem;
}

.h-3 {
    height: <?php echo $l_space?>px;
}
.h-4 {
    height: <?php echo $l_space + ( $l_space / 2 )?>px;
}
.h-5 {
    height: <?php echo $l_space * 2?>px;
}

/**
 * Media Query of Box, Size, Ddisplay
 */
@media all and (min-width: 540px) {
    .d-sm-block {
        display: block;
    }
}

@media all and (min-width: 768px) {
    .d-md-block {
        display: block;
    }
}
@media all and (min-width: 992px) {
    .d-lg-block {
        display: block;
    }
    .d-lg-none {
        display: none;
    }
    .p-lg-5 {
        padding: 3rem;
    }
}
@media all and (min-width: 1200px) {
    .d-xl-block {
        display: block;
    }
}


/**
 * Border
 */
.rounded {
    border-radius: 3px;
}


/**
 * Colors
 */
.bg-white {
    background-color: #ffffff;
}
.bg-light {
    background-color: #ECECEC;
}
.bg-lighter {
    background-color: #F8F8F8;
}
.bg-lightblue {
    background-color: #bee7ff;
}
.bg-lightgreen {
    background-color: #d1fdd1;
}
.bg-lightred {
    background-color: #fdd1da;
}

/**
 * Micellanious
 */
.pointer {
    cursor: pointer;
}
/**
 * Components
 */
.page-subtitle {
    font-size: .85rem;
    color: #3c3c3c;
}
.page-title {
     color: #2c2c2c;
}


.form-input {
    padding: .25em;
    width: 100%;
    font-size: 1.25em;
}

.btn-primary {
    padding: .75em;
    border: 0;
    width: 100%;
    color: <?php echo $color_primary_text?>;
    background-color: <?php echo $color_primary?>;
    cursor: pointer;
}


.spinner {
    border: 2px solid #d5d5d5;
    border-radius: 50%;
    border-top: 2px solid #9c9f9e;
    border-bottom: 2px solid #9c9f9e;
    width: 24px;
    height: 24px;
    -webkit-animation: spin 1.5s linear infinite;
    animation: spin 1.5s linear infinite;
}

@-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

