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

        $obj =  $request->get_params();
        $opt =  get_option( 'hmu_opts' );
        $errors =  $this->validate($obj, [
                "hottok"       => 'required',
                "first_name"    => 'required',
                "last_name"     => 'required',
                "email"         => "required|email",
            ]
        );

        if(count($errors) === 0) {
            if ($obj["hottok"] == "XeHaykWnh8pHKVDm2ZETkJy0y4XK86edc1b5f2-93da-40b9-9acd-df912e4b39cc" ) {
                if(email_exists( $obj["email"] )) {
                    status_header( 502 );
                    wp_send_json( __('Este e-mail já está em uso!', 'hotwebhookuser')  );
                } else {
                    $password = wp_generate_password( 6, false );
                    $userdata = array(
                        'user_login' => $obj['email'],
                        'user_nicename' => $obj['first_name'],
                        'first_name'    => $obj['first_name'],
                        'last_name'     => $obj['last_name'],
                        'user_email'    => $obj['email'],
                        'user_pass'     => $password,
                    );
                    wp_insert_user( $userdata );
                    $obj['password'] = $password;
                    $this->send_email($obj);
                    wp_send_json( "Done");
                }
            }else {
                status_header( 502 );
                wp_send_json( "Token Inválido!" );
            }
        }

        status_header( 502 );
        wp_send_json( $errors );
    }

    private function send_email( $dados ) {
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail( $dados['email'], "Assunto", "Bla bla bla", $headers);
    }
    /**
     * Método para validar dados de acordo com as $rules.
     * @param $data e $rules. As rules devem seguir o formato de array ['fieldname' => 'required|email"]
     * @return array $errors.
     */
    public function validate( $data, $rules ) {
        $errors = array();
        foreach ($rules as $key => $value) {
            $rules_value = explode( "|", $value);
            foreach ($rules_value as $rule) {
                if( !is_bool($this->valid_rule($data[$key], $rule)) ) {
                    $errors[$key] = $this->valid_rule($data[$key], $rule);
                }
            }
        }
        return $errors;
    }

    /**
     * Função interna para validar a regra e o valor.
     * @param $value e $rule.
     * @return true em caso de válido.
     * @return string em caso de não válido.
     */
    private function valid_rule ($value, $rule){
        switch ($rule) {
            case "required":
                return isset($value) ? true: "campo requerido";
            case "email":
                return is_email( $value ) ? true : "e-mail inválido";
            case "email_exists":
                return !email_exists( $value ) ? true : "e-mail já cadastrado";
            default:
                return true;
        }
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