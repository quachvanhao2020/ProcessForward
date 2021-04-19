<?php
use ProcessForward\ProcessManager;
require_once "../vendor/autoload.php";
if(empty($_POST)){
    $input = json_decode(file_get_contents("php://input"),true);
    $_POST = $input;
}
$pm = new ProcessManager();
$pm->handle();