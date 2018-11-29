<?php

//tratar a requisição e redirecionar de acordo com o status da compra.
function hmu_create_user(WP_REST_Request $request)
{
    $obj = $request->get_params();
    $opt = get_option( 'hmu_opts' );

    if ($obj->hottok == $opt->hottok ) {
        if(email_exists( $obj->email )) {
            status_header( 502 );
            wp_send_json( __('Este e-mail já está em uso!', 'hotwebhookuser') );
        }
    }

    wp_send_json($obj);
}