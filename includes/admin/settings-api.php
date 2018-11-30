<?php
function hmu_settings_api()
{
    register_setting('hmu_opts_group', 'hmu_opts', 'hmu_opts_sanitaze');

    //Section dos campos para configuração do webhook.
    add_settings_section(
        'hmu_settings',
        __('Integração com Hotmart', 'hotwebhookuser'),
        'hmu_settings_section',
        'hmu_opts_sections'
    );

    add_settings_field(
        'hmu_token_required',
        __('Token do Hotmart', 'hotwebhookuser'),
        'hmu_token_callback_function',
        'hmu_opts_sections',
        'hmu_settings'
    );


    // Section dos campos de e-mail.
    add_settings_section(
        'hmu_settings',
        __('E-mail para Clientes', 'hotwebhookuser'),
        '',
        'hmu_opts_email_sections'
    );

    add_settings_field(
        'hmu_title_email_required',
        __('Título do E-mail', 'hotwebhookuser'),
        'hmu_hotmart_title_email_callback_funtion',
        'hmu_opts_email_sections',
        'hmu_settings'
    );

    add_settings_field(
        'hmu_nome_autor',
        __('Nome do Autor', 'hotwebhookuser'),
        'hmu_hotmart_remetente_callback_function',
        'hmu_opts_email_sections',
        'hmu_settings'
    );

    add_settings_field(
        'hmu_email_remetente_required',
        __('E-mail do Remetente', 'hotwebhookuser'),
        'hmu_hotmart_email_remetente_callback_function',
        'hmu_opts_email_sections',
        'hmu_settings'
    );

    add_settings_field(
        'hmu_conteudo_email',
        __('Conteúdo do E-mail', 'hotwebhookuser'),
        'hmu_conteudo_email_callback_function',
        'hmu_opts_email_sections',
        'hmu_settings'
    );

    //Section que gera os links.
    add_settings_section(
        'hmu_settings',
        __('Link do WebHook', 'hotwebhookuser'),
        'hmu_link_webhook',
        'hmu_opts_link_sections'
    );

}

function hmu_settings_section()
{
    $message = __('Aqui você poderá configurar o WebHook do Hotmart e também customizar o e-mail enviado para seu cliente!', 'hotwebhookuser');
    echo "<p> {$message} </p>";
}


function hmu_token_callback_function () {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_token_required" value="<?php echo $opts['hmu_token_required']; ?>"
 placeholder="Insira o seu token aqui!"
required="" name="hmu_opts[hmu_token_required]">
<?php
}

function hmu_hotmart_title_email_callback_funtion() {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_title_email_required" 
placeholder="[Curso] Seus Dados de Acesso" value="<?php echo $opts['hmu_title_email_required']; ?>"
name="hmu_opts[hmu_title_email_required]">
<?php
}

function hmu_hotmart_remetente_callback_function () {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_nome_autor"
 placeholder="Nome do autor do curso" value="<?php echo $opts['hmu_nome_autor']; ?>"
 name="hmu_opts[hmu_nome_autor]">
<?php
}

function hmu_hotmart_email_remetente_callback_function () {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" 
id="hmu_email_remetente_required" 
placeholder="E-mail do autor" value="<?php echo $opts['hmu_email_remetente_required']; ?>"
name="hmu_opts[hmu_email_remetente_required]">
<?php
}

function hmu_conteudo_email_callback_function () {
    $opts = get_option('hmu_opts');
    $settings = array(
        'media_buttons' => false,
        'tinymce'       => array(
        'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
        'toolbar2'      => '',
        'toolbar3'      => '',
    ),);

    $template = wp_remote_get(
		plugins_url( 'template/email-template.php', HMU_PLUGIN_URL )
    );
    
    $template_html = wp_remote_retrieve_body($template);

    $value = $opts['hmu_conteudo_email'] == "" ? $template_html : $opts['hmu_conteudo_email'];
    wp_editor( $value, 'content_tiny', $settings);
    ?>
    <input type="hidden" value="<?php echo esc_html($opts['hmu_conteudo_email']); ?>" name="hmu_opts[hmu_conteudo_email]" id="hmu_conteudo_email" />
    <script>
        jQuery(function($) {
            $("#opt-form").on('submit', function(e) {
                $('#hmu_conteudo_email').val( tinymce.activeEditor.getContent() );
            });
        });
    </script>
    <?php
}

function hmu_link_webhook() {
    $message = __('Aqui está o link que você deve por em sua conta do Hotmart:', 'hotwebhookuser');
    echo "<p> {$message} </p>";
    ?>
<code>
    <?php echo UserController::getRoute('store'); ?>
</code>
<?php
}

function hmu_opts_sanitaze ( $input ) {
    $input['hmu_token_required']            = sanitize_text_field( $input['hmu_token_required'] );
    $input['hmu_title_email_required'] = sanitize_text_field( $input['hmu_email_remetente_required'] );
    $input['hmu_remetente'] = sanitize_text_field( $input['hmu_remetente'] );
    $input['hmu_conteudo_email_input'] = sanitize_text_field( $input['hmu_conteudo_email_input'] );
    $input['hmu_email_remetente_required'] = sanitize_email( $input['hmu_email_remetente_required'] );
    $input['hmu_conteudo_email_input'] = wp_kses_post($input['hmu_conteudo_email_input']);
    
    
	return $input;
}