<?php

require_once './classes/user.php';
require_once './classes/files.php';
require_once './classes/response.php';
require_once './classes/pizzas.php';
require_once './classes/sales.php';
require_once __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

$request = $_SERVER['PATH_INFO'] ?? '';
$method =  $_SERVER['REQUEST_METHOD'] ?? '';
$key = 'prog3-parcial';

if ($request != '/usuario' && $request != '/login') {
    $jwtData = getallheaders()['token'] ?? '';
    try {
        $decoded = JWT::decode($jwtData, $key, array('HS256'));
    } catch (\Throwable $th) {
        exit(Response::res("Error", 401, "Unauthorized"));
    }
}

switch ($method) {
    case 'POST':
        switch ($request) {
            case '/usuario':
                $email = $_POST['email'] ?? '';
                $password = $_POST['clave'] ?? '';
                $type = $_POST['tipo'] ?? '';

                if ($email != '' && $password != '' && $type != '') {
                    if (User::validUser($email, 'users.json')) {
                        $user = new User($email, $password, $type);
                        $file = new File('users.json');

                        if ($file->writeJson($user)) {
                            echo Response::res("Success", 200, "User added");
                        }  else {
                            echo Response::res("Error", 400, "Problem while adding user");
                        }
                    }  else {
                        echo Response::res("Error", 400, "User already exist");
                    }
                } else {
                    echo Response::res("Failed", 400, "Missing parameters");
                }
                break;
            case '/login':
                $email = $_POST['email'] ?? '';
                $password = $_POST['clave'] ?? '';

                if ($email != '' && $password != '') {
                    $file = new File('users.json');
                    $user = $file->findOne($email, $password);

                    if ($user) {
                        $payload = array(
                        'email' => $user->email,
                        'password' => $user->password,     
                        'type' => $user->type);
                        echo Response::res("Success", 201, (object) JWT::encode($payload, $key));
                    } else {
                        echo Response::res("Failed", 404, "User not found");
                    }
                } else {
                    echo Response::res("Failed", 400, "Missing parameters");
                }
                break;
            case '/pizzas':
                $type = $_POST['tipo'] ?? '';
                $price = $_POST['precio'] ?? '';
                $stock = $_POST['stock'] ?? '';
                $flavor = $_POST['sabor'] ?? '';
                $picture = $_FILES['foto'] ?? '';

                if(User::isAdmin($decoded->email, 'users.json')){
                    if ($type != '' && $price != '' && $stock != '' && $flavor != '' && isset($picture)) {
                        if(Pizza::validFlavor($flavor, $type, 'pizzas.json')) {
                            $pizza = new Pizza($type, $price, $stock, $flavor, $picture);
                            $file = new File('pizzas.json');

                            if ($file->writeJson($pizza)) {
                                echo Response::res("Success", 200, "Pizza added");
                            }  else {
                                echo Response::res("Failed", 400, "Problem while adding pizza");
                            }
                        } else {
                            echo Response::res("Failed", 400, "Pizza already exist");
                        }
                    } else {
                        echo Response::res("Failed", 400, "Missing parameters");
                    }
                } else {
                    echo Response::res("Error", 401, "Unauthorized");
                }
                break;
            case '/ventas':
                $type = $_POST['tipo'] ?? '';
                $flavor = $_POST['sabor'] ?? '';

                if(!User::isAdmin($decoded->email, 'users.json')){
                    if ($type != ''  && $flavor != '') {
                        $sales = new Sales();
                        $sale = $sales->sell($type, $flavor, $decoded);
                        if ($sale) {
                            echo Response::res("Success", 200, $sale);
                        } else {
                            echo Response::res("Failed", 400, "Problem while adding sale");
                        }
                    } else {
                        echo Response::res("Failed", 400, "Missing parameters");
                    }
                } else {
                    echo Response::res("Error", 401, "Unauthorized");
                }
                break;
            default:
                echo Response::res("Error", 400, "Bad endpoint");
                break;
        }
        break;
    case 'GET':
        switch ($request) {
            case '/pizzas':
                $pizzas = Pizza::getPizzas($decoded->email, 'pizzas.json');
                echo Response::res("Success", 200, $pizzas);
                break;
            case '/ventas':
                $sales = Sales::getSales($decoded);
                echo Response::res("Success", 200, $sales);
                break;
            default:
                echo Response::res("Error", 400, "Bad endpoint");
                break;
        }
        break;
    default:
        echo Response::res("Error", 400, "Bad method");
        break;
}