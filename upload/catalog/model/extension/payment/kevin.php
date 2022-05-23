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

class ModelExtensionPaymentKevin extends Model
{
    private $lib_version = '0.3';
    private $plugin_version = '1.0.1.5';

    public function getMethod($address, $total)
    {
        unset($this->session->data['iso_code_2']);
        $this->load->language('extension/payment/kevin');
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

        $project_settings = $kevinClient->auth()->getProjectSettings();

        if (!empty($project_settings['error']['code']) && ($project_settings['error']['code'] == '401' || $project_settings['error']['code'] == '400')) {
            $status = false;

            return;
        }

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."zone_to_geo_zone WHERE geo_zone_id = '".(int) $this->config->get('payment_kevin_geo_zone_id')."' AND country_id = '".(int) $address['country_id']."' AND (zone_id = '".(int) $address['zone_id']."' OR zone_id = '0')");

        if ($this->config->get('payment_kevin_total') > 0 && $this->config->get('payment_kevin_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('payment_kevin_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $current_country_code = $address['iso_code_2'];
        $this->session->data['iso_code_2'] = $address['iso_code_2'];

        $payment_methods = $project_settings['paymentMethods'];

        $contries = $kevinClient->auth()->getCountries();
        $countryCodes = $contries['data'];
        if (in_array($current_country_code, $countryCodes) || in_array('card', $payment_methods) && $status) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = [];
        $this->load->model('localisation/language');
        $current_language = $this->config->get('config_language_id');
        if (!empty($this->config->get('payment_kevin_image_width'))) {
            $logo_width = 'max-width:'.$this->config->get('payment_kevin_image_width').'px';
        } else {
            $logo_width = 'width: auto';
        }
        if (!empty($this->config->get('payment_kevin_image_height'))) {
            $logo_height = 'max-height: '.$this->config->get('payment_kevin_image_height').'px;';
        } else {
            $logo_height = 'height: auto;';
        }

        if (is_file(DIR_IMAGE.$this->config->get('payment_kevin_image'))) {
            $kevin_image = '<img src="'.$this->config->get('config_url').'image/'.$this->config->get('payment_kevin_image').'" title="'.$this->config->get('payment_kevin_title'.$current_language).'" style="margin-top: -3px;'.$logo_height.$logo_width.'"/>&nbsp;&nbsp;';
        } else {
            $kevin_image = '';
        }

        if ($this->config->get('payment_kevin_position') == 'right') {
            $title = $this->config->get('payment_kevin_title'.$current_language).'&nbsp;&nbsp;'.$kevin_image;
        } else {
            $title = $kevin_image.$this->config->get('payment_kevin_title'.$current_language);
        }

        if ($status) {
            $method_data = [
                'code' => 'kevin',
                'title' => $title,
                'terms' => '',
                'sort_order' => $this->config->get('payment_kevin_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addKevinOrder($data)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."kevin_order` SET
		`order_id` = '".(int) $data['order_id']."',
		`payment_id` = '".$this->db->escape($data['payment_id'])."',
		`bank_id` = '".$this->db->escape($data['bank_id'])."',
		`status` = '".$this->db->escape($data['status'])."',
		`payment_method` = '".$this->db->escape($data['payment_method'])."',
		`statusGroup` = '".$this->db->escape($data['statusGroup'])."',
		`order_status_id` = '".(int) $data['order_status_id']."',
		`ip_address` = '".$this->db->escape($data['ip_address'])."',
		`date_added` = now(),
		`date_modified` = now(),
		`currency_code` = '".$this->db->escape($data['currency_code'])."',
		`total` = '".(float) $data['total']."'");
    }

    public function updateWebhookKevinOrder($data)
    {
        $this->db->query('UPDATE `'.DB_PREFIX."kevin_order` SET
		`status` = '".$this->db->escape($data['status'])."',
		`statusGroup` = '".$this->db->escape($data['statusGroup'])."',
		`order_status_id` = '".(int) $data['order_status_id']."',
		`date_modified` = NOW()
         WHERE `payment_id` = '".$this->db->escape($data['payment_id'])."'");
    }

    public function updateSignatureKevinOrder($data)
    {
        $this->db->query('UPDATE `'.DB_PREFIX."kevin_order` SET
		`order_status_id` = '".(int) $data['order_status_id']."',
		`date_modified` = NOW()
         WHERE `payment_id` = '".$this->db->escape($data['payment_id'])."'");
    }

    public function getKevinOrders($payment_id)
    {
        $kevin_order = $this->db->query('SELECT * FROM `'.DB_PREFIX."kevin_order` WHERE `payment_id` = '".$this->db->escape($payment_id)."'");
        if ($kevin_order->num_rows) {
            return $kevin_order->row;
        } else {
            return false;
        }
    }

    public function getKevinRefunds($payment_id, $refund_id)
    {
        $kevin_order = $this->db->query('SELECT * FROM `'.DB_PREFIX."kevin_refund` WHERE `payment_id` = '".$this->db->escape($payment_id)."' AND `kevin_refund_id` = '".(int) $refund_id."'");
        if ($kevin_order->num_rows) {
            return $kevin_order->row;
        } else {
            return false;
        }
    }

    public function updateWebhookKevinRefund($data)
    {
        $order_status = false;
        $query_refunded = $this->db->query('SELECT SUM(kr.amount) total_amount, ko.total, ko.currency_code FROM '.DB_PREFIX.'kevin_order ko LEFT JOIN '.DB_PREFIX."kevin_refund kr ON (kr.order_id = ko.order_id) WHERE ko.payment_id = '".$this->db->escape($data['payment_id'])."'");

        $total_amount = number_format((float) $query_refunded->row['total_amount'], 2, '.', '');
        $total = number_format((float) $query_refunded->row['total'], 2, '.', '');

        if ($data['statusGroup'] != 'completed') {
            $this->db->query('UPDATE '.DB_PREFIX."kevin_order SET refund_action_id = '".(int) $this->config->get('payment_kevin_created_action_id')."' WHERE payment_id = '".$this->db->escape($data['payment_id'])."'");
        } elseif ($total_amount == $total && $data['statusGroup'] == 'completed') {
            $this->db->query('UPDATE '.DB_PREFIX."kevin_order SET refund_action_id = '".(int) $this->config->get('payment_kevin_refunded_action_id')."' WHERE payment_id = '".$this->db->escape($data['payment_id'])."'");
            $order_status = $this->config->get('payment_kevin_refunded_status_id');
        } else {
            $this->db->query('UPDATE '.DB_PREFIX."kevin_order SET refund_action_id = '".(int) $this->config->get('payment_kevin_partial_refund_action_id')."' WHERE payment_id = '".$this->db->escape($data['payment_id'])."'");
            $order_status = $this->config->get('payment_kevin_partial_refund_status_id');
        }

        $this->db->query('UPDATE `'.DB_PREFIX."kevin_refund` SET
		`statusGroup` = '".$this->db->escape($data['statusGroup'])."',
		`date_modified` = NOW()
         WHERE `kevin_refund_id` = '".(int) $data['kevin_refund_id']."'
		 AND `payment_id` = '".$this->db->escape($data['payment_id'])."'");

        return $order_status;
    }
}
