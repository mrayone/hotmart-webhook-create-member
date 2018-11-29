<?php

class UserController {
    // Iniciliazando o namespace e o recurso.
    public function __construct() {
        $this->namespace     = '/hothook/v1';
        $this->resource_name = 'users';
    }
 
    // Registrando nossas rotas.
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            array(
                'methods'   => 'POST',
                'callback'  => array($this, "store")
            )
        ) );
    }
 
    // Sets up the proper HTTP status code for authorization.
    public function authorization_status_code() {
 
        $status = 401;
 
        if ( is_user_logged_in() ) {
            $status = 403;
        }
 
        return $status;
    }

    //tratar a requisição e redirecionar de acordo com o status da compra.
    public function store ($request)
    {
        if( !($request instanceof WP_REST_Request ) ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'Bad Request' ), array( 'status' => 502 ) );
        }

        $obj = (object) $request->get_params();
        $opt = get_option( 'hmu_opts' );

        if ($obj->hottok == $opt->hottok ) {
            if(email_exists( $obj->email )) {
                status_header( 502 );
                wp_send_json( __('Este e-mail já está em uso!', 'hotwebhookuser') );
            }
        }

        wp_send_json($obj->first_name);
    }


    /**
     * Método estático para o retorno de rotas que espera o rótulo do recurso para retornar a rota.
     * @param string $resource é método que se deseja retornar a url.
     * @return route_url url formatada exemplo: http://myblog.com.br/wp-json/hothook/v1/{resource}
     */
    public static function getRoute($resource) {
        switch($resource) {
            case "store": 
                return rest_url( "/hothook/v1/users" );
            default:
                return null;
        }
    }
}