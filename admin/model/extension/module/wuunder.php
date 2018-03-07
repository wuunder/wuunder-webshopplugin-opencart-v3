<?php

class ModelExtensionModuleWuunder extends Model {

    public function installTable() {
        $this->db->query("DROP TABLE IF EXISTS `wuunder_shipment`;");
        $this->db->query("CREATE TABLE IF NOT EXISTS wuunder_shipment (
          `shipment_id` int(10) NOT NULL AUTO_INCREMENT,
          `order_id` int(10) NOT NULL,
          `label_id` varchar(255) DEFAULT NULL,
          `label_url` text DEFAULT NULL,
          `label_tt_url` text DEFAULT NULL,
          `booking_token` varchar(255) NOT NULL,
          `booking_url` text DEFAULT NULL,
          PRIMARY KEY (`shipment_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
    }

    public function uninstallTable() {
        $this->db->query("DROP TABLE IF EXISTS `wuunder_shipment`;");
    }

    public function getLabel($order_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM wuunder_shipment WHERE order_id = '" . (int)$order_id . "'");

        if ($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
    }

    public function checkLabelExists($order_id) {
        $query = $this->db->query("SELECT 1 FROM wuunder_shipment WHERE order_id = '" . (int)$order_id . "'");
        return $query->num_rows > 0;
    }

    public function insertBookingUrlAndToken($order_id, $booking_url, $booking_token) {
        $this->db->query("INSERT INTO `wuunder_shipment` (order_id, booking_url, booking_token) VALUES (" . $order_id . ", '" . $booking_url . "', '" . $booking_token . "');");
    }

    public function insertBookingToken($order_id, $booking_token) {
        $this->db->query("INSERT INTO `wuunder_shipment` (order_id, booking_token) VALUES (" . $order_id . ", '" . $booking_token . "');");
    }

    public function setBookingUrl($order_id, $booking_token, $booking_url) {
        $this->db->query("UPDATE `wuunder_shipment` SET booking_url = '" . $booking_url . "' WHERE order_id = " . $order_id . " AND booking_token = '" . $booking_token . "'");
    }

    public function getOrderStatusCode($order_id) {
        $query = $this->db->query("SELECT order_status_id FROM `" . DB_PREFIX . "order` o WHERE o.order_id = " . $order_id);
        if ($query->num_rows) {
            return $query->row['order_status_id'];
        } else {
            return false;
        }
    }

}
