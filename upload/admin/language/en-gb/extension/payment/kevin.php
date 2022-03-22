<?php
/*
* 2020 kevin. payment  for OpenCart version 3.0.x.x
* @version 1.0.1.4
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
$_['heading_title'] = '<span style="font-weight: 700; color:red;">kevin. </span> Payment (version 1.0.1.4)';

// Text
$_['text_payment'] = 'Payment';
$_['text_edit'] = 'Edit kevin. payment module';
$_['text_extension'] = 'Extensions';
$_['text_success'] = 'Success: You have modified kevin. module details!';
$_['text_clear_success'] = 'Success: You have successfully cleared log!';
$_['text_kevin'] = '<a href="https://www.kevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="kevin." title="kevin." style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_right'] = 'Right';
$_['text_left'] = 'Left';
$_['text_select_status'] = '-Select Status-';
$_['text_select_action'] = '-Select Action-';
$_['text_payment_log'] = 'Payment log';
$_['text_refund_log'] = 'Refund log';

// Entry
$_['entry_general'] = 'General';
$_['entry_order_statuses'] = 'Order Statuses';
$_['entry_refund_actions'] = 'Refund Actions';
$_['entry_instructions'] = 'Payment Instructions';
$_['entry_client_id'] = 'Client Id: ';
$_['entry_client_secret'] = 'Client Secret: ';
$_['entry_client_endpointSecret'] = 'EndpointSecret';
$_['entry_client_company'] = 'Client Company Name: ';
$_['entry_client_iban'] = 'Client Accaunt No.: ';
$_['entry_redirect_preferred'] = 'Redirect Preferred';
$_['entry_image'] = 'Payment kevin. Logo Image';
$_['entry_image_height'] = 'Max Image Height in px';
$_['entry_image_width'] = 'Max Image Width in px';
$_['entry_position'] = 'Payment Logo Position';
$_['entry_bank_name_enabled'] = 'Bank Name';
$_['entry_kevin_title'] = 'Payment Method Title';
$_['entry_instruction_title'] = 'Instruction Title';
$_['entry_kevin_instruction'] = 'Instruction on the confirm step. HTML not supported';
$_['entry_total'] = 'Total: ';
$_['entry_order_status'] = 'Order Status:';
$_['entry_started_status'] = 'Started';
$_['entry_completed_status'] = 'Complete';
$_['entry_pending_status'] = 'Pending';
$_['entry_failed_status'] = 'Failed';
$_['entry_partial_refund_status'] = 'Partially Refunded Order Status';
$_['entry_refunded_status'] = 'Refunded Order Status';
$_['entry_created_refund_action'] = 'Refund Ready';
$_['entry_partial_refund_action'] = 'Partially Refunded';
$_['entry_full_refund_action'] = 'Fully Refunded';
$_['entry_geo_zone'] = 'Geo Zone:';
$_['entry_status'] = 'Status:';
$_['entry_log'] = 'kevin. log:';
$_['entry_sort_order'] = 'Sort Order:';
$_['entry_refund_status'] = 'Refund Status';
$_['entry_payment_log'] = 'Payment log';
$_['entry_refund_log'] = 'Refund log';

// Error
$_['error_warning'] = 'Check the settings carefully for errors!';
$_['error_permission'] = 'Warning: You do not have permission to modify payment kevin.!';
$_['error_client_id'] = 'Client Id Required!';
$_['error_client_secret'] = 'Client Secret Required!';
$_['error_client_endpointSecret'] = 'Client Signature Required!';
$_['error_client_company'] = 'Client Company Name Required!';
$_['error_client_iban_empty'] = 'Client Account No. Required!';
$_['error_client_iban_valid'] = 'Client Account No. not valid!';
$_['error_title'] = 'Payment Title, or Payment logo Required!';
$_['error_bcmod'] = 'Not possible validate Account No. because PHP Module "bcmath" are not installed on your server!  Please install "bcmath" module, or ask your server provider to install "bcmath" module.';
$_['error_started_status'] = 'Order status required!';
$_['error_pending_status'] = 'Order status required!';
$_['error_failed_status'] = 'Order status required!';
$_['error_completed_status'] = 'Order status required!';
$_['error_partial_status'] = 'Order status required!';
$_['error_refunded_status'] = 'Order status required!';
$_['error_created_action'] = 'Refund action required!';
$_['error_refunded_action'] = 'Refund action required!';
$_['error_partial_action'] = 'Refund action required!';
$_['error_refund_log_warning'] = 'Warning: Your Refund log file %s is %s!';
$_['error_payment_log_warning'] = 'Warning: Your Payment log file %s is %s!';

// Help
$_['help_iban_format'] = 'Account No. format For Lithuania should be two letters and 18 numbers. Example: LT599386327515536498.';
$_['help_bank_name_enbl'] = 'Enable bank name on checkout page.';
$_['help_client_id'] = 'Your client ID. Your can get it in kevin. platform console.';
$_['help_client_secret'] = 'Your secret code. Your can get it in kevin. platform console.';
$_['help_client_endpointSecret'] = 'Your secret signature. Your can get it in kevin. platform console.';
$_['help_bank_title'] = 'You can only add a bank logo instead of a Payment Method Title.';
$_['help_total'] = 'The checkout total the order must reach before this payment method becomes active.';
$_['help_log'] = 'If &quot;kevin. log&quot; is enabled kevin_payment.log and kevin_refund.log files will be saved. You can easily check it, download, or clear.';
$_['help_width'] = 'Set the payment logo image  max width in px for &quot;payment method&quot; in checkout payment step. Image height will be changed proportionally.';
$_['help_height'] = 'Set the payment logo image  max height in px for &quot;payment method&quot; in checkout payment step. Image width will be changed proportionally.';
$_['help_position'] = 'Position of the payment method logo next to the payment method name.';
