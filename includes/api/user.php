<?php
function hmu_create_user (WP_REST_Request $request) {

    wp_send_json('params' . $request->get_params() );
}
