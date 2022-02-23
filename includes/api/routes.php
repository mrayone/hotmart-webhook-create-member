<?php 
function hmu_rest_routes_init() {
    $userControllerV1 = new \Controllers\v1\UserController();
    $userControllerV2 = new \Controllers\v2\UserController();

    register_rest_route('/hothook/v1', '/' . 'users', array(
        array(
            'methods' => 'POST',
            'callback' => array($userControllerV1 , "store"),
        ),
    ));

    register_rest_route('/hothook/v2', '/' . 'users', array(
        array(
            'methods' => 'POST',
            'callback' => array($userControllerV2 , "store"),
        ),
    ));
}