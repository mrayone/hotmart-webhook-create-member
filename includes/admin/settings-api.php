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
        'hmu_remetente',
        __('Nome do Remetente', 'hotwebhookuser'),
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
    ?>
<input type="text" class="regular-text" id="hmu_token_required" placeholder="Insira o seu token aqui!" required="" name="hmu_opts[hmu_token_required]">
<?php
}

function hmu_hotmart_title_email_callback_funtion() {
    ?>
<input type="text" class="regular-text" id="hmu_title_email_required" placeholder="[Curso] Seus Dados de Acesso" name="hmu_opts[hmu_title_email_required]">
<?php
}

function hmu_hotmart_remetente_callback_function () {
    ?>
<input type="text" class="regular-text" id="hmu_remetente" placeholder="Nome do autor do curso" name="hmu_opts[hmu_remetente]">
<?php
}

function hmu_hotmart_email_remetente_callback_function () {
    ?>
<input type="text" class="regular-text" id="hmu_email_remetente_required" placeholder="E-mail do autor" name="hmu_opts[hmu_email_remetente_required]">
<?php
}

function hmu_conteudo_email_callback_function () {
    wp_editor( '', 'hmu_conteudo_email_input' );
}

function hmu_link_webhook() {
    ?>
<code>
    <?php echo rest_url( 'hothook/v1/user' ); ?>
</code>
<?php
}