<?php
$allFiles = glob("../img/events/*");
$images = [];
for ($i = 0; $i < count($allFiles); $i++) {
    $imageName = $allFiles[$i];
    $support = array('gif', 'jpg', 'jpeg', 'png');
    $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    if (in_array($ext, $support)) {
        $images[] = $imageName;
    } else {
        continue;
    }
}

echo json_encode($images);