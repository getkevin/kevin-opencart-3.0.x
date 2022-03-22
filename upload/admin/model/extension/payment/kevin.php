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
class ModelExtensionPaymentKevin extends Model
{
    public function uninstall()
    {
        //$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kevin_order`;");
    }

    public function install()
    {
        $this->db->query('
		CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'kevin_order` (
		`kevin_order_id` int(11) NOT NULL AUTO_INCREMENT,
		`order_id` int(11) NOT NULL,
		`payment_id` varchar(128) DEFAULT NULL,
		`bank_id` varchar(64) DEFAULT NULL,
		`ip_address` varchar(64) DEFAULT NULL,
		`currency_code` CHAR(3) NOT NULL,
		`total` DECIMAL( 10, 2 ) NOT NULL,
		`payment_method` varchar(10) DEFAULT NULL,
		`status` varchar(32) DEFAULT NULL,
		`statusGroup` varchar(10) DEFAULT NULL,
		`order_status_id` int(11) NOT NULL,
		`refund_action_id` int(11) NOT NULL,
		`date_added` DATETIME NOT NULL,
		`date_modified` DATETIME NOT NULL,
		PRIMARY KEY (`kevin_order_id`),
		KEY `order_id` (`order_id`),
		KEY `payment_id` (`payment_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;');

        $this->db->query('
		CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'kevin_refund` (
		`refund_id` int(11) NOT NULL AUTO_INCREMENT,
		`kevin_refund_id` int(11) NOT NULL,
		`order_id` int(11) NOT NULL,
		`payment_id` varchar(128) DEFAULT NULL,
		`notify_refund` int(3) DEFAULT NULL,
		`currency_code` CHAR(3) NOT NULL,
		`amount_available` DECIMAL( 10, 2 ) NOT NULL,
		`amount` DECIMAL( 10, 2 ) NOT NULL,
		`reason` varchar(256) DEFAULT NULL,
		`restocked_products` varchar(128) DEFAULT NULL,
		`statusGroup` varchar(10) DEFAULT NULL,
		`refund_status_id` int(11) NOT NULL,
		`date_added` DATETIME NOT NULL,
		`date_modified` DATETIME NOT NULL,
		PRIMARY KEY (`refund_id`),
		KEY `order_id` (`order_id`),
		KEY `payment_id` (`payment_id`),
		KEY `kevin_refund_id` (`kevin_refund_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;');

        $check_payment_id = $this->db->query("SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME = '".DB_PREFIX."kevin_order' AND COLUMN_NAME = 'payment_id'");
        if ($check_payment_id->num_rows && ('int' == $check_payment_id->row['DATA_TYPE'] || 'INT' == $check_payment_id->row['DATA_TYPE'])) {
            $this->db->query('ALTER TABLE `'.DB_PREFIX.'kevin_order` MODIFY COLUMN `payment_id`	varchar(128) DEFAULT NULL');
        }

        //modify the length of the data type in the table column to display the payment method logo
        $this->db->query('ALTER TABLE `'.DB_PREFIX.'order` MODIFY COLUMN `payment_method`	varchar(256) NOT NULL');

        $query_status = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` order_status_id');
        if (!$query_status->num_rows) {
            $this->db->query('ALTER TABLE `'.DB_PREFIX.'kevin_order` ADD `order_status_id` int(11) NOT NULL AFTER statusGroup ');
        }

        $query_method = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` payment_method');
        if (!$query_method->num_rows) {
            $this->db->query('ALTER TABLE `'.DB_PREFIX.'kevin_order` ADD `payment_method` varchar(10) DEFAULT NULL AFTER total ');
        }
        $query = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` bank_id');
        if (!$query->num_rows) {
            $this->db->query('ALTER TABLE `'.DB_PREFIX.'kevin_order` ADD `bank_id` varchar(32) DEFAULT NULL AFTER payment_id ');
        }

        $query_action = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` refund_action_id');
        if (!$query_action->num_rows) {
            $this->db->query('ALTER TABLE `'.DB_PREFIX.'kevin_order` ADD `refund_action_id` int(11) NOT NULL AFTER order_status_id ');
        }

        //$query_refund_table = $this->db->query("DESCRIBE `" . DB_PREFIX . "kevin_refund` ");
    }

    public function checkKevinDB()
    {
        $query_status = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` order_status_id');
        $query_method = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` payment_method');
        $query_bank = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` bank_id');
        $query_action = $this->db->query('DESC `'.DB_PREFIX.'kevin_order` refund_action_id');

        $query_refund_table = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."kevin_refund' ");

        if (!$query_status->num_rows || !$query_method->num_rows || !$query_bank->num_rows || !$query_refund_table->num_rows || !$query_action->num_rows) {
            return true;
        } else {
            return false;
        }
    }
}
