<?php

require_once 'bootstrap.php';

$writer = new Writer();

$getData = json_encode($_GET, JSON_PRETTY_PRINT);
$postData = json_encode($_POST, JSON_PRETTY_PRINT);

$writer->write("GET: " . $getData);
$writer->write("POST: " . $postData);