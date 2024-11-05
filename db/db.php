<?php

function getConnection(){
    $host = "";
$user = "";
$pass = "";
$db_name = "";

$conn = new mysqli($host, $user, $pass,$db_name);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);}

return $conn;
}

function addRow($fullname, $role, $efficiency){
   
    $conn = getConnection();
    $stmt = $conn->prepare('INSERT INTO test(full_name, role, efficiency) VALUES (?,?,?)');
    $stmt->bind_param("ssi", $fullname, $role, $efficiency);
    $stmt->execute();

    return $conn->insert_id;

}

function getById($id){
   
    $conn = getConnection();
    $res = $conn->query("SELECT * FROM test WHERE id = $id");
    $row = $res->fetch_assoc();
    return $row;

}

function getByParams($params){
    $strParams = "";
    foreach($params as $key => $value){
        $strParams = $strParams .  $key . " = " . $value . " and "; 
    }
    $strParams = $strParams . " 1=1";
    $conn = getConnection();
    $res = $conn->query("SELECT * FROM test WHERE " . $strParams);
    $row = $res->fetch_assoc();
    return $row;

}

function updateRow($user_id, $fullname, $role, $efficiency){
    $setStr = "";
    if (!empty($fullname)) {
         $setStr = 'full_name = "' . $fullname . '"';
    }
    if (!empty($role)) {
        $setStr .= empty($setStr)?"":","; 
        $setStr .='role = "' . $role . '"';
   }
   if (!empty($efficiency) && !is_null($efficiency)){
    $setStr .= empty($setStr)?"":","; 
    $setStr = $setStr . 'efficiency = '. $efficiency;
}
    $conn = getConnection();
    $stmt = $conn->prepare('UPDATE test SET ' . $setStr . ' where id = ?');
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}


function deleteById($id){
    $stmt = "1=1";
    if (!is_null($id)){
        $stmt = "id = " . $id;
    }
    $conn = getConnection();
    $res = $conn->query("DELETE FROM test WHERE " . $stmt);
  
    return $res;

}
