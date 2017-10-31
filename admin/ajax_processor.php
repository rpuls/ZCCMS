<?php

$action = $_POST['action'];

if($action == 'layout'){
    
    $svg = $_POST['svg'];
    echo "you chose $svg ?";
}

?>