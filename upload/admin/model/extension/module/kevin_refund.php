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
*  @author 2021 kevin. <help@kevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
class ModelExtensionModuleKevinRefund extends Model
{
    public function addKevinRefund($data)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."kevin_refund` SET payment_id = '".$this->db->escape($data['payment_id'])."', order_id = '".(int) $data['order_id']."', amount = '".(float) $data['refund_amount']."', currency_code = '".$this->db->escape($data['currency_code'])."', statusGroup = '".$this->db->escape($data['statusGroup'])."', kevin_refund_id = '".(int) $data['kevin_refund_id']."', notify_refund = '".(int) $data['notify_refund']."', reason = '".$this->db->escape($data['reason'])."', date_added = NOW(), date_modified = NOW()");

        $query_refunded = $this->db->query('SELECT SUM(kr.amount) total_amount, ko.total, ko.currency_code FROM '.DB_PREFIX.'kevin_order ko LEFT JOIN '.DB_PREFIX."kevin_refund kr ON (kr.order_id = ko.order_id) WHERE ko.payment_id = '".$this->db->escape($data['payment_id'])."'");

        $total_amount = number_format((float) $query_refunded->row['total_amount'], 2, '.', '');
        $total = number_format((float) $query_refunded->row['total'], 2, '.', '');

        if ('completed' != $data['statusGroup']) {
            $this->db->query('UPDATE '.DB_PREFIX."kevin_order SET refund_action_id = '".(int) $this->config->get('payment_kevin_created_action_id')."' WHERE payment_id = '".$this->db->escape($data['payment_id'])."'");
        } elseif ($total_amount == $total) {
            $this->db->query('UPDATE '.DB_PREFIX."kevin_order SET refund_action_id = '".(int) $this->config->get('payment_kevin_refunded_action_id')."' WHERE payment_id = '".$this->db->escape($data['payment_id'])."'");
        } else {
            $this->db->query('UPDATE '.DB_PREFIX."kevin_order SET refund_action_id = '".(int) $this->config->get('payment_kevin_partial_refund_action_id')."' WHERE payment_id = '".$this->db->escape($data['payment_id'])."'");
        }

        if ($data['restock']) {
            foreach ($data['restock'] as $product_id => $quantity) {
                if ($quantity) {
                    $this->db->query('UPDATE '.DB_PREFIX."product SET quantity = quantity + '".(int) $quantity."' WHERE product_id = '".(int) $product_id."'");
                    $this->db->query('UPDATE '.DB_PREFIX."kevin_refund SET restocked_products = '".$this->db->escape(json_encode($data['restock']))."' WHERE kevin_refund_id = '".(int) $data['kevin_refund_id']."'");
                }
            }
        }
    }

    public function getOrders($data = [])
    {
        $sql = "SELECT  o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS order_status, o.shipping_code, o.total, ko.currency_code, o.currency_value, o.date_added, o.date_modified, ko.order_id, kr.reason, kr.statusGroup, (SELECT ra.name FROM ".DB_PREFIX."return_action ra WHERE ra.return_action_id = ko.refund_action_id AND ra.language_id = '".(int) $this->config->get('config_language_id')."') AS return_action, sum(kr.amount) total_amount, CASE WHEN sum(kr.amount) IS NULL THEN ko.total ELSE (ko.total - sum(kr.amount)) END AS amount_available, ko.payment_id, ko.total AS kevin_total FROM `".DB_PREFIX.'kevin_order` ko LEFT JOIN `'.DB_PREFIX.'kevin_refund` kr ON (kr.order_id = ko.order_id) LEFT JOIN `'.DB_PREFIX."order` o ON (o.order_id = ko.order_id) AND ko.statusGroup = 'completed' ";

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode) {
                $sql .= ' WHERE ('.implode(' OR ', $implode).')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        }
        /*
        if (!empty($data['filter_total'])) {
            $sql .= " AND total_amount = '" . (float)$data['filter_total_amount'] . "'";
        }
*/
        $sort_data = [
            'o.order_id',
            'customer',
            'order_status',
            'return_action',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'total_amount',
            'amount_available',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' GROUP BY ko.order_id ORDER BY '.$data['sort'];
        } else {
            $sql .= ' GROUP BY ko.order_id ORDER BY o.order_id';
        }

        // $sql .= " GROUP BY ko.order_id ";

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getRefundOrder($order_id)
    {
        $query = $this->db->query('SELECT ko.order_id, ko.payment_id, SUM(kr.amount) total_amount, ko.total, ko.currency_code FROM '.DB_PREFIX.'kevin_order ko LEFT JOIN '.DB_PREFIX."kevin_refund kr ON (kr.order_id = ko.order_id) WHERE ko.order_id = '".(int) $order_id."'");

        return $query->row;
    }

    public function getRefundOrderAmount($order_id)
    {
        $query = $this->db->query("SELECT ko.order_id, ko.payment_id, CASE WHEN kr.statusGroup = 'completed' THEN SUM(kr.amount) ELSE (SUM(kr.amount) - kr.amount) END AS total_amount, ko.total, ko.currency_code FROM ".DB_PREFIX.'kevin_order ko LEFT JOIN '.DB_PREFIX."kevin_refund kr ON (kr.order_id = ko.order_id) WHERE ko.order_id = '".(int) $order_id."' AND kr.statusGroup = 'completed'");

        return $query->row;
    }

    public function getKevinOrder($payment_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."kevin_order WHERE payment_id = '".$this->db->escape($payment_id)."'");

        return $query->row;
    }

    public function getKevinOrderCurrency($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."kevin_order WHERE order_id = '".(int) $order_id."'");

        return $query->row;
    }

    public function getRefundedOrder($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."kevin_refund WHERE order_id = '".(int) $order_id."' ORDER BY kevin_refund_id  DESC ");

        return $query->rows;
    }

    public function getOrderProducts($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

        return $query->rows;
    }

    public function getRestockedProducts($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."kevin_refund WHERE order_id = '".(int) $order_id."'");

        $restocked = [];
        foreach ($query->rows as $value) {
            if (!empty($value['restocked_products'])) {
                $product_id_values = json_decode($value['restocked_products'], true);
                $keys = [];
                $values = [];
                foreach ($product_id_values as $key => $value) {
                    $keys[] = $key;
                    $values[] = $value;
                }
                $restocked[] = array_combine($keys, $values);
            }
        }

        $restocked_product_ids = [];
        array_walk_recursive($restocked, function ($item, $key) use (&$restocked_product_ids) {
            $restocked_product_ids[$key] = isset($restocked_product_ids[$key]) ? $item + $restocked_product_ids[$key] : $item;
        });

        $restocked_products = [];
        foreach ($restocked_product_ids as $product_id => $value) {
            if (!empty($value) || 0 == $value) {
                $query_products = $this->db->query('SELECT p.product_id, p.image, pd.name, p.model, p.price, p.quantity, p.status   FROM '.DB_PREFIX.'product p  LEFT JOIN '.DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int) $product_id."' AND pd.language_id = '".(int) $this->config->get('config_language_id')."' ");

                $ordered_quantity = $this->db->query('SELECT quantity FROM `'.DB_PREFIX."order_product`  WHERE product_id = '".(int) $product_id."' AND order_id = '".(int) $order_id."'");

                $restocked_quantity = ['restocked_quantity' => $value, 'ordered_quantity' => $ordered_quantity->row['quantity']];
                $restocked = array_merge($query_products->row, $restocked_quantity);
                $keys = [];
                $values = [];
                foreach ($restocked as $key => $val) {
                    $keys[] = $key;
                    $values[] = $val;
                }

                $restocked_products[] = array_combine($keys, $values);
            }
        }

        $products = [];
        foreach ($restocked_products as $product) {
            if (0 != $product['restocked_quantity']) {
                $products[] = [
                    'product_id' => $product['product_id'],
                    'image' => $product['image'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'restocked_quantity' => $product['restocked_quantity'],
                    'status' => $product['status'],
                    'ordered_quantity' => $product['ordered_quantity'],
                ];
            }
        }
        // echo '<pre>fin'; print_r( $products); echo '</pre>';
        return $products;
    }

    public function getRestockProducts($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."kevin_refund WHERE order_id = '".(int) $order_id."'");

        $restocked = [];

        foreach ($query->rows as $value) {
            $keys = [];
            $values = [];
            if (!empty($value['restocked_products'])) {
                $product_id_values = json_decode($value['restocked_products'], true);

                $keys = array_keys($product_id_values);
                $values = array_values($product_id_values);

                $restocked[] = array_combine($keys, $values);
            }
        }

        $restocked_product_ids = [];
        array_walk_recursive($restocked, function ($item, $key) use (&$restocked_product_ids) {
            $restocked_product_ids[$key] = isset($restocked_product_ids[$key]) ? $item + $restocked_product_ids[$key] : $item;
        });

        $restocked_products = [];
        if (!empty($restocked_product_ids)) {
            foreach ($restocked_product_ids as $product_id => $value) {
                if (!empty($value) || 0 == $value) {
                    $query_products = $this->db->query('SELECT p.product_id, p.image, pd.name, p.model, p.price, p.quantity, p.status   FROM '.DB_PREFIX.'product p  LEFT JOIN '.DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int) $product_id."' AND pd.language_id = '".(int) $this->config->get('config_language_id')."' ");

                    $ordered_quantity = $this->db->query('SELECT quantity FROM `'.DB_PREFIX."order_product`  WHERE product_id = '".(int) $product_id."' AND order_id = '".(int) $order_id."'");

                    $restocked_quantity = ['restocked_quantity' => $value, 'ordered_quantity' => $ordered_quantity->row['quantity']];
                    $restocked = array_merge($query_products->row, $restocked_quantity);
                    $keys = [];
                    $values = [];
                    foreach ($restocked as $key => $val) {
                        $keys[] = $key;
                        $values[] = $val;
                    }

                    $restocked_products[] = array_combine($keys, $values);
                }
            }
        } else {
            foreach ($query->rows as $value) {
                $query_products = $this->db->query('SELECT p.product_id, p.image, pd.name, p.model, p.price, p.quantity, p.status   FROM '.DB_PREFIX.'product p  LEFT JOIN '.DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int) $value['product_id']."' AND pd.language_id = '".(int) $this->config->get('config_language_id')."' ");

                $ordered_quantity = $this->db->query('SELECT quantity FROM `'.DB_PREFIX."order_product`  WHERE product_id = '".(int) $value['product_id']."' AND order_id = '".(int) $order_id."'");

                $restocked_quantity = ['restocked_quantity' => 0, 'ordered_quantity' => $ordered_quantity->row['quantity']];
                $restocked = array_merge($query_products->row, $restocked_quantity);
                $keys = [];
                $values = [];
                foreach ($restocked as $key => $val) {
                    $keys[] = $key;
                    $values[] = $val;
                }
                $restocked_products[] = array_combine($keys, $values);
            }
        }

        $products = [];
        foreach ($restocked_products as $product) {
            $products[] = [
                'product_id' => $product['product_id'],
                'image' => $product['image'],
                'name' => $product['name'],
                'model' => $product['model'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'restocked_quantity' => !empty($product['restocked_quantity']) ? $product['restocked_quantity'] : 0,
                'status' => $product['status'],
                'ordered_quantity' => $product['ordered_quantity'],
            ];
        }

        return $products;
    }

    public function getOrderOptions($order_id, $order_product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_option WHERE order_id = '".(int) $order_id."' AND order_product_id = '".(int) $order_product_id."'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_voucher WHERE order_id = '".(int) $order_id."'");

        return $query->rows;
    }

    public function getOrderTotals($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_total WHERE order_id = '".(int) $order_id."' ORDER BY sort_order");

        return $query->rows;
    }

    public function getTotalOrders($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'kevin_order` ko LEFT JOIN `'.DB_PREFIX."order` o ON(ko.order_id=o.order_id)  AND ko.statusGroup = 'completed' ";

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode) {
                $sql .= ' WHERE ('.implode(' OR ', $implode).')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND ko.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(ko.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(ko.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        }
        /*
        if (!empty($data['filter_total_amount'])) {
            $sql .= " AND ko.amount = '" . (float)$data['filter_total_amount'] . "'";
        }
*/
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
