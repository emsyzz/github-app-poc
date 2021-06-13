<?php

require_once 'bootstrap.php';

$writer = new Writer();

$requestBody = file_get_contents('php://input');
$requestData = json_decode($requestBody, true);

$writer->writeDump("Headers:", getallheaders());
$writer->writeDump("Body:", $requestData);