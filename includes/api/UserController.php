<?php

class UserController
{

    // Iniciliazando o namespace e o recurso.
    public function __construct()
    {
        $this->namespace = '/hothook/v1';
        $this->resource_name = 'users';
        $this->options = get_option('hmu_opts');
    }

    // Registrando nossas rotas.
    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->resource_name, array(
            array(
                'methods' => 'POST',
                'callback' => array($this, "store"),
            ),
        ));
    }

    // Sets up the proper HTTP status code for authorization.
    public function authorization_status_code()
    {

        $status = 401;

        if (is_user_logged_in()) {
            $status = 403;
        }

        return $status;
    }

    //tratar a requisição e redirecionar de acordo com o status da compra.
    public function store($request)
    {
        if (!($request instanceof WP_REST_Request)) {
            return new WP_Error('rest_forbidden', esc_html__('Bad Request'), array('status' => 502));
        }

        $obj = $request->get_params();
        $errors = $this->validate($obj, [
            "hottok" => 'required',
            "first_name" => 'required',
            "last_name" => 'required',
            "email" => "required|email",
            "status"=> "required"
        ]
        );

        if (count($errors) === 0) {
            if ($obj["hottok"] == $this->options['hmu_token_required']) {
                switch($obj['status']) {
                    case 'approved': 
                        $this->create_user($obj);
                    break;
                    case 'canceled':
                    case 'chargeback':
                    case 'refunded':
                        $this->delete_user($obj);
                    break;
                }
            } else {
                status_header(502);
                wp_send_json("Token Inválido!");
            }
        }

        status_header(502);
        wp_send_json($errors);
    }

    /**
     * Método que cria o usuário.
     * @param array $obj com os dados da requisição.
     * @return wp_send_json response.
     * 
     */
    private function create_user($obj) {
        if (email_exists($obj["email"])) {
            status_header(502);
            wp_send_json(__('Este e-mail já está em uso!', 'hotwebhookuser'));
        } else {
            $password = wp_generate_password(6, false);
            $userdata = array(
                'user_login' => $obj['email'],
                'user_nicename' => $obj['first_name'],
                'first_name' => $obj['first_name'],
                'last_name' => $obj['last_name'],
                'user_email' => $obj['email'],
                'user_pass' => $password,
            );
            wp_insert_user($userdata);
            $obj['password'] = $password;
            $this->send_email($obj);
            wp_send_json("Done");
        }
    }

    /**
     * Método que deleta o usuário.
     * @param array $obj com os dados da requisição.
     * @return wp_send_json response.
     * 
     */
    private function delete_user($obj) {
        require_once( ABSPATH.'wp-admin/includes/user.php' );
        if (email_exists($obj["email"])) {
            $user =  get_user_by( "email", $obj['email'] );
            wp_delete_user( $user->ID );

            wp_send_json("Done");
        }
    }

    /**
     * Método que envia o e-mail de acordo com o template.
     * @param array $dados
     * @return void
     */
    private function send_email($dados)
    {
        $nome_autor = $this->options['hmu_nome_autor'];
        $email_autor = $this->options['hmu_email_remetente_required'];

        $message_html = $this->options['hmu_conteudo_email'];

        
        $message_html = str_replace('NOME_CLIENTE', $dados['first_name'], $message_html);
        $message_html = str_replace('NOME_AUTOR', $this->options['hmu_nome_autor'], $message_html);
        $message_html = str_replace('CURSO_NOME', $dados['prod_name'], $message_html);
        $message_html = str_replace('USU_LOGIN', $dados['email'], $message_html);
        $message_html = str_replace('USU_PASSWORD', $dados['password'], $message_html);
        $assunto = $this->options['hmu_title_email_required'];

        $headers = array('Content-Type: text/html; charset=UTF-8', "Reply-To: {$nome_autor} <{$email_autor}>");

        if (empty($this->options['hmu_sendgrid'])) {
            wp_mail($dados['email'], $assunto, $message_html, $headers);
        } else {
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($email_autor, $nome_autor);
            $email->setSubject($assunto);
            $email->addTo($dados['email'], $dados['first_name']);
            $email->addContent(
                "text/html", $message_html
            );
            $sendgrid = new \SendGrid($this->options['hmu_sendgrid']);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {
                return new WP_Error('rest_forbidden', esc_html__('Erro ao enviar e-mail.'), array('status' => 502));
            }
        }

    }
    /**
     * Método para validar dados de acordo com as $rules.
     * @param $data e $rules. As rules devem seguir o formato de array ['fieldname' => 'required|email"]
     * @return array $errors.
     */
    public function validate($data, $rules)
    {
        $errors = array();

        if (!$this->options) {
            status_header(502);
            wp_send_json("Erro de configuração do plugin!");
        }

        foreach ($rules as $key => $value) {
            $rules_value = explode("|", $value);
            foreach ($rules_value as $rule) {
                if (!is_bool($this->valid_rule($data[$key], $rule))) {
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
    private function valid_rule($value, $rule)
    {
        switch ($rule) {
            case "required":
                return isset($value) ? true : "campo requerido";
            case "email":
                return is_email($value) ? true : "e-mail inválido";
            case "email_exists":
                return !email_exists($value) ? true : "e-mail já cadastrado";
            default:
                return true;
        }
    }
    /**
     * Método estático para o retorno de rotas que espera o rótulo do recurso para retornar a rota.
     * @param string $resource é método que se deseja retornar a url.
     * @return route_url url formatada exemplo: http://myblog.com.br/wp-json/hothook/v1/{resource}
     */
    public static function getRoute($resource)
    {
        switch ($resource) {
            case "store":
                return rest_url("/hothook/v1/users");
            default:
                return null;
        }
    }
}
