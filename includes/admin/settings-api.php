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
        __('Token do Hotmart (*)', 'hotwebhookuser'),
        'hmu_token_callback_function',
        'hmu_opts_sections',
        'hmu_settings'
    );

    add_settings_field(
        'hmu_sendgrid',
        __('SendGrid API Token (Opcional)', 'hotwebhookuser'),
        'hmu_sendgrid_token_callback_function',
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
        __('Título do E-mail (*)', 'hotwebhookuser'),
        'hmu_hotmart_title_email_callback_funtion',
        'hmu_opts_email_sections',
        'hmu_settings'
    );

    add_settings_field(
        'hmu_nome_autor',
        __('Nome do Autor (*)', 'hotwebhookuser'),
        'hmu_hotmart_remetente_callback_function',
        'hmu_opts_email_sections',
        'hmu_settings'
    );

    add_settings_field(
        'hmu_email_remetente_required',
        __('E-mail do Remetente (*)', 'hotwebhookuser'),
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


function hmu_sendgrid_token_callback_function () {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_sendgrid" value="<?php echo $opts['hmu_sendgrid']; ?>" placeholder="Insira o seu token aqui!"
    name="hmu_opts[hmu_sendgrid]"><a href="https://sendgrid.com/" target="_blank">
    <?php echo __('Obtenha seu token aqui!', 'hotwebhookuser'); ?></a>
<p class="description" id="new-admin-email-description">
    <?php echo __('Esta configuração permite utilizar a aplicação SendGrid API para envio de e-mails.', 'hotwebhookuser' ); ?>
</p>

<?php
}

function hmu_token_callback_function () {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_token_required" value="<?php echo $opts['hmu_token_required']; ?>"
    placeholder="Insira o seu token aqui!" required="" name="hmu_opts[hmu_token_required]"><a href="https://app-vlc.hotmart.com/tools/webhook" target="_blank">
    <?php echo __('Obtenha seu token aqui!', 'hotwebhookuser'); ?></a>
<?php
}

function hmu_hotmart_title_email_callback_funtion() {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_title_email_required" required="" placeholder="[Curso] Seus Dados de Acesso" value="<?php echo $opts['hmu_title_email_required']; ?>"
    name="hmu_opts[hmu_title_email_required]">
<?php
}

function hmu_hotmart_remetente_callback_function () {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_nome_autor" placeholder="Nome do autor do curso" value="<?php echo $opts['hmu_nome_autor']; ?>"
    name="hmu_opts[hmu_nome_autor]">
<?php
}

function hmu_hotmart_email_remetente_callback_function () {
    $opts = get_option('hmu_opts');
    ?>
<input type="text" class="regular-text" id="hmu_email_remetente_required" placeholder="E-mail do autor" required="" value="<?php echo $opts['hmu_email_remetente_required']; ?>"
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
    $template_html = str_replace('TEXT_1', __("Olá", 'hotwebhookuser') , $template_html);
    $template_html = str_replace('TEXT_2', __("aqui é o", 'hotwebhookuser') , $template_html);
    $template_html = str_replace('TEXT_3', __("Estou te enviando esse e-mail para parabenizá-lo por sua inscrição no curso CURSO_NOME e também para te passar os
    dados de acesso!", 'hotwebhookuser') , $template_html);
    $template_html = str_replace('TEXT_4', __("Segue os dados:", 'hotwebhookuser') , $template_html);
    $template_html = str_replace('TEXT_5', __("Site para acesso:", 'hotwebhookuser') , $template_html);
    $template_html = str_replace('TEXT_6', __("Login:", 'hotwebhookuser') , $template_html);
    $template_html = str_replace('TEXT_7', __("Senha Temporária:", 'hotwebhookuser') , $template_html);
    $template_html = str_replace('URL_SITE', get_site_url().'/wp-login.php',$template_html);

    $value = $opts['hmu_conteudo_email'] == "" ? $template_html : $opts['hmu_conteudo_email'];
    wp_editor( $value, 'content_tiny', $settings);
    ?>
    <?php $message = __('Agilizaremos algumas coisas para você se as constantes ´NOME_CLIENTE, NOME_AUTOR, URL_SITE, USU_LOGIN, USU_PASSWORD´ continuarem no corpo do e-mail :) !!!', 'hotwebhookuser'); 
        echo "<p> <strong>{$message}</strong> </p>";
    ?>
<input type="hidden" value="<?php echo esc_html($opts['hmu_conteudo_email']); ?>" name="hmu_opts[hmu_conteudo_email]"
    id="hmu_conteudo_email" />
<script>
    jQuery(function ($) {
        $("#opt-form").on('submit', function (e) {
            $('#hmu_conteudo_email').val(tinymce.activeEditor.getContent());
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
    <?php echo \Controllers\v1\UserController::getRoute('store'); ?>
</code>
<code>
    <?php echo \Controllers\v2\UserController::getRoute('store'); ?>
</code>
<?php
}

function hmu_opts_sanitaze ( $input ) {
    $input['hmu_token_required']            = sanitize_text_field( $input['hmu_token_required'] );
    $input['hmu_title_email_required'] = sanitize_text_field( $input['hmu_title_email_required'] );
    $input['hmu_remetente'] = sanitize_text_field( $input['hmu_remetente'] );
    $input['hmu_conteudo_email_input'] = sanitize_text_field( $input['hmu_conteudo_email_input'] );
    $input['hmu_sendgrid'] = sanitize_text_field( $input['hmu_sendgrid'] );
    $input['hmu_email_remetente_required'] = sanitize_email( $input['hmu_email_remetente_required'] );
    $input['hmu_conteudo_email_input'] = wp_kses_post($input['hmu_conteudo_email_input']);
    
    
	return $input;
}