<?php 
function hmu_rest_routes_init() {
    register_rest_route('hothook/v1', 'user', array(
        'methods'   => 'POST',
        'callback'  => 'hmu_create_user'
    ) );
}