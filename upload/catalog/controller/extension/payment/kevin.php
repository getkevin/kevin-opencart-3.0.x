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
*  @author 2021 kevin. <help@kevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
use Kevin\Client;

class ControllerExtensionPaymentKevin extends Controller
{
    private $type = 'payment';
    private $name = 'kevin';
    private $lib_version = '0.3';
    private $plugin_version = '1.0.1.5';

    public function index()
    {
        $date = new DateTime();
        date_default_timezone_set($date->getTimezone()->getName());

        require_once DIR_APPLICATION.'/model/extension/payment/kevin/vendor/autoload.php';
        $clientId = $this->config->get('payment_kevin_client_id');
        $clientSecret = $this->config->get('payment_kevin_client_secret');
        $endpointSecret = $this->config->get('payment_kevin_client_endpointSecret');

        $options = [
            'error' => 'array',
            'version' => $this->lib_version,
            'pluginVersion' => $this->plugin_version,
            'pluginPlatform' => 'OpenCart',
            'pluginPlatformVersion' => (string) VERSION,
        ];

        $kevinClient = new Client($clientId, $clientSecret, $options);

        $this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');
        $this->load->language('extension/payment/kevin');
        $this->load->model('localisation/language');
        $current_language = $this->config->get('config_language_id');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        if (!$order_info) {
            $order_info['total'] = 0;
            $order_info['currency_code'] = $this->config->get('config_currency');
            $order_info['currency_value'] = 1;
        }

        $total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false, false);

        $data['kevin_instr_title'] = $this->config->get('payment_kevin_ititle'.$current_language);
        //	$data['kevin_instr'] =  html_entity_decode($this->config->get('payment_kevin_instruction' . $current_language));
        $data['kevin_instr'] = $this->config->get('payment_kevin_instruction'.$current_language);

        if (!empty($order_info['payment_iso_code_2'])) {
            $current_country_code = $order_info['payment_iso_code_2'];
        } elseif (isset($this->session->data['iso_code_2'])) {
            $current_country_code = $this->session->data['iso_code_2'];
        }

        $countries = $kevinClient->auth()->getCountries();
        // $countryCodes = array("LT", "LV", "EE");
        $countryCodes = $countries['data'];

        // select  country for banks
        $this->load->model('localisation/country');

        $countries = $this->model_localisation_country->getCountries();

        $data['current_country_code'] = $current_country_code;

        $data['countries'] = [];
        foreach ($countries as $country) {
            if (in_array($country['iso_code_2'], $countryCodes) && $country['status']) {
                $data['countries'][] = [
                'country_id' => $country['country_id'],
                'name' => $country['name'],
                'iso_code_2' => $country['iso_code_2'],
            ];
            }
        }

        $country_code = ['countryCode' => $current_country_code];
        $banks = $kevinClient->auth()->getBanks($country_code);

        $project_settings = $kevinClient->auth()->getProjectSettings();

        $payment_methods = $project_settings['paymentMethods'];

        // card first
        function cmp($a, $b)
        {
            if ($a != 'card') {
                return 1;
            }
            if ($b != 'card') {
                return -1;
            }

            return strcmp($a, $b);
        }

        $payments = $payment_methods;
        usort($payments, 'cmp');

        if (isset($banks['error']['code'])) {
            $data['error_bank_missing'] = 'Error Description: '.$banks['error']['description'].'. Error code: '.$banks['error']['code'].'. Data: '.$banks['data'].'. Please try another payment method.';
        } else {
            $data['error_bank_missing'] = '';
        }

        $data['text_sandbox_alert'] = '';
        if ($project_settings['isSandbox']) {
            $data['text_sandbox_alert'] = $this->language->get('text_sandbox_alert');
        }

        if (@getimagesize('https://cdn.kevin.eu/banks/images/VISA_MC.png')) {
            $data['credit_card_icon'] = 'https://cdn.kevin.eu/banks/images/VISA_MC.png';
        } elseif (file_exists(DIR_APPLICATION.'controller/extension/payment/kevin_image/credit_card_icon.png')) {
            $data['credit_card_icon'] = $this->config->get('config_url').'catalog/controller/extension/payment/kevin_image/credit_card_icon.png';
        } else {
            $data['credit_card_icon'] = '';
        }

        $data['payment_methods'] = $payments;
        $data['banks'] = $banks['data'];

