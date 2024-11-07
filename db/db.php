<?php

function getConnection(){


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
    $values = array();
    $types = "";
    foreach($params as $key => $value){
        $strParams = $strParams .  $key . " = ?" . " and "; 
        $types .= gettype($value)[0];
        $values[] = $value;

    }
    $strParams = $strParams . " 1=?";
    $types .= "i";
    $values[] = 1;
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM test WHERE " . $strParams);
    $bindres = $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_all(MYSQLI_ASSOC);
    return $row;
    // $res = $conn->query("SELECT * FROM test WHERE " . $strParams);
    // $row = $res->fetch_all(MYSQLI_ASSOC);
    // return $row;

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


function deleteAll(){
    $res = $conn->query("DELETE FROM test");
    return $res;

}
function deleteById($id){

    $conn = getConnection();
    $res = $conn->query("SELECT * FROM test WHERE id = $id");
    $row = $res->fetch_assoc();

    $res = $conn->query("DELETE FROM test WHERE id= " . intval($row["id"]));
  
    return $res;

}