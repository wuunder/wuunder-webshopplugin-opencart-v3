<?php

class ControllerExtensionModuleWuunder extends Controller {

    public function index() {

    }

    public function webhook() {
        if (isset($_REQUEST['order']) && isset($_REQUEST['user_token'])) {
            $order_id = $_REQUEST['order'];
            $booking_token = $_REQUEST['user_token'];
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data['action'] === "shipment_booked") {
              $this->db->query("UPDATE `wuunder_shipment` SET label_id = '" . $this->db->escape($data['shipment']['id']) . "', label_url = '" . $this->db->escape($data['shipment']['label_url']) . "', label_tt_url = '" . $this->db->escape($data['shipment']['track_and_trace_url']) . "' WHERE order_id = " . $order_id . " AND booking_token = '" . $booking_token . "'");
              $this->load->model("checkout/order");
              $this->model_checkout_order->addOrderHistory($order_id, 3);
            }
        }
    }
}
