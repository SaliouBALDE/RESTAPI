<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost:8080/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include_once '../config/Database.php';
    include_once '../objects/Users.php';

    $database = new Database();
    $db = $database->getConnection();

    $user = new Users($db);

    $donnees = json_decode(file_get_contents("php://input"));

    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->role = $data->role;
    $user->language = $data->language;

} else {
    //On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => " La mathode n'est pas autorisée"]);
}
