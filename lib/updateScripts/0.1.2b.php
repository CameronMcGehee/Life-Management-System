<?php

    $currentUpdateStatus = 'run';

    $database->delete("systemInfo", "WHERE var = 'currentVersion'", 1);
    $database->insert("systemInfo", [
        "var" => "currentVersion",
        "val" => "0.1.2b"
    ]);
    $currentUpdateStatus = 'success';

?>
