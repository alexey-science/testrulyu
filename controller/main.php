<?php

require_once("./models/response.php");
require_once("./controller/basicMethods.php");

// function main($fullPath){

// }

function route($fullPath){

    $action = $fullPath[0];
    $serverMethod = $_SERVER["REQUEST_METHOD"];
    switch(true){
        case ($action ==="create") && (count($fullPath) == 1):
            if($serverMethod === "POST"){
                $request_body = file_get_contents('php://input');
                $request_body = json_decode($request_body, true);
                createMethod($request_body);
            }else{
                sendResponseError("Неверно указан метод http запроса!");  
            }
            break;
        case ($action ==="get") && (count($fullPath) <= 2):
                if($serverMethod === "GET"){
                    $request_body = file_get_contents('php://input');
                    $request_body = json_decode($request_body, true);
                    $userId = isset($fullPath[1])?intval($fullPath[1]):null;
                    getMethod($request_body, $userId);
                }else{
                    sendResponseError("Неверно указан метод http запроса!");  
                }
                break;
        case ($action ==="update") && (count($fullPath) == 2):
                    if($serverMethod === "PATCH"){
                        $request_body = file_get_contents('php://input');
                        $request_body = json_decode($request_body, true);
                        $userId = intval($fullPath[1]);
                        updateMethod($request_body, $userId);
                    }else{
                        sendResponseError("Неверно указан метод http запроса!");  
                    }
                    break;
        case ($action ==="delete") && (count($fullPath) <= 2):
                        if($serverMethod === "DELETE"){
                            $request_body = file_get_contents('php://input');
                            $request_body = json_decode($request_body, true);
                            $userId = isset($fullPath[1])?intval($fullPath[1]):null;
                            deleteMethod($request_body, $userId);
                        }else{
                            sendResponseError("Неверно указан метод http запроса!");  
                        }
                        break;
        default:
            sendResponseError("Неверный запрос!");
            
    }
}