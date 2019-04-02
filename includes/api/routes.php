<?php 
function hmu_rest_routes_init() {
    $user = new \Controllers\UserController();

    register_rest_route('/hothook/v1', '/' . 'users', array(
        array(
            'methods' => 'POST',
            'callback' => array($user, "store"),
        ),
    ));
}