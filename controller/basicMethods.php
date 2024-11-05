<?php
require_once("./models/response.php");
require_once("./db/db.php");

function createMethod($request){
    $error = false;
    $textError = "";
    //$error = !isset($request["full_name"]) || !isset($request["role"]) || !isset($request["efficiency"]);
    // if ($error){
    //     sendResponseError("Недостаточно параметров");
    //     return;
    // }

    $fullname = isset($request["full_name"])?$request["full_name"]:"";
    if (empty($fullname)){
        sendResponseError("Параметр full_name обязателен");
        return;
    }

    $role = isset($request["role"])?$request["role"]:"";
    if (empty($role)){
        sendResponseError("Параметр role обязателен");
        return;
    }

    $efficiency = isset($request["efficiency"])?$request["efficiency"]:null;
    if (is_null($efficiency)){
        sendResponseError("Параметр efficiency обязателен");
        return;
    }

    if(!is_int($efficiency)){
        sendResponseError("Параметр efficiency должен быть целочисленным");
        return;
    }

    if($efficiency > PHP_INT_MAX) {
        $efficiency =   PHP_INT_MAX;
    }
    if($efficiency < PHP_INT_MIN) {
        $efficiency =   PHP_INT_MIN;
    }

    $fullname = substr($fullname, 0, 255);
    $role = substr($role, 0, 255);

    $last_id = addRow($fullname, $role, $efficiency);

    sendResponse(true, array("id"=>$last_id));
    return;
}


function getMethod($request, $user_id){
    if (!is_null($user_id) && !is_int($user_id)){
        sendResponseError("user id должен быть целочисленным");
        return;
    }

    if(!is_null($user_id)){
        $user = getById($user_id);
        sendResponse(true, array("users"=>array($user)));
        return;
    }

    $params = array();
    if (isset($_GET["role"])){
        $params["role"] = $_GET["role"];
    }

    if (isset($_GET["full_name"])){
        $params["full_name"] = $_GET["full_name"];
    }

    if (isset($_GET["efficiency"])){
        $params["efficiency"] = $_GET["efficiency"];
    }


    $users = getByParams($params);
    $users = is_null($users)?"":$users;
    sendResponse(true, array("users"=>array($users)));
    return;
}

function updateMethod($request, $user_id){
    $error = false;
    $textError = "";
    //$error = !isset($request["full_name"]) || !isset($request["role"]) || !isset($request["efficiency"]);
    // if ($error){
    //     sendResponseError("Недостаточно параметров");
    //     return;
    // }

    if (!is_null($user_id) && !is_int($user_id)){
        sendResponseError("user id должен быть целочисленным");
        return;
    }

    $params = array();

    $fullname = isset($request["full_name"])?$request["full_name"]:"";
    

    $role = isset($request["role"])?$request["role"]:"";
    

    $efficiency = isset($request["efficiency"])?$request["efficiency"]:null;
   

    if(!is_null($efficiency) && !is_int($efficiency)){
        sendResponseError("Параметр efficiency должен быть целочисленным");
        return;
    }

    if (!is_null($efficiency)){
        if($efficiency > PHP_INT_MAX) {
            $efficiency =   PHP_INT_MAX;
        }
        if($efficiency < PHP_INT_MIN) {
            $efficiency =   PHP_INT_MIN;
        }
    }

    $fullname = substr($fullname, 0, 255);
    $role = substr($role, 0, 255);
    $update_row = updateRow($user_id, $fullname, $role, $efficiency);

    $user = getById($user_id);
    sendResponse(true, array("users"=>array($user)));
    return;
}


function deleteMethod($request, $user_id){
    if (!is_null($user_id) && !is_int($user_id)){
        sendResponseError("user id должен быть целочисленным");
        return;
    }

        $user = deleteById($user_id);
        sendResponseSucces($user);
        return;
    

   
    
}
