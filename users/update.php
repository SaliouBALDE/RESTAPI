<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost:8080/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
include_once '../config/core.php';
include_once '../includes/php-jwt-master/src/BeforeValidException.php';
include_once '../includes/php-jwt-master/src/ExpiredException.php';
include_once '../includes/php-jwt-master/src/SignatureInvalidException.php';
include_once '../includes/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    include_once '../config/Database.php';
    include_once '../objects/Users.php';

    $database = new Database();
    $db = $database->getConnection();

    $user = new Users($db);

    $donnees = json_decode(file_get_contents("php://input"));

    $user->id = $donnees->id;
    $user->firstname = $donnees->firstname;
    $user->lastname = $donnees->lastname;
    $user->email = $donnees->email;
    $user->password = $donnees->password;
    $user->role = $donnees->role;
    $user->language = $donnees->language;

    if ($user->update()) {
        http_response_code(200);
        echo json_encode(["message" => "User was created."]);
    } else {
        http_response_code(200);
        echo json_encode(["message" => "Unable to create user."]);
    }

} else {
    //On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => " La mathode n'est pas autorisée"]);
}
