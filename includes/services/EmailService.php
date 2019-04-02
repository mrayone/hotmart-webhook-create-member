<?php
namespace services;

class EmailService {

    public function __construct () {
        $this->options = get_option('hmu_opts');

        $this->sendApi = $this->options['hmu_sendgrid'];

        $this->nome_autor = $this->options['hmu_nome_autor'];
        $this->email_autor = $this->options['hmu_email_remetente_required'];
        $this->message_html = $this->options['hmu_conteudo_email'];
        $this->assunto = $this->options['hmu_title_email_required'];
        $this->headers = array('Content-Type: text/html; charset=UTF-8', "Reply-To: {$this->nome_autor} <{$this->email_autor}>");
    }

    public function send_email($dados)
    {
        $this->message_html = str_replace('NOME_CLIENTE', $dados['first_name'], $this->message_html);
        $this->message_html = str_replace('NOME_AUTOR', $this->options['hmu_nome_autor'], $this->message_html);
        $this->message_html = str_replace('CURSO_NOME', $dados['prod_name'], $this->message_html);
        $this->message_html = str_replace('USU_LOGIN', $dados['email'], $this->message_html);
        $this->message_html = str_replace('USU_PASSWORD', $dados['password'], $this->message_html);

        if (empty($this->sendApi)) {
            send_wp_mail($dados['email']);
        } else {
            send_grid($dados['email']);
        }
    }

    private function send_wp_mail($dados) {
        wp_mail($dados['email'], $this->assunto, $this->message_html, $this->headers);
    }

    private function send_grid ($dados) {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($this->email_autor, $this->nome_autor);
        $email->setSubject($this->assunto);
        $email->addTo($dados['email'], $dados['first_name']);
        $email->addContent(
            "text/html", $this->message_html
        );
        $sendgrid = new \SendGrid($this->sendApi);
        try {
            $response = $sendgrid->send($email);
        } catch (Exception $e) {
            return new WP_Error('rest_forbidden', esc_html__('Erro ao enviar e-mail.'), array('status' => 502));
        }
    }
}