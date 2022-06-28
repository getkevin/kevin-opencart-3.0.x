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

class ControllerExtensionModuleKevinRefund extends Controller
{
    private $error = [];

    private $lib_version = '0.3';
    private $plugin_version = KEVIN_VERSION;

    public function install()
    {
        $this->load->model('setting/setting');

        if ($this->validate()) {
            $this->model_setting_setting->editSetting('module_kevin_refund', ['module_kevin_refund_status' => 1]);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=payment', true));
        }
    }

    public function index()
    {
        $this->load->model('extension/payment/kevin');
        // checking if kevin DB is updated on module update/reinstall.
        $DB_query = $this->model_extension_payment_kevin->checkKevinDB();

        if ($DB_query) {
            $this->model_extension_payment_kevin->install();
        }

        $data['payment_kevin_refund_status'] = $this->config->get('payment_kevin_refund_status');
        $data['payment_kevin_status'] = $this->config->get('payment_kevin_status');

        $this->load->language('extension/module/kevin_refund');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/kevin_refund');

        $this->getList();
    }

    protected function kevinClient()
    {
        require_once DIR_CATALOG.'/model/extension/payment/kevin/vendor/autoload.php';
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

        return $kevinClient;
    }

    protected function getList()
    {
        $date = new DateTime();
        date_default_timezone_set($date->getTimezone()->getName());

        $kevinClient = $this->kevinClient();
        $project = $kevinClient->auth()->getProjectSettings();

        $data['text_sandbox_alert'] = '';
        $data['error_client'] = '';

        if (empty($project['allowedRefundsFor'])) {
            if (!empty($project['error']['code'])) {
                $data['error_client'] = 'Can not connect to <span style="font-weight: 600; color:red;">kevin. </span> Error: '.$project['error']['name'].'.  Error code: '.$project['error']['code'];
                $this->KevinRefundLog('Can not connect to kevin.  Error: '.$project['error']['name'].'.  Error code: '.$project['error']['code']);
            }
            $project_settings = false;
        } else {
            $payment_methods = $project['allowedRefundsFor'];
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

            $refunds = $payment_methods;
            if ($refunds) {
                usort($refunds, 'cmp');
            }
            if (!empty($project['isSandbox']) && $project['isSandbox']) {
                $data['text_sandbox_alert'] = $this->language->get('text_sandbox_alert');
            }
            $project_settings = $project;
        }

        if (isset($this->session->data['error_refund'])) {
            $data['error_refund'] = $this->session->data['error_refund'];
            unset($this->session->data['error_refund']);
        } else {
            $data['error_refund'] = '';
        }

        if (isset($this->session->data['success_refund'])) {
            $data['success_refund'] = $this->session->data['success_refund'];
            unset($this->session->data['success_refund']);
        } else {
            $data['success_refund'] = '';
        }

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], \ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token='.$this->session->data['user_token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url, true),
        ];

        $data['orders'] = [];

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_order_status' => $filter_order_status,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $order_total = $this->model_extension_module_kevin_refund->getTotalOrders($filter_data);

        if (empty($refunds)) {
            $results = [];
            $data['refund_warning'] = $this->language->get('text_refund_warning');
        } else {
            $data['refund_warning'] = '';
            $results = $this->model_extension_module_kevin_refund->getOrders($filter_data);
        }

        foreach ($results as $result) {
            $product_results = $this->model_extension_module_kevin_refund->getOrderProducts($result['order_id']);

            $products = [];
            foreach ($product_results as $product) {
                $product_quantity = $product['quantity'];
                $quantity_array = [];
                for ($i = 0; $i <= $product['quantity']; ++$i) {
                    $quantity_array[$i] = $i;
                }

                $price = $this->currency->convert((float) ($product['price'] + $product['tax']), $this->config->get('config_currency'), $result['currency_code']);
                $products[] = [
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'price' => $price,
                    'quantity_array' => $quantity_array,
                    'pr_quantity' => $product_quantity,
                ];
            }

            // Checking if refund is still available on project setting days limit
            $date = new DateTime('now');
            $date = $date->format('Y-m-d H:i:s');

            if ((!empty($project_settings['cardRefundDayLimit']) || !empty($project_settings['bankRefundDayLimit'])) && ($project_settings['cardRefundDayLimit'] || $project_settings['bankRefundDayLimit'])) {
                $refund_limit = $project_settings['bankRefundDayLimit'] ?: $project_settings['cardRefundDayLimit'];
            } else {
                $refund_limit = 0;
            }

            if (!empty($result['date_added']) && $refund_limit) {
                $date_added = new DateTime($result['date_added']);
                $date_added->modify($refund_limit.'days');
                $date_added = $date_added->format('Y-m-d H:i:s');

                if (strtotime($date_added) <= strtotime($date)) {
                    $refund_available = true;
                } else {
                    $refund_available = false;
                }
            } else {
                $refund_available = true;
            }
            $query_refunded = $this->model_extension_module_kevin_refund->getRefundOrderAmount($result['order_id']);

            $currency_value = 1;

            $amount_available = $result['kevin_total'] - $query_refunded['total_amount'];

            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'products' => $products,
                'payment_id' => $result['payment_id'],
                'customer' => $result['customer'],
                'order_status' => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
                'total' => $this->currency->format((float) $result['kevin_total'], $result['currency_code'], $currency_value),
                'amount_0' => $this->currency->format('0', $result['currency_code'], $currency_value),
                'amount' => $this->currency->format((float) $query_refunded['total_amount'], $result['currency_code'], $currency_value),
                'amount_available' => $this->currency->format((float) $amount_available, $result['currency_code'], $currency_value),
                'refund_available' => $refund_available,
                'currency_symbol_left' => $this->currency->getSymbolLeft($result['currency_code']),
                'currency_symbol_right' => $this->currency->getSymbolRight($result['currency_code']),
                'reason' => strip_tags($result['reason']),
                'refund_status' => strip_tags($result['statusGroup']),
                'refund_action' => $result['return_action'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                'view' => $this->url->link('extension/module/kevin_refund/info', 'user_token='.$this->session->data['user_token'].'&order_id='.$result['order_id'].$url, true),
                'refund_kevin' => $this->url->link('extension/module/kevin_refund/refundKevin', 'user_token='.$this->session->data['user_token'].'&order_id='.$result['order_id'].$url, true),
            ];
        }

        $this->document->setTitle(strip_tags($this->language->get('heading_title')));

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], \ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }
        /*
        if (isset($this->request->get['filter_total_amount'])) {
            $url .= '&filter_total_amount=' . $this->request->get['filter_total_amount'];
        }
*/
        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=o.order_id'.$url, true);
        $data['sort_customer'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=customer'.$url, true);
        $data['sort_status'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=order_status'.$url, true);
        $data['sort_total'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=o.total'.$url, true);
        $data['sort_total_amount'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=total_amount'.$url, true);
        $data['sort_amount_available'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=amount_available'.$url, true);
        $data['sort_refund_action'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=return_action'.$url, true);
        $data['sort_date_added'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=o.date_added'.$url, true);
        $data['sort_date_modified'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].'&sort=o.date_modified'.$url, true);

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], \ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if ($project_settings) {
            $order_total = $order_total;
        } else {
            $order_total = 0;
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url.'&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/kevin_refund_list', $data));
    }

    public function info()
    {
        $kevinClient = $this->kevinClient();

        $project = $kevinClient->auth()->getProjectSettings();

        if (empty($project['allowedRefundsFor'])) {
            $data['error_client'] = 'Can not connect to <span style="font-weight: 600; color:red;">kevin. </span> Error: '.$project['error']['name'].'.  Error code: '.$project['error']['code'];
            $project_settings = false;
        } else {
            $data['error_client'] = '';
            $project_settings = $project;
        }

        $this->load->model('sale/order');
        $this->load->model('catalog/product');
        $this->load->model('extension/module/kevin_refund');
        $this->load->language('extension/module/kevin_refund');

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], \ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_sale_order->getOrder($order_id);

        // Kevin refund
        if ($order_info) {
            $query_refund = $this->model_extension_module_kevin_refund->getRefundOrder($order_id);

            $data['order_id'] = $order_id;
            $data['refund_kevin'] = $this->url->link('extension/module/kevin_refund/refundKevin', 'user_token='.$this->session->data['user_token'].'&refund_info=1&order_id='.$order_id.$url, true);
            $data['total'] = $query_refund['total'];
            $data['amount_0'] = $this->currency->format('0', $order_info['currency_code'], $order_info['currency_value']);

            $query_refunded = $this->model_extension_module_kevin_refund->getRefundOrderAmount($order_id);

            $data['amount'] = $this->currency->format($query_refunded['total_amount'], $query_refund['currency_code'], 1);

            $amount_available = $query_refund['total'] - $query_refunded['total_amount'];
            $data['payment_id'] = $query_refund['payment_id'];
            $data['amount_available'] = $this->currency->format((float) $amount_available, $order_info['currency_code'], 1);

            $product_results = $this->model_extension_module_kevin_refund->getOrderProducts($order_id);

            $data['refund_products'] = [];

            foreach ($product_results as $product) {
                $product_quantity = $product['quantity'];
                $quantities = [];
                for ($i = 0; $i <= $product['quantity']; ++$i) {
                    $quantities[$i] = $i;
                }
                $price = $this->currency->convert((float) ($product['price'] + $product['tax']), $this->config->get('config_currency'), $order_info['currency_code']);
                $data['refund_products'][] = [
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'price' => $price,
                    'quantities' => $quantities,
                    'pr_quantity' => $product_quantity,
                ];
            }

            $refund_results = $this->model_extension_module_kevin_refund->getRefundedOrder($order_id);

            $data['refunds'] = [];
            $total_amount = 0;
            foreach ($refund_results as $result) {
                $currency_value = 1;

                $total = $this->currency->convert((float) ($query_refund['total']), $this->config->get('config_currency'), $result['currency_code']);

                $total_amount = $result['amount'];
                if ($result['statusGroup'] != 'completed') {
                    $refunded_amount = $total_amount - $result['amount'];
                } else {
                    $refunded_amount = $total_amount;
                }

                $amount_available = $total - $total_amount;
                $data['refunds'][] = [
                    'order_id' => $order_id,
                    'kevin_refund_id' => $result['kevin_refund_id'],
                    'payment_id' => $result['payment_id'],
                    'total' => $this->currency->format((float) $total, $order_info['currency_code'], $currency_value),
                    'amount_0' => $this->currency->format('0', $order_info['currency_code'], $currency_value),
                    'refunded_amount' => $this->currency->format((float) $refunded_amount, $result['currency_code'], $currency_value),
                    'amount' => $this->currency->format((float) ($result['amount'] - $refunded_amount), $result['currency_code'], $currency_value),
                    'amount_available' => $this->currency->format((float) $amount_available, $order_info['currency_code'], $currency_value),
                    'reason' => strip_tags($result['reason']),
                    'refund_status' => strip_tags($result['statusGroup']),
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                    'view' => $this->url->link('extension/module/kevin_refund/info', 'user_token='.$this->session->data['user_token'].'&order_id='.$result['order_id'].$url, true),
                ];
            }

            $data['currency_symbol_left'] = $this->currency->getSymbolLeft($order_info['currency_code']);

            $data['currency_symbol_right'] = $this->currency->getSymbolRight($order_info['currency_code']);

            if (isset($this->session->data['error_refund'])) {
                $data['error_refund'] = $this->session->data['error_refund'];
                unset($this->session->data['error_refund']);
            } else {
                $data['error_refund'] = '';
            }

            if (isset($this->session->data['success_refund'])) {
                $data['success_refund'] = $this->session->data['success_refund'];
                unset($this->session->data['success_refund']);
            } else {
                $data['success_refund'] = '';
            }

            // restocked products
            $this->load->model('tool/image');

            $results = $this->model_extension_module_kevin_refund->getRestockedProducts($order_id);
            $data['restocked_products'] = [];

            foreach ($results as $result) {
                if (is_file(DIR_IMAGE.$result['image'])) {
                    $image = $this->model_tool_image->resize($result['image'], 40, 40);
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                }

                $special = false;

                $product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

                foreach ($product_specials  as $product_special) {
                    if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
                        $special = $product_special['price'];

                        break;
                    }
                }

                $data['restocked_products'][] = [
                    'product_id' => $result['product_id'],
                    'image' => $image,
                    'name' => $result['name'],
                    'model' => $result['model'],
                    'price' => $result['price'],
                    'special' => $special,
                    'quantity' => $result['quantity'],
                    'restocked_quantity' => $result['restocked_quantity'],
                    'status' => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'edit' => $this->url->link('catalog/product/edit', 'user_token='.$this->session->data['user_token'].'&product_id='.$result['product_id'].$url, true),
                ];
            }

            $this->document->setTitle($this->language->get('heading_title'));
            $data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);

            $this->document->setTitle(strip_tags($this->language->get('heading_title')));

            $url = '';

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_customer'])) {
                $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], \ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_status'])) {
                $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
            }

            if (isset($this->request->get['filter_total'])) {
                $url .= '&filter_total='.$this->request->get['filter_total'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_modified'])) {
                $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token='.$this->session->data['user_token'], true),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url, true),
            ];

            $data['cancel'] = $this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url, true);

            $data['user_token'] = $this->session->data['user_token'];

            $data['order_id'] = $this->request->get['order_id'];

            $data['store_id'] = $order_info['store_id'];
            $data['store_name'] = $order_info['store_name'];

            if ($order_info['store_id'] == 0) {
                $data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
            } else {
                $data['store_url'] = $order_info['store_url'];
            }

            if ($order_info['invoice_no']) {
                $data['invoice_no'] = $order_info['invoice_prefix'].$order_info['invoice_no'];
            } else {
                $data['invoice_no'] = '';
            }

            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

            $data['firstname'] = $order_info['firstname'];
            $data['lastname'] = $order_info['lastname'];

            if ($order_info['customer_id']) {
                $data['customer'] = $this->url->link('customer/customer/edit', 'user_token='.$this->session->data['user_token'].'&customer_id='.$order_info['customer_id'], true);
            } else {
                $data['customer'] = '';
            }

            $this->load->model('customer/customer_group');

            $customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

            if ($customer_group_info) {
                $data['customer_group'] = $customer_group_info['name'];
            } else {
                $data['customer_group'] = '';
            }

            $data['email'] = $order_info['email'];
            $data['telephone'] = $order_info['telephone'];

            $data['shipping_method'] = $order_info['shipping_method'];
            $data['payment_method'] = $order_info['payment_method'];

            // Uploaded files
            $this->load->model('tool/upload');

            $data['products'] = [];

            $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {
                $option_data = [];

                $options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => $option['value'],
                            'type' => $option['type'],
                        ];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $option_data[] = [
                                'name' => $option['name'],
                                'value' => $upload_info['name'],
                                'type' => $option['type'],
                                'href' => $this->url->link('tool/upload/download', 'user_token='.$this->session->data['user_token'].'&code='.$upload_info['code'], true),
                            ];
                        }
                    }
                }

                $data['products'][] = [
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'href' => $this->url->link('catalog/product/edit', 'user_token='.$this->session->data['user_token'].'&product_id='.$product['product_id'], true),
                ];
            }

            $data['vouchers'] = [];

            $vouchers = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

            foreach ($vouchers as $voucher) {
                $data['vouchers'][] = [
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
                    'href' => $this->url->link('sale/voucher/edit', 'user_token='.$this->session->data['user_token'].'&voucher_id='.$voucher['voucher_id'], true),
                ];
            }

            $data['totals'] = [];

            $totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                ];
            }

            $data['comment'] = nl2br($order_info['comment']);

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('extension/module/kevin_refund_info', $data));
        } else {
            return new Action('error/not_found');
        }
    }

    public function checkQuantity()
    {
        $this->load->model('extension/module/kevin_refund');
        $this->load->model('sale/order');
        $json = [];
        if (isset($this->request->get['order_id']) && isset($this->request->get['products'])) {
            $get_products = json_decode(html_entity_decode($this->request->get['products']), true);
            $restock = [];
            $product = [];
            $restock_quantity = [];

            foreach ($get_products as $v) {
                foreach ($v as $v1) {
                    foreach ($v1 as $v2) {
                        if (!is_array($v2)) {
                            $restock_quantity = ['restock_quantity' => $v2];
                        } else {
                            $product = $v2;
                        }
                    }
                }
                $restock[] = array_merge($product, $restock_quantity);
            }

            $json['order_id'] = $this->request->get['order_id'];
            $product_query = $this->model_extension_module_kevin_refund->getRestockedProducts($this->request->get['order_id']);

            $query_currency = $this->model_extension_module_kevin_refund->getKevinOrderCurrency($this->request->get['order_id']);

            $products = array_replace_recursive($product_query, $restock);
            $refund_amount = 0;
            $value['name'] = '';
            $product_array = [];

            foreach ($products as $value) {
                $restocked_quantity = !empty($value['restocked_quantity']) ? $value['restocked_quantity'] : '';
                $ordered_quantity = !empty($value['ordered_quantity']) ? $value['ordered_quantity'] : 0;
                $restock_quantity = !empty($value['restock_quantity']) ? $value['restock_quantity'] : 0;
                $name = !empty($value['name']) ? $value['name'] : '';

                if ($value['restock_quantity'] == 0) {
                    $value['restock_quantity'] = 0;
                }
                $product_array[] = [
                        'order_id' => $this->request->get['order_id'],
                        'product_id' => $value['product_id'],
                        'name' => $name,
                        'restocked_quantity' => $restocked_quantity,
                        'ordered_quantity' => $ordered_quantity,
                        'restock_quantity' => $restock_quantity,
                    ];

                if (($ordered_quantity < ((int) $restocked_quantity + (int) $restock_quantity)) && empty((int) $restock_quantity)) {
                    $refund_amount += 0 * $value['price'];
                } else {
                    $refund_amount += $value['restock_quantity'] * $value['price'];
                }
            }
            $json['products'] = $product_array;
            $json['refund_amount'] = $refund_amount;

            $json['refund_amount_text'] = $this->currency->format($refund_amount, $query_currency['currency_code'], 1);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function refundKevin()
    {
        $date = new DateTime();
        date_default_timezone_set($date->getTimezone()->getName());

        $kevinClient = $this->kevinClient();

        $project_settings = $kevinClient->auth()->getProjectSettings();

        $refunds = !empty($project_settings['allowedRefundsFor']) ? $project_settings['allowedRefundsFor'] : '';
        if (empty($refunds)) {
            $this->session->data['error_refund'] = $this->language->get('error_auth_refund');
            $this->response->redirect($this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'], true));
            $this->KevinRefundLog('Authorized error to make refunds.');
        }

        $this->load->model('sale/order');
        $this->load->model('extension/module/kevin_refund');
        $this->load->language('extension/module/kevin_refund');
        $this->load->model('localisation/language');

        if (!empty($this->request->get['refund_info'])) {
            $refund_info = true;
        } else {
            $refund_info = false;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], \ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        if (!empty($this->request->get['order_id']) && !empty($this->request->post['payment_id'])) {
            $order_id = $this->request->get['order_id'];

            if (empty($this->request->post['kevin_refund_amount'])) {
                $this->session->data['error_refund'] = $this->language->get('error_amount_refund');
                $this->KevinRefundLog('An error occurred! Refund amount was not entered.');
                if ($refund_info) {
                    $this->response->redirect($this->url->link('extension/module/kevin_refund/info&order_id='.$order_id, 'user_token='.$this->session->data['user_token'], true));
                } else {
                    $this->response->redirect($this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url, true));
                }
            }

            $paymentId = $this->request->post['payment_id'];

            $kevin_order_info = $this->model_extension_module_kevin_refund->getKevinOrder($paymentId);

            $refund_amount = str_replace(',', '.', $this->request->post['kevin_refund_amount']);

            if (!empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 1) {
                $webhook_url = HTTPS_CATALOG.'index.php?route=extension/payment/kevin/webhookRefund';
            } else {
                $webhook_url = HTTP_CATALOG.'index.php?route=extension/payment/kevin/webhookRefund';
            }

            $init_attribute = [
                'amount' => $refund_amount,
                'Webhook-URL' => $webhook_url,
            ];

            $init_refund = $kevinClient->payment()->initiatePaymentRefund($paymentId, $init_attribute);

            $get_payment_refunds = $kevinClient->payment()->getPaymentRefunds($paymentId);

            $order_info = $this->model_sale_order->getOrder($order_id);

            if (!empty($init_refund['error']['code'])) {
                $this->session->data['error_refund'] = 'An error occurred! Code: '.$init_refund['error']['code'].' Error Description: '.$init_refund['error']['description'];
                $this->KevinRefundLog($this->session->data['error_refund']);
                if ($refund_info) {
                    $this->response->redirect($this->url->link('extension/module/kevin_refund/info&order_id='.$order_id, 'user_token='.$this->session->data['user_token'], true));
                } else {
                    $this->response->redirect($this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url, true));
                }
            }

            if (isset($this->request->post['refund_restock'])) {
                $restock = $this->request->post['kevin_refund_restock'];
            } else {
                $restock = false;
            }

            $refund['payment_id'] = $paymentId;
            $refund['order_id'] = $order_id;
            $refund['refund_amount'] = !empty($init_refund['amount']) ? $init_refund['amount'] : 0;
            $refund['statusGroup'] = !empty($init_refund['statusGroup']) ? $init_refund['statusGroup'] : '';
            $refund['kevin_refund_id'] = !empty($init_refund['id']) ? $init_refund['id'] : 0;
            $refund['restock'] = !empty($this->request->post['kevin_refund_restock']) ? $this->request->post['kevin_refund_restock'] : [];
            if (isset($this->request->post['kevin_refund_reason'])) {
                $refund['reason'] = $this->request->post['kevin_refund_reason'];
            } else {
                $refund['reason'] = '';
            }
            $refund['refund_amount'] = $refund_amount;
            $refund['currency_code'] = $kevin_order_info['currency_code'];

            if (!empty($this->request->post['notify_refund'])) {
                $refund['notify_refund'] = $this->request->post['notify_refund'];
            } else {
                $refund['notify_refund'] = 0;
            }

            $refunded_amount = $this->currency->format($refund['refund_amount'], $kevin_order_info['currency_code'], 1);

            $this->model_extension_module_kevin_refund->addKevinRefund($refund);

            $this->session->data['success_refund'] = sprintf($this->language->get('text_refund_prepare'), $refunded_amount, $refund['statusGroup'], $order_id);
            $this->KevinRefundLog($this->session->data['success_refund']);

            if ($refund_info) {
                $this->response->redirect($this->url->link('extension/module/kevin_refund/info&order_id='.$order_id, 'user_token='.$this->session->data['user_token'], true));
            } else {
                $this->response->redirect($this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url, true));
            }
        } else {
            $this->session->data['error_refund'] = $this->language->get('error_field_refund');

            if ($refund_info) {
                $this->response->redirect($this->url->link('extension/module/kevin_refund/info&order_id='.$order_id, 'user_token='.$this->session->data['user_token'], true));
            } else {
                $this->response->redirect($this->url->link('extension/module/kevin_refund', 'user_token='.$this->session->data['user_token'].$url, true));
            }
        }
    }

    // refund log
    public function KevinRefundLog($log_data)
    {
        if ($this->config->get('payment_kevin_log')) {
            $kevin_log = new Log('kevin_refund.log');
            $kevin_log->write($log_data);
        } else {
        }
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/kevin_refund')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
