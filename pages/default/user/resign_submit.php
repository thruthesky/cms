<?php


$re = $apiBase->userResign(['session_id' => sessionId()]);

if ($re = sessionId()) {
    setLogout();
}




?>

<h1>Thank you.</h1>

<p>
    We are looking forward to see you again. Good Bye!
</p>