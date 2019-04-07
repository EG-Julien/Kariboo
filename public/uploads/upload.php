<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key, Cache-Control");


function str_random($length){
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

header('Content-Type: application/json');

$file = str_random('20') . ".jpg";

move_uploaded_file($_FILES['file']['tmp_name'], $file);
move_uploaded_file($_FILES['image']['tmp_name'], $file);

$results = array(
  'id' => basename($file),
  'url'=> "https://elektradev.science/admin/uploads/" . $file
);


header('Content-Type: application/json');
echo json_encode($results);
