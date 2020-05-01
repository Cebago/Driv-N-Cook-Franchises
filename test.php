<?php

$admin = ($_SERVER["SERVER_ADMIN"]);
$string  = substr($admin, strpos($admin,'@')+1, strlen($admin));
echo $string;