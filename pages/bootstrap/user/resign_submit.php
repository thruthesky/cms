<?php


$re = $apiLib->userResign(['session_id' => sessionId()]);

/**
 * Resign success
 */
if ($re['session_id'] == sessionId()) {
    ?>
    <script>

        $$(function() {
            setCookieLogout();
            move('<?=Config::$resignResultPage?>');
        });
    </script>
<?php
}




?>
