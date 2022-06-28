<?php
/*
* 2020 kevin. payment  for OpenCart version 3.0.x.x
* @version 1.0.1.5
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
*  @author 2020 kevin. <help@kevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
// Heading
$_['heading_title'] = '<span style="font-weight: 700; color:red;">kevin. </span> Payment (versão '.KEVIN_VERSION.')';

// Text
$_['text_clear_success'] = 'O sucesso: Conseguiu limpar o registo!';
$_['text_edit'] = 'Editar módulo de pagamento kevin.';
$_['text_extension'] = 'Extensões';
$_['text_kevin'] = '<a href="https://www.kevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="kevin." title="kevin." style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_left'] = 'Esquerda';
$_['text_payment'] = 'Pagamento';
$_['text_payment_log'] = 'Diário de pagamentos';
$_['text_refund_log'] = 'Diário de reembolso';
$_['text_right'] = 'Certo';
$_['text_select_action'] = '- Selecione Ação-';
$_['text_select_status'] = '-Seleccionar Estado...-';
$_['text_success'] = 'O sucesso: Modificou os detalhes do módulo kevin.';
$_['text_refund'] = 'Os reembolsos são permitidos.';
$_['text_payment_bank'] = 'O método de pagamento bancário é permitido.';
$_['text_payment_card'] = 'O método de pagamento com cartão é permitido.';
$_['text_sandbox_alert'] = '<span style = "font-weight: 600; color: red;"> kevin. </span> Os pagamentos são definidos para o modo Sandbox. Apenas para pagamentos de teste. Pagamentos reais não estão disponíveis!';

// Entry
$_['entry_bank_name_enabled'] = 'Nome do Banco';
$_['entry_client_company'] = 'Nome da empresa cliente:';
$_['entry_client_endpointSecret'] = 'EndpointSecret';
$_['entry_client_iban'] = 'Cliente Account No..:';
$_['entry_client_id'] = 'Client ID:';
$_['entry_client_secret'] = 'Client Secret:';
$_['entry_completed_status'] = 'Completo';
$_['entry_created_refund_action'] = 'Reembolso Pronto';
$_['entry_failed_status'] = 'Falha';
$_['entry_full_refund_action'] = 'Totalmente Reembolsado';
$_['entry_general'] = 'Geral';
$_['entry_geo_zone'] = 'Zona Geo:';
$_['entry_image'] = 'Pagamento kevin. Imagem do logotipo';
$_['entry_image_height'] = 'Altura máxima da imagem em px';
$_['entry_image_width'] = 'Largura máxima da imagem em px';
$_['entry_instructions'] = 'Instruções de pagamento';
$_['entry_instruction_title'] = 'Título da Instrução';
$_['entry_kevin_instruction'] = 'Instrução sobre a etapa de confirmação. HTML não suportado';
$_['entry_kevin_title'] = 'Título do método de pagamento';
$_['entry_log'] = 'kevin. log:';
$_['entry_order_status'] = 'Estado da encomenda:';
$_['entry_order_statuses'] = 'Estado das encomendas';
$_['entry_partial_refund_action'] = 'Parcialmente Reembolsado';
$_['entry_partial_refund_status'] = 'Estado da Ordem Parcialmente Reembolsada';
$_['entry_payment_log'] = 'Registro de pagamento';
$_['entry_pending_status'] = 'Pendente';
$_['entry_position'] = 'Posição do logotipo de pagamento';
$_['entry_redirect_preferred'] = 'Redireccionar Preferido';
$_['entry_refunded_status'] = 'Estado da Ordem Reembolsada';
$_['entry_refund_actions'] = 'Acções de Reembolso';
$_['entry_refund_log'] = 'Registro de Reembolso';
$_['entry_refund_status'] = 'Estado do Reembolso';
$_['entry_sort_order'] = 'Ordem de classificação:';
$_['entry_started_status'] = 'Iniciado em';
$_['entry_status'] = 'Estado:';
$_['entry_total'] = 'Total:';

// Error
$_['error_bcmod'] = 'Não é possível validar o N.º de conta porque o Módulo PHP &quot;bcmath&quot; não está instalado no seu servidor! Por favor instale o módulo &quot;bcmath&quot;, ou peça ao seu fornecedor do servidor para instalar o módulo "bcmath".';
$_['error_client_company'] = 'Nome da Empresa Cliente Necessário!';
$_['error_client_c_symbol'] = 'Caracteres especiais no nome do cliente não são aceitáveis!';
$_['error_client_endpointSecret'] = 'É necessária a assinatura do cliente!';
$_['error_client_iban_empty'] = 'N.º de Conta de Cliente Necessário!';
$_['error_client_iban_valid'] = 'Número de conta de cliente não válido!';
$_['error_client_id'] = 'Necessária identificação do cliente!';
$_['error_client_secret'] = 'Segredo do cliente é necessário!';
$_['error_completed_status'] = 'Estado do pedido exigido!';
$_['error_created_action'] = 'É necessária uma acção de reembolso!';
$_['error_failed_status'] = 'Estado do pedido exigido!';
$_['error_partial_action'] = 'É necessária uma acção de reembolso!';
$_['error_partial_status'] = 'Estado do pedido exigido!';
$_['error_payment_log_warning'] = 'Advertência: O seu ficheiro de registo de pagamento %s é %s!';
$_['error_pending_status'] = 'Estado do pedido exigido!';
$_['error_permission'] = 'Advertência: Não tem autorização para modificar o pagamento kevin.';
$_['error_refunded_action'] = 'É necessária uma acção de reembolso!';
$_['error_refunded_status'] = 'Estado do pedido exigido!';
$_['error_refund_log_warning'] = 'Advertência: O seu ficheiro de registo de Reembolso %s é %s!';
$_['error_started_status'] = 'Estado do pedido exigido!';
$_['error_title'] = 'Título de pagamento, ou Logotipo de pagamento Necessário!';
$_['error_warning'] = 'Verifique cuidadosamente as definições para detectar erros!';
$_['error_client'] = 'Não é possível conectar com <span style="font-weight: 600; color:red;">kevin. </span> devido a um erro do servidor.';

// Help
$_['help_bank_name_enbl'] = 'Habilitar o nome do banco na página de checkout.';
$_['help_bank_title'] = 'Só se pode adicionar um logótipo bancário em vez de um título do método de pagamento.';
$_['help_client_endpointSecret'] = 'O seu Endpoint Secret do painel de bordo do kevin.';
$_['help_client_id'] = 'O seu ID de cliente do painel de instrumentos do kevin.';
$_['help_client_secret'] = 'O segredo do seu cliente no painel de bordo do kevin.';
$_['help_height'] = 'Definir a altura máxima da imagem do logotipo de pagamento em px para o método de pagamento na etapa de pagamento da caixa. A largura da imagem será alterada proporcionalmente.';
$_['help_iban_format'] = 'Formato da conta No. de conta Para a Lituânia deve ser de duas letras e 18 números. Exemplo: LT599386327515536498.';
$_['help_log'] = 'Se kevin. log estiver activado, os ficheiros kevin_payment.log e kevin_refund.log serão guardados. Pode facilmente verificá-lo, descarregá-lo, ou apagá-lo.';
$_['help_position'] = 'Posição do logotipo do método de pagamento ao lado do nome do método de pagamento.';
$_['help_total'] = 'Quantidade mínima do pedido';
$_['help_width'] = 'Definir a largura máxima da imagem do logótipo de pagamento em px para o método de pagamento na etapa de pagamento da caixa. A altura da imagem será alterada proporcionalmente.';
