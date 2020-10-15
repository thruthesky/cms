<?php

?>
<div>
    Copy the json inside textarea and import it on remote server.
</div>

<textarea class="w-100" style="height: 600px;"><?=json_encode(get_i18n( Config::$i18n_languages ));?>
</textarea>
