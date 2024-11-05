<?php

require_once("./controller/main.php");
require_once("./db/db.php");

$fullPath = isset($_GET["_route"])?$_GET["_route"]:"index";
  


$fullPath = explode("/", $fullPath);
if(empty($fullPath[count($fullPath)-1])){
    array_splice($fullPath, count($fullPath)-1, 1);
}
route($fullPath);
