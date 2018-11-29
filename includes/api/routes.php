<?php 
function hmu_rest_routes_init() {
    $user = new UserController();
    $user->register_routes();
}