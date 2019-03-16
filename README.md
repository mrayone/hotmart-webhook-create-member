# Hotmart Criador de Membros WebHook (Hotmart User Creator WebHook) Beta 1.0
Um Plugin para WordPress que realiza a integração com o WebHook do Hotmart. O plugin tem como função criar um usuário em seu blog quando o cliente efetivar uma compra de algum curso.
Estão presentes neste plugin as seguintes funcionalidades:

1. Integração com API do SendGrid E-mail. Esta integração é opcional já que o plugin utiliza a função "wp_mail" do WordPress para utilizar o servidor de SMTP da própria hospedagem.

2. Verifica o campo status do WebHook para criar usuário quando a compra estiver "aprovada" e excluir o mesmo caso seja solicitado "estorno" ou "reembolso".

## Observações

O plugin utiliza a função de rest_api do WordPress. Desta forma recomenda-se a utilização das versões  >= 4.5 e o links-permanentes no modelo "nome do post".
