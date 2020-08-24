<?php


$re = $apiBase->userResign(['session_id' => sessionId()]);

/**
 * Resign success
 */
if ($re['session_id'] == sessionId()) {
    ?>
    <script>
        setLogout();
    </script>
<?php
}




?>

<h1>Thank you.</h1>

<p>
    We are looking forward to see you again. Good Bye!
</p>