        $data['action'] = $this->url->link('extension/payment/kevin/redirect', '', true);
        $data['bank_name_enable'] = $this->config->get('payment_kevin_bank_name_enabled');

        $order_id = $order_info['order_id'];
        $currency = $order_info['currency_code'];
        $data['currency'] = $currency;

        if (!$currency) {
            $data['text_error_currency'] = $this->language->get('error_currency');
        } else {
            $data['text_error_currency'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        return $this->load->view('extension/'.$this->type.'/'.$this->name, $data);
    }

    public function redirect()
    {
        $date = new DateTime();
        date_default_timezone_set($date->getTimezone()->getName());
        if (isset($this->request->post['bankId']) && isset($this->request->post['payment_method'])) {
            $bank_id = $this->request->post['bankId'];
            $payment_method = $this->request->post['payment_method'];
        } elseif (isset($this->request->get['bankId']) && isset($this->request->get['payment_method'])) {
            $bank_id = $this->request->get['bankId'];
            $payment_method = $this->request->get['payment_method'];
        } else {
            $bank_id = '';
            $payment_method = '';
            $this->session->data['error'] = $this->language->get('error_missing_data');
            $this->KevinLog('Data for payment is missing! Please try again, or choose another payment method.');
            $this->response->redirect($this->url->link('checkout/cart', '', true));
        }

        require_once DIR_APPLICATION.'/model/extension/payment/kevin/vendor/autoload.php';

        $clientId = $this->config->get('payment_kevin_client_id');
        $clientSecret = $this->config->get('payment_kevin_client_secret');
        $endpointSecret = $this->config->get('payment_kevin_client_endpointSecret');

        $options = [
            'error' => 'array',
            'version' => $this->lib_version,
            'pluginVersion' => $this->plugin_version,
            'pluginPlatform' => 'OpenCart',
            'pluginPlatformVersion' => (string) VERSION,
        ];

        $kevinClient = new Client($clientId, $clientSecret, $options);
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');
        $this->load->language('extension/payment/kevin');

        if (isset($this->session->data['order_id'])) {
            $this->session->data['order_id'] = $this->session->data['order_id'];
        } else {
            $this->session->data['order_id'] = 0;
        }

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        // Checking and reseting session order_id if the missing order has been deleted from the DB. Related to the Journal theme.
        if (!$order_info) {
            $this->KevinLog('Checkout was interrupted due lost an order_id.');
            //	$this->session->data['error'] = 'Checkout was interrupted due to a server error! If you want to order the desired products, please try again.';
            unset($this->session->data['order_id']);
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }

        $ip_address = $order_info['ip'];

        $order_id = (int) $order_info['order_id'];
        $order_status_id = $order_info['order_status_id'];

        if (!$order_info) {
            $order_info['total'] = 0;
            $order_info['currency_code'] = $this->config->get('config_currency');
            $order_info['currency_value'] = 1;
        }

        $total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false, false);

        // Vendor logo can be added to the payment confirmation, if kevin API will support it.
        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE.$this->config->get('config_logo'))) {
            $vendor_logo = $this->config->get('config_url').'image/'.$this->config->get('config_logo').' ';
        } else {
            $vendor_logo = '';
        }
        // ('<img src="' . $vendor_logo . '" style="height: 32px width: auto;" />')
        $confirm_url = $this->url->link('extension/payment/kevin/confirm', '', true);

        if (!empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 1) {
            $webhook_url = HTTPS_SERVER.'index.php?route=extension/payment/kevin/webhook';
        } else {
            $webhook_url = HTTP_SERVER.'index.php?route=extension/payment/kevin/webhook';
        }

        if (!empty($this->config->get('payment_kevin_redirect_preferred') && $this->config->get('payment_kevin_redirect_preferred') == 1)) {
            $redirect_preferred = true;
        } else {
            $redirect_preferred = false;
        }

        if (!empty($this->customer->getEmail())) {
            $customer_email = $this->customer->getEmail();
        } elseif (!empty($this->session->data['guest']['email'])) {
            $customer_email = $this->session->data['guest']['email'];
        } else {
            $customer_email = '';
        }

        if ($payment_method == 'bank') {
            $payment_attr = [
                'paymentMethodPreferred' => $payment_method,
                'redirectPreferred' => $redirect_preferred,
                'bankId' => $bank_id,
                'Redirect-URL' => $confirm_url,
                'Webhook-URL' => $webhook_url,
                'endToEndId' => (string) $order_id,
                'description' => sprintf('Order ID %s', $order_id),
                'informationUnstructured' => sprintf('Order ID %s', $order_id),
                'currencyCode' => $order_info['currency_code'],
                'amount' => number_format((float) $total, 2, '.', ''),
                'bankPaymentMethod' => [
                    'endToEndId' => (string) $order_id,
                    'creditorName' => $this->config->get('payment_kevin_client_company'),
                    'creditorAccount' => [
                        'iban' => $this->config->get('payment_kevin_client_iban'),
                    ],
                ],
            ];

            if (!empty($customer_email)) {
                $payment_attr['identifier'] = ['email' => $customer_email];
            }
        } elseif ($payment_method == 'card') {
            $payment_attr = [
                'paymentMethodPreferred' => $payment_method,
                'redirectPreferred' => '1',
                'Redirect-URL' => $confirm_url,
                'Webhook-URL' => $webhook_url,
                'endToEndId' => (string) $order_id,
                'description' => sprintf('Order ID %s', $order_id),
                'informationUnstructured' => sprintf('Order ID %s', $order_id),
                'currencyCode' => $order_info['currency_code'],
                'amount' => number_format((float) $total, 2, '.', ''),
                'bankPaymentMethod' => [
                    'endToEndId' => (string) $order_id,
                    'creditorName' => $this->config->get('payment_kevin_client_company'),
                    'creditorAccount' => [
                        'iban' => $this->config->get('payment_kevin_client_iban'),
                    ],
                ],
                'cardPaymentMethod' => [
                ],
            ];

            if (!empty($customer_email)) {
                $payment_attr['identifier'] = ['email' => $customer_email];
            }
        } else {
            $log_data = 'Answer on Redirect kevin... No payment options using this payment method available.';
            $this->KevinLog($log_data);
            $this->session->data['error'] = $this->language->get('error_payment_option');
            $this->response->redirect($this->url->link('checkout/cart', '', true));
        }

        $init_payment = $kevinClient->payment()->initPayment($payment_attr);

        if (!empty($init_payment['id'])) {
            $payment_id = $init_payment['id'];
        } else {
            $log_data = 'Answer on Redirect kevin... Error Description: '.$init_payment['error']['description'].'. Code: '.$init_payment['error']['code'].'. Data: '.$init_payment['data'].'.';
            $this->KevinLog($log_data);
            $this->session->data['error'] = $this->language->get('error_kevin_payment').' Error Description: '.$init_payment['error']['description'].'. Code: '.$init_payment['error']['code'].'. Data: '.$init_payment['data'].'.';
            $this->response->redirect($this->url->link('checkout/cart', '', true));
            $payment_id = 0;
        }

        if ($payment_method == 'card') {
            $add_order['status'] = $init_payment['hybridStatus'];
        } elseif ($payment_method == 'bank') {
            $add_order['status'] = $init_payment['bankStatus'];
        }

        $add_order['statusGroup'] = $init_payment['statusGroup'];
        $add_order['payment_id'] = $init_payment['id'];
        $add_order['order_status_id'] = 0;
        $add_order['total'] = $total;
        $add_order['bank_id'] = $bank_id;
        $add_order['payment_method'] = $payment_method;
        $add_order['currency_code'] = $order_info['currency_code'];
        $add_order['order_id'] = $order_info['order_id'];
        $add_order['ip_address'] = $ip_address;

        $this->model_extension_payment_kevin->addKevinOrder($add_order);

        $get_payment_attr = ['PSU-IP-Address' => $ip_address];
        $get_payment = $kevinClient->payment()->getPayment($payment_id, $get_payment_attr);

        /* log */
        $log_data = 'Answer on Redirect kevin... Payment Method: '.$payment_method.'; Payment ID: '.$payment_id.'; Order ID: '.$order_id.'; Payment Status: '.$get_payment['statusGroup'].'; Total: '.$get_payment['amount'].$get_payment['currencyCode'].'; Bank ID: '.$bank_id.'.';
        $this->KevinLog($log_data);

        $lang = $this->language->get('code');
        unset($this->session->data['order_id']);

        $init_payment['confirmLink'] .= (parse_url($init_payment['confirmLink'], \PHP_URL_QUERY) ? '&' : '?').'lang='.$lang;

        $this->response->redirect($init_payment['confirmLink']);
    }

    public function confirm()
    {
        $date = new DateTime();
        date_default_timezone_set($date->getTimezone()->getName());
        unset($this->session->data['error']);

        $this->language->load('extension/payment/kevin');
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');

        if (isset($this->request->get['paymentId'])) {
            $payment_id = $this->request->get['paymentId'];
        } elseif (isset($_POST['paymentId'])) {
            $payment_id = $_POST['paymentId'];
        } else {
            $payment_id = 0;
            $log_data = 'On the order confirm Payment ID not received from kevin.\'s server!';
        }

        if (isset($this->request->get['statusGroup'])) {
            $statusGroup = $this->request->get['statusGroup'];
        } else {
            $statusGroup = false;
            $log_data = 'On the order confirm/redirect statusGroup not received from kevin.\'s server!';
        }

        if (!$payment_id || !$statusGroup) {
            $this->KevinLog($log_data);
            $this->session->data['error'] = $this->language->get('error_kevin_payment_id');
            $this->response->redirect($this->url->link('checkout/cart', '', true));
        }

        $order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);

        if ($order_query) {
            $order_id = $order_query['order_id'];
            $this->session->data['order_id'] = $order_query['order_id'];
            $payment_method = $order_query['payment_method'];
            $old_status_id = $order_query['order_status_id'];
        } else {
            $order_id = 0;
            $payment_method = '';
            $this->session->data['error'] = $this->language->get('error_order_session');
            $this->KevinLog('An error occurred. Order Session have been ended!');
            $this->response->redirect($this->url->link('checkout/cart', '', true));
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);

        switch ($statusGroup) {
            case 'started':
                $new_status_id = $this->config->get('payment_kevin_started_status_id');
                $new_status = $statusGroup;
                break;
            case 'pending':
                $new_status_id = $this->config->get('payment_kevin_pending_status_id');
                $new_status = $statusGroup;
                break;
            case 'completed':
                $new_status_id = $this->config->get('payment_kevin_completed_status_id');
                $new_status = $statusGroup;
                break;
            case 'failed':
                $new_status_id = $this->config->get('payment_kevin_failed_status_id');
                $new_status = $statusGroup;
                break;
            default:
                $new_status_id = null;
                $new_status = $statusGroup;
        }

        if (!$new_status_id) {
            $this->session->data['error'] = $this->language->get('error_response');
            $this->KevinLog('An error occurred! On response not received any statusGroup. Description: Server Error.');
            $this->response->redirect($this->url->link('checkout/cart', '', true));
        }

        /* log */
        $log_data = 'Answer on Confirm kevin... Payment Method: '.$payment_method.'; Payment ID: '.$payment_id.'; Order ID: '.$order_info['order_id'].'; Payment Status: '.$new_status.'.';
        $this->KevinLog($log_data);

        if ($new_status == 'completed') {
            $this->response->redirect($this->url->link('checkout/success', '', true));
        } elseif ($new_status == 'pending') {
            $this->response->redirect($this->url->link('checkout/success', '', true));
        } elseif ($new_status == 'failed') {
            $this->response->redirect($this->url->link('checkout/failure', '', true));
        } elseif ($new_status == 'started') {
            $this->response->redirect($this->url->link('checkout/cart', '', true));
        } else {
            $this->session->data['error'] = $this->language->get('error_kevin_payment');
            $this->response->redirect($this->url->link('checkout/cart'));
        }
    }

    public function webhook()
    {
        require_once DIR_APPLICATION.'/model/extension/payment/kevin/vendor/autoload.php';
        $date = new DateTime();
        date_default_timezone_set($date->getTimezone()->getName());
        $this->language->load('extension/payment/kevin');
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/kevin');

        $webhook_data = file_get_contents('php://input');

        if ($webhook_data !== false && !empty($webhook_data)) {
            $this->KevinLog('Received kevin webhook body:'.$webhook_data);
            $get_payment_status = json_decode($webhook_data, true);
        } else {
            $this->KevinLog('Payment status not received from the remote server!');
            header($this->request->server['SERVER_PROTOCOL'].' 400 ');
            exit('Returned empty webhook...');
        }

        $payment_id = $get_payment_status['id'];

        // Validate Signature
        if (!empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 1) {
            $webhook_url = HTTPS_SERVER.'index.php?route=extension/payment/kevin/webhook';
        } else {
            $webhook_url = HTTP_SERVER.'index.php?route=extension/payment/kevin/webhook';
        }

        // function to get headers if use nginx instead of apache
        if (!function_exists('getallheaders')) {
            function getallheaders()
            {
                $get_headers = [];
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $get_headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }

                return $get_headers;
            }
        }

        $get_headers = getallheaders();

        $headers = [];
        foreach ($get_headers as $name => $value) {
            $headers[strtolower($name)] = $value;
        }

        $endpointSecret = $this->config->get('payment_kevin_client_endpointSecret');

        if (!empty($headers['x-kevin-signature']) || !empty($headers['x-kevin-timestamp'])) {
            $kevin_signature = $headers['x-kevin-signature'];
            $time_stamp = $headers['x-kevin-timestamp'];
        } else {
            $kevin_signature = false;
            $time_stamp = '';
        }

        $signature = hash_hmac('sha256', 'POST'.$webhook_url.$time_stamp.$webhook_data, $endpointSecret);

        $this->KevinLog('Generated Signature: '.$signature);
        $this->KevinLog('X-Kevin-Signature: '.$kevin_signature);

        // Timestamp in milliseconds
        $timestampTimeout = 300000;
        $isValid = \Kevin\SecurityManager::verifySignature($endpointSecret, $webhook_data, $headers, $webhook_url, $timestampTimeout);

        if ($isValid) {
            header($this->request->server['SERVER_PROTOCOL'].' 200 ');
            echo 'Signatures match.';
            $this->KevinLog('Signatures match.');
        } else {
            $order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);
            $update_order_signature['order_status_id'] = $this->config->get('payment_kevin_pending_status_id');
            $update_order_signature['payment_id'] = $payment_id;

            if ($order_query['order_status_id'] != $update_order_signature['order_status_id']) {
                $this->model_extension_payment_kevin->updateSignatureKevinOrder($update_order_signature);
                $comment_sign = 'Unable to change order status. Please check whether signature is correct.';
                $this->KevinLog('Signature not validated!');
                $this->model_checkout_order->addOrderHistory($order_query['order_id'], $this->config->get('payment_kevin_pending_status_id'), $comment_sign, true);
            }
            $this->KevinLog('Signatures do not match.');
            header($this->request->server['SERVER_PROTOCOL'].' 400 ');
            exit('Signatures do not match.');
        }

        switch ($get_payment_status['statusGroup']) {
            case 'started':
                $new_status_id = $this->config->get('payment_kevin_started_status_id');
                $new_status = $get_payment_status['statusGroup'];
                break;
            case 'pending':
                $new_status_id = $this->config->get('payment_kevin_pending_status_id');
                $new_status = $get_payment_status['statusGroup'];
                break;
            case 'completed':
                $new_status_id = $this->config->get('payment_kevin_completed_status_id');
                $new_status = $get_payment_status['statusGroup'];
                break;
            case 'failed':
                $new_status_id = $this->config->get('payment_kevin_failed_status_id');
                $new_status = $get_payment_status['statusGroup'];
                break;
            default:
                $new_status_id = null;
                $new_status = $get_payment_status['statusGroup'];
        }

        $order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);

        if ($order_query) {
            $order_id = $order_query['order_id'];
            $payment_method = $order_query['payment_method'];
            $old_status_id = $order_query['order_status_id'];
        } else {
            $order_id = 0;
            $payment_method = '';
            $this->KevinLog('An error occurred. The order has been deleted from the database.');
            header($this->request->server['SERVER_PROTOCOL'].' 400 ');
            exit('An error occurred. The order has been deleted from the database.');
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $log_data = 'Answer on WebHook kevin... Payment Method: '.$payment_method.'; Payment ID: '.$payment_id.'; Order ID: '.$order_info['order_id'].'; Payment Status: '.$new_status.'.';
        $this->KevinLog($log_data);

        if ($payment_method == 'card') {
            $update_order['status'] = !empty($get_payment_status['hybridStatus']) ? $get_payment_status['hybridStatus'] : $get_payment_status['cardStatus'];
        } elseif ($payment_method == 'bank') {
            $update_order['status'] = $get_payment_status['bankStatus'];
        }
        $update_order['statusGroup'] = $get_payment_status['statusGroup'];
        $update_order['payment_id'] = $payment_id;
        $update_order['order_status_id'] = $new_status_id;

        if ($old_status_id != $new_status_id && $order_info['order_id'] == $order_id) {
            $this->KevinLog($log_data);
            $this->model_extension_payment_kevin->updateWebhookKevinOrder($update_order);
            $payment_status = true;
        } else {
            $payment_status = false;
        }

        $order_query = $this->model_extension_payment_kevin->getKevinOrders($payment_id);

        if (!empty($order_query['bank_id']) && $order_query['bank_id'] != 'card') {
            $bank_id = ' ('.$order_query['bank_id'].')';
        } else {
            $bank_id = '';
        }

        $comment = sprintf($this->language->get('text_kevin_payment_method'), ucfirst($order_query['payment_method'])).$bank_id."\n";
        $comment .= sprintf($this->language->get('text_status'), ucfirst($order_query['status']))."\n";
        $comment .= sprintf($this->language->get('text_status_group'), ucfirst($order_query['statusGroup']))."\n";
        $comment .= sprintf($this->language->get('text_payment_id'), $order_query['payment_id']);

        if ($new_status == 'completed' && $payment_status) {
            $order_status_id = $this->config->get('payment_kevin_completed_status_id');
            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
        } elseif ($new_status == 'pending' && $payment_status) {
            $order_status_id = $this->config->get('payment_kevin_pending_status_id');
            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
        } elseif ($new_status == 'failed' && $payment_status) {
            $order_status_id = $this->config->get('payment_kevin_failed_status_id');
            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
        }
    }

    public function autocomplete()
    {
        require_once DIR_APPLICATION.'/model/extension/payment/kevin/vendor/autoload.php';
        $clientId = $this->config->get('payment_kevin_client_id');
        $clientSecret = $this->config->get('payment_kevin_client_secret');

        $options = [
            'error' => 'array',
            'version' => $this->lib_version,
            'pluginVersion' => $this->plugin_version,
            'pluginPlatform' => 'OpenCart',
            'pluginPlatformVersion' => (string) VERSION,
        ];

        $kevinClient = new Client($clientId, $clientSecret, $options);

        $json = [];
        $banks = [];

        if (isset($this->request->get['filter_name'])) {
            $country_code = !empty($this->request->get['country_code']) ? $this->request->get['country_code'] : '';
            $filter_bank = strtolower($this->request->get['filter_name']);
            $results = $kevinClient->auth()->getBanks($country_code);

            foreach ($results['data'] as $result) {
                $result_filter = strtolower(html_entity_decode($result['name'], \ENT_QUOTES, 'UTF-8'));

                if (preg_match("/$filter_bank/i", $result_filter) && $country_code == $result['countryCode']) {
                    $banks[] = [
                        'name' => strip_tags(html_entity_decode($result['name'], \ENT_QUOTES, 'UTF-8')),
                        'id' => $result['id'],
                        'imageUri' => $result['imageUri'],
                    ];

                    $json[] = [
                        'name' => strip_tags(html_entity_decode($result['name'], \ENT_QUOTES, 'UTF-8')),
                        'country_code' => $country_code,
                        'banks' => $banks,
                        'id' => $result['id'],
                        'imageUri' => $result['imageUri'],
                    ];
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function selectCountry()
    {
        require_once DIR_APPLICATION.'/model/extension/payment/kevin/vendor/autoload.php';
        $clientId = $this->config->get('payment_kevin_client_id');
        $clientSecret = $this->config->get('payment_kevin_client_secret');

        $options = [
            'error' => 'array',
            'version' => $this->lib_version,
            'pluginVersion' => $this->plugin_version,
            'pluginPlatform' => 'OpenCart',
            'pluginPlatformVersion' => (string) VERSION,
        ];

        $kevinClient = new Client($clientId, $clientSecret, $options);

        $json = [];
        if (!$json) {
            if (isset($this->request->get['country_id'])) {
                $country_id = $this->request->get['country_id'];
                $this->load->model('localisation/country');
                $country_info = $this->model_localisation_country->getCountry($country_id);
                $selected_country_code = $country_info['iso_code_2'];

                $country_code = ['countryCode' => $selected_country_code];
                $banks = $kevinClient->auth()->getBanks($country_code);

                $json['country_code'] = $selected_country_code;

                $json['banks'] = $banks['data'];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private static function verifyTimeout($timestampTimeout, $headers)
    {
        if (!isset($headers['x-kevin-timestamp'])) {
            return false;
        }

        if ($timestampTimeout === null) {
            return true;
        }

        $timeDifference = (time() * 1000) - $headers['x-kevin-timestamp'];

        return $timestampTimeout > $timeDifference;
    }

    public function webhookRefund()
    {
        require_once DIR_APPLICATION.'/model/extension/payment/kevin/vendor/autoload.php';
        $date = new DateTime();
        date_default_timezone_set($date->getTimezone()->getName());
        $this->language->load('extension/payment/kevin');
        $this->load->model('checkout/order');
        $this->load->model('setting/setting');
        $this->load->model('extension/payment/kevin');

        $webhook_data = file_get_contents('php://input');

        if ($webhook_data !== false && !empty($webhook_data)) {
            $this->KevinRefundLog('Received kevin webhook body:'.$webhook_data);
            $get_refund_status = json_decode($webhook_data, true);
        } else {
            $this->KevinRefundLog('Payment status not received from the remote server!');
            header($this->request->server['SERVER_PROTOCOL'].' 400 ');
            exit('Webhook Refund empty.');
        }

        $refund_id = $get_refund_status['id'];
        $payment_id = $get_refund_status['paymentId'];

        // Validate Signature
        if (!empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 1) {
            $webhook_url = HTTPS_SERVER.'index.php?route=extension/payment/kevin/webhookRefund';
        } else {
            $webhook_url = HTTP_SERVER.'index.php?route=extension/payment/kevin/webhookRefund';
        }

        // function to get headers if use nginx instead of apache
        if (!function_exists('getallheaders')) {
            function getallheaders()
            {
                $get_headers = [];
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $get_headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }

                return $get_headers;
            }
        }

        $get_headers = getallheaders();

        $headers = [];
        foreach ($get_headers as $name => $value) {
            $headers[strtolower($name)] = $value;
        }

        $endpointSecret = $this->config->get('payment_kevin_client_endpointSecret');

        if (!empty($headers['x-kevin-signature']) || !empty($headers['x-kevin-timestamp'])) {
            $kevin_signature = $headers['x-kevin-signature'];
            $time_stamp = $headers['x-kevin-timestamp'];
        } else {
            $kevin_signature = false;
            $time_stamp = '';
        }

        $signature = hash_hmac('sha256', 'POST'.$webhook_url.$time_stamp.$webhook_data, $endpointSecret);

        $this->KevinRefundLog('Generated Signature: '.$signature);
        $this->KevinRefundLog('X-Kevin-Signature: '.$kevin_signature);

        // Timestamp in milliseconds
        $timestampTimeout = 300000;
        $isValid = \Kevin\SecurityManager::verifySignature($endpointSecret, $webhook_data, $headers, $webhook_url, $timestampTimeout);

        $refund_query = $this->model_extension_payment_kevin->getKevinRefunds($payment_id, $refund_id);

        if (!empty($refund_query)) {
            $order_id = $refund_query['order_id'];
            $old_status_id = $refund_query['statusGroup'];
        } else {
            $order_id = 0;
            $this->KevinRefundLog('An error occurred. The order not yet added to the database, or has been deleted... refund_id does not exist in DB.');
            header($this->request->server['SERVER_PROTOCOL'].' 400 ');
            exit('refund_id does not exist in DB. To fast response from the kevin. system.');
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $log_data = 'Answer on WebHook kevin...  Payment ID: '.$payment_id.'; Type: '.$get_refund_status['type'].'; Refund Status: '.$get_refund_status['statusGroup'].'.';

        $update_order['statusGroup'] = $get_refund_status['statusGroup'];
        $update_order['payment_id'] = $payment_id;
        $update_order['kevin_refund_id'] = $get_refund_status['id'];

        if ($old_status_id != $get_refund_status['statusGroup'] && $order_info['order_id'] == $order_id && $isValid) {
            $this->KevinRefundLog($log_data);
            $query_refunded_order_status = $this->model_extension_payment_kevin->updateWebhookKevinRefund($update_order);
            $payment_status = true;
            echo 'Signatures match.';
            $this->KevinRefundLog('Signatures match.');
            header($this->request->server['SERVER_PROTOCOL'].' 200 ');
        } else {
            if ($get_refund_status['statusGroup'] != 'completed') {
                $query_refunded_order_status = $this->model_extension_payment_kevin->updateWebhookKevinRefund($update_order); // do not change statusGroup till signatures match
            }
            $query_refunded_order_status = false;
            $payment_status = false;
            echo 'Signatures do not match.';
            $this->KevinRefundLog('Signatures do not match.');
            header($this->request->server['SERVER_PROTOCOL'].' 400 ');
        }

        $refund_query = $this->model_extension_payment_kevin->getKevinRefunds($payment_id, $refund_id);

        $refund_amount = $this->currency->format($refund_query['amount'], $refund_query['currency_code'], 1);
        // $comment = sprintf($this->language->get('text_kevin_payment_method'), ucfirst($refund_query['payment_method'])) . $bank_id . "\n";
        $comment = '';
        $comment = sprintf($this->language->get('text_amount_received'), $refund_amount).\PHP_EOL;
        $comment .= sprintf($this->language->get('text_refund_status'), ucfirst($refund_query['statusGroup'])).\PHP_EOL;
        $comment .= sprintf($this->language->get('text_payment_id'), $refund_query['payment_id']).\PHP_EOL;

        // changing order status for fully refunded order
        if ($query_refunded_order_status) {
            $this->model_checkout_order->addOrderHistory($order_id, $query_refunded_order_status, $comment, $refund_query['notify_refund']);
        }

        $store_name = $order_info['store_name'];
        $text_thank_you = sprintf($this->language->get('text_thank_you'), $store_name);
        $comment .= '<br /><br /><br />'.$text_thank_you;
        $body = nl2br($comment);

        $subject = sprintf($this->language->get('text_subject'), '&quot;'.$store_name.'&quot; ', $order_info['order_id']);

        $email_from = $this->config->get('config_email');

        if ($payment_status && $refund_query['notify_refund']) {
            $send = $this->sendMail($email_from, $subject, $body, $order_info, $store_name);
            if ($send) {
                $this->KevinRefundLog('Refund ID:'.$get_refund_status['id'].' successfully completed. Email to:'.$order_info['email'].' sent successfully.');
            } else {
                $this->KevinRefundLog('Refund ID:'.$get_refund_status['id'].' successfully completed. Customer has not been notified.');
            }
        } else {
            $this->KevinRefundLog('Refund ID:'.$get_refund_status['id'].' failed.');
        }
    }

    // Send mail
    public function sendMail($email_from, $subject, $body, $order_info, $store_name)
    {
        $this->load->model('setting/setting');

        if (is_file(DIR_IMAGE.$this->config->get('config_logo'))) {
            $logo = $this->config->get('config_url').'image/'.$this->config->get('config_logo');
        }

        if (isset($logo)) {
            $logo_view = '<img style="float: left; max-height: 40px;" src="'.$logo.'"><br /><br /><br />';
        } else {
            $logo_view = '';
        }

        $store_name = $this->config->get('config_name');

        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $message .= '<html dir="ltr" lang="en">'."\n";
        $message .= '  <head>'."\n";
        $message .= '    <title> <h4>'.$subject.'</h4></title>'."\n";
        $message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
        $message .= '  </head>'."\n";
        $message .= '  <body>';
        $message .= $logo_view.'<br /><br /><br />';
        $message .= html_entity_decode($body, \ENT_QUOTES, 'UTF-8');
        $message .= '</body>'."\n";
        $message .= '</html>'."\n";

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), \ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($order_info['email']);
        $mail->setFrom($email_from);
        $mail->setSender(html_entity_decode($store_name, \ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, \ENT_QUOTES, 'UTF-8'));
        $mail->setHtml($message);
        $mail->send();

        return true;
    }

    /* refund log */
    public function KevinRefundLog($log_data)
    {
        if ($this->config->get('payment_kevin_log')) {
            $kevin_log = new Log('kevin_refund.log');
            $kevin_log->write($log_data);
        } else {
        }
    }

    /* payment log */
    public function KevinLog($log_data)
    {
        if ($this->config->get('payment_kevin_log')) {
            $kevin_log = new Log('kevin_payment.log');
            $kevin_log->write($log_data);
        } else {
        }
    }
}
