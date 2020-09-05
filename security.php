<?php
/**
 * @file security
 */


/**
 * Check admin functionality
 */
if ( strpos(in('page'), 'admin') !== false ) {
    if ( ! admin() ) {
        $_REQUEST['page'] = 'error.you-are-not-admin';
    }
}

