<?php
class Order extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Order_model');
		$this->load->model('User_model');
		$this->load->model('Product_model');
		$this->load->library('ajax');
		$this->lang->load('main');
		$this->db->query("SET NAMES 'utf8'");
		$this->db->query("SET CHARACTER SET utf8");
		$this->db->query("SET NAMES 'utf8'");
	}

	function index() {
		//$this->view_order();
		$this->list_orders();
	}

	function view_order($orderID, $userID) {


		$user = $this->User_model->getUser($userID);
		$order = $this->Order_model->get_order($orderID);
		$products = $this->Order_model->get_order_products($orderID);
		foreach($products as $product => $value) {
			$products[$product] = array_merge($products[$product], $this->Product_model->getProductText($products[$product]['productID']));
		}

		$this->config->set_item('language', $user['user_language']);
		$this->lang->load('main');
		$data['lang'] = $user['user_language'];

		/*$form_data = array();
		$form_data['orderID'] = $orderID;

		$form_data['action'] = $this->uri->segment(3, "add_order");
		if($form_data['action'] === "edit_order") {
			$form_data = array_merge($form_data, $this->Order_model->get_order($orderID));
			$form_data['action'] = 'set_order';
		}
		$data['contents'] = $this->load->view('order/print_tpl', $form_data, TRUE);*/

		$data['user'] = $user;
		$data['order'] = $order;
		$data['products'] = $products;


		$data['title'] = "Διαχείριση Συστήματος Προϊόντων";
		$data['heading'] = "Επεξεργασία Παραγγελίας";
		$data['contents'] = "contents";

		$this->load->view('order/print_tpl',$data);
	}

	function list_orders()
	{
		// gets all orders from database

		$data['title'] = "Διαχείριση Συστήματος Προϊόντων";
		$data['heading'] = "Λίστα Παραγγελιών";

		$list_data['orders'] = $this->Order_model->get_all_order_ids();
		$data['contents'] = $this->load->view('order/list_tpl', $list_data, TRUE);


		$this->load->view('container_tpl',$data);
	}

	function set_order() {
		$this->Order_model->set_order();
		redirect('order');
	}

	function add_order() {
		$this->Order_model->add_order();
		redirect('order');
	}

	function delete_order()
	{
		$this->Order_model->delete_order($this->uri->segment(3));
		redirect('order');
	}

	function ajaxget_user($userID) {
		$data = $this->User_model->getUser($userID);
		$this->load->view('order/user_tpl', $data);
	}

	function ajaxget_products($orderID, $userID) {
		$user = $this->User_model->getUser($userID);
		$products = $this->Order_model->get_order_products($orderID);

		foreach($products as $product => $value) {
			$products[$product] = array_merge($products[$product], $this->Product_model->getProductText($products[$product]['productID']));
		}
		$this->load->view('order/products_tpl', array('products' => $products, 'user' => $user));
	}

	function ajaxset_status($orderID, $status) {
		echo $this->Order_model->set_order_status($orderID, $status);
	}
}
