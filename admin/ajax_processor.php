<?php

include("../includes/mysqli.php");

$action = $_POST['action'];
$zite_id = $_POST['zite_id'];

if($action == 'layout'){
    $pos = 1;
    foreach($_POST as $key => $value) {
        if (strstr($key, 'se')){
            $values = [];
            foreach($value as $se) {
                $values[] = $se;
            }
            $stmt = $mysqli_link->prepare("REPLACE INTO tblSvgElement(PlaceID, SVG, SVGPosition, Path1Color, Path2Color, Path3Color) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("iissss", $zite_id, $values[0], $pos, $values[1], $values[2], $values[3]);
            $stmt->execute();
            $pos +=1;
        }
    }
    $stmt->close();
    $mysqli_link->close();
}

?>