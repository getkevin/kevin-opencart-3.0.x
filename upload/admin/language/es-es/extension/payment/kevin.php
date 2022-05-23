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
$_['heading_title'] = '<span style="font-weight: 700; color:red;">kevin. </span> Payment (versión '.KEVIN_VERSION.')';

// Text
$_['text_clear_success'] = 'Éxito: ¡Has limpiado el registro con éxito!';
$_['text_edit'] = 'Editar kevin. módulo de pago';
$_['text_extension'] = 'Extensiones';
$_['text_kevin'] = '<a href="https://www.kevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="kevin." title="kevin." style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_left'] = 'Izquierda';
$_['text_payment'] = 'Pago';
$_['text_payment_log'] = 'Registro de pagos';
$_['text_refund_log'] = 'Registro de devoluciones';
$_['text_right'] = 'A la derecha';
$_['text_select_action'] = '- Seleccionar Acción-';
$_['text_select_status'] = '-Seleccionar estado-';
$_['text_success'] = 'Éxito: Has modificado los detalles del módulo de kevin.';

// Entry
$_['entry_bank_name_enabled'] = 'Nombre del banco';
$_['entry_client_company'] = 'Nombre de la empresa cliente:';
$_['entry_client_endpointSecret'] = 'EndpointSecret';
$_['entry_client_iban'] = 'Número de cliente';
$_['entry_client_id'] = 'Client ID:';
$_['entry_client_secret'] = 'Client Secret:';
$_['entry_completed_status'] = 'Completa';
$_['entry_created_refund_action'] = 'Reembolsos listos';
$_['entry_failed_status'] = 'Fallido';
$_['entry_full_refund_action'] = 'Totalmente financiado';
$_['entry_general'] = 'General';
$_['entry_geo_zone'] = 'Zona geográfica:';
$_['entry_image'] = 'Pago a kevin. Imagen del logotipo';
$_['entry_image_height'] = 'Altura máxima de la imagen en px';
$_['entry_image_width'] = 'Ancho máximo de la imagen en px';
$_['entry_instructions'] = 'Instrucciones de pago';
$_['entry_instruction_title'] = 'Título de la instrucción';
$_['entry_kevin_instruction'] = 'Instrucción sobre el paso de confirmación. No se admite HTML';
$_['entry_kevin_title'] = 'Método de pago Título';
$_['entry_log'] = 'kevin. registro:';
$_['entry_order_status'] = 'Estado del pedido:';
$_['entry_order_statuses'] = 'Estados de los pedidos';
$_['entry_partial_refund_action'] = 'Reembolsado parcialmente';
$_['entry_partial_refund_status'] = 'Estado del pedido parcialmente reembolsado';
$_['entry_payment_log'] = 'Registro de pago';
$_['entry_pending_status'] = 'Pendiente';
$_['entry_position'] = 'Posición del logotipo de pago';
$_['entry_redirect_preferred'] = 'Redirección preferente';
$_['entry_refunded_status'] = 'Estado del pedido reembolsado';
$_['entry_refund_actions'] = 'Acciones de reembolso';
$_['entry_refund_log'] = 'Registro de reembolso';
$_['entry_refund_status'] = 'Estado del reembolso';
$_['entry_sort_order'] = 'Orden de clasificación:';
$_['entry_started_status'] = 'Comenzó';
$_['entry_status'] = 'Estado:';
$_['entry_total'] = 'Total:';

// Error
$_['error_bcmod'] = 'No es posible validar el número de cuenta porque el módulo PHP &quot;bcmath&quot; no está instalado en su servidor. Por favor, instale el módulo &quot;bcmath&quot;, o pida a su proveedor de servidores que instale el módulo "bcmath".';
$_['error_client_company'] = 'Nombre de la empresa cliente requerido';
$_['error_client_c_symbol'] = '¡No se aceptan caracteres especiales en el nombre del cliente!';
$_['error_client_endpointSecret'] = 'Se requiere la firma del cliente.';
$_['error_client_iban_empty'] = 'Se requiere un número de cuenta de cliente.';
$_['error_client_iban_valid'] = 'El número de cuenta del cliente no es válido.';
$_['error_client_id'] = 'Se requiere la identificación del cliente.';
$_['error_client_secret'] = '¡Se requiere el secreto del cliente!';
$_['error_completed_status'] = 'Se requiere el estado del pedido.';
$_['error_created_action'] = 'Se requiere una acción de reembolso.';
$_['error_failed_status'] = 'Se requiere el estado del pedido.';
$_['error_partial_action'] = 'Se requiere una acción de reembolso.';
$_['error_partial_status'] = 'Se requiere el estado del pedido.';
$_['error_payment_log_warning'] = 'Advertencia: ¡Su archivo de registro de pagos %s es %s!';
$_['error_pending_status'] = 'Se requiere el estado del pedido.';
$_['error_permission'] = 'Advertencia: No tienes permiso para modificar el pago kevin.';
$_['error_refunded_action'] = 'Se requiere una acción de reembolso.';
$_['error_refunded_status'] = 'Se requiere el estado del pedido.';
$_['error_refund_log_warning'] = 'Advertencia: ¡Su archivo de registro de reembolso %s es %s!';
$_['error_started_status'] = 'Se requiere el estado del pedido.';
$_['error_title'] = '¡Título de pago, o logo de pago requerido!';
$_['error_warning'] = 'Comprueba que no haya errores en los ajustes.';

// Help
$_['help_bank_name_enbl'] = 'Habilitar el nombre del banco en la página de pago.';
$_['help_bank_title'] = 'Sólo puede añadir el logotipo de un banco en lugar del título del método de pago.';
$_['help_client_endpointSecret'] = 'Su secreto de punto final de kevin. tablero de instrumentos.';
$_['help_client_id'] = 'Su ID de cliente de kevin. salpicadero.';
$_['help_client_secret'] = 'Su secreto de cliente de kevin. salpicadero.';
$_['help_height'] = 'Establezca la altura máxima de la imagen del logotipo de pago en px para el método de pago en el paso de pago de la caja. El ancho de la imagen se cambiará proporcionalmente.';
$_['help_iban_format'] = 'Formato del número de cuenta Para Lituania deben ser dos letras y 18 números. Ejemplo: LT599386327515536498.';
$_['help_log'] = 'Si kevin. log está activado, se guardarán los archivos kevin_payment.log y kevin_refund.log. Puede comprobarlo fácilmente, descargarlo o borrarlo.';
$_['help_position'] = 'Posición del logotipo del método de pago junto al nombre del método de pago.';
$_['help_total'] = 'Cantidad mínima de pedido';
$_['help_width'] = 'Establezca el ancho máximo de la imagen del logotipo de pago en px para el método de pago en el paso de pago de la caja. La altura de la imagen se cambiará proporcionalmente.';
