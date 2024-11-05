<?php

function sendResponse($success, $result){
    $response = array("success" => $success, "result"=> $result);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
}

function sendResponseSucces($success){
    $response = array("success" => $success);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
}




function getResultError($error){
    return array("error" => $error);
}


function sendResponseError($error){
    $err = getResultError($error);
    sendResponse(false, $err);
}