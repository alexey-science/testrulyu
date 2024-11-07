<?php
require_once("./models/response.php");
require_once("./db/db.php");

CONST MAX_INT_DB = 2147483647;

CONST MIN_INT_DB  = -2147483648;
function paramIsInt($param){

    return (float)((int)$param) === (float)$param;
}

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

    if(!is_numeric($efficiency) || !paramIsInt($efficiency)){
        sendResponseError("Параметр efficiency должен быть целочисленным");
        return;
    }
    $efficiency = (int) $efficiency;
    if($efficiency > MAX_INT_DB) {
        $efficiency =   MAX_INT_DB;
    }
    if($efficiency < MIN_INT_DB) {
        $efficiency =   MIN_INT_DB;
    }

    $fullname = substr($fullname, 0, 255);
    $role = substr($role, 0, 255);

    $last_id = addRow($fullname, $role, $efficiency);

    sendResponse(true, array("id"=>$last_id));
    return;
}


function getMethod($request, $user_id){
    if (!is_null($user_id) && (!is_numeric($user_id) || !paramIsInt($user_id))){
        sendResponseError("user id должен быть целочисленным");
        return;
    }

    if(!is_null($user_id)){
        $user_id = (int) $user_id;
        $user = getById($user_id);
        if(is_null($user)){
            sendResponseError("Данных с указанными параметрами не найдено");
            return;

        }
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
        $params["efficiency"] =  $_GET["efficiency"];
    }


    $users = getByParams($params);
    if (count($users) == 0){
        sendResponseError("Данных с указанными параметрами не найдено");
        return;
    }
    sendResponse(true, array("users"=>$users));
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

    if (!is_null($user_id) && (!is_numeric($user_id) || !paramIsInt($user_id))){
        sendResponseError("user id должен быть целочисленным");
        return;
    }

    if(!isset($request["full_name"]) && !isset($request["role"]) && !isset($request["efficiency"])){
        sendResponseError("Запрос должен содержать данные для обновления");
        return; 
    }

    $params = array();

    $fullname = isset($request["full_name"])?$request["full_name"]:"";
    

    $role = isset($request["role"])?$request["role"]:"";
    

    $efficiency = isset($request["efficiency"])?$request["efficiency"]:null;
   
   

    if(!is_null($efficiency) && (!is_numeric($efficiency) || !paramIsInt($efficiency))){
        sendResponseError("Параметр efficiency должен быть целочисленным");
        return;
    }

    if (!is_null($efficiency)  && is_numeric($efficiency) && paramIsInt($efficiency)){
        $efficiency = (int) $efficiency;
        if($efficiency > MAX_INT_DB) {
            $efficiency =   MAX_INT_DB;
        }
        if($efficiency < MIN_INT_DB) {
            $efficiency =   MIN_INT_DB;
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
    if (!is_null($user_id) && (!is_numeric($user_id) || !paramIsInt($user_id))){
        sendResponseError("user id должен быть целочисленным");
        return;
    }else{
        $user_id = (int) $user_id;
        $user = deleteById($user_id);
        sendResponse(true, array("users"=>array($user)));
        
        return;
    }

        
    if(is_null($user)){
        $res = deleteAll();
        sendResponseSucces($res);
        return;
    }

   
    
}
