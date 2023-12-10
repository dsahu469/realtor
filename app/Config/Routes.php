<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group("api", function ($routes) {
    $routes->post("register", "Authentication::register");
    $routes->post("login", "Authentication::login");
    $routes->post("whatsapp/upload", "Whatsapp::upload", ['filter' => 'authFilter']);
    $routes->post("user/update/address", "User::updateLocation", ['filter' => 'authFilter']);
    $routes->get("whatsapp/messages", "Whatsapp::getWhatsapp", ['filter' => 'authFilter']);
});