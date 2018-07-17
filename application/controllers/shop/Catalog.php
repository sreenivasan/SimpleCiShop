<?php
class Catalog extends CI_Controller {
	var $lang;
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Product_model');
		$this->load->model('Category_model');
		$this->load->model('Search_model');
		$this->db->query("SET NAMES 'utf8'");
		$this->db->query("SET CHARACTER SET utf8");
		$this->db->query("SET NAMES 'utf8'");
	}

	function index($lang=NULL)
	{
		if($lang!="greek") redirect('shop/index/greek');

		$this->config->set_item('language', $lang);
		$this->lang->load('main');

		$affiliate = $this->uri->segment(6,"");
		if($affiliate) $this->_setAffiliate($affiliate);

		//$searchData = $this->Search_model->getSearchData();

		$nicename = $this->uri->segment(4,"");
		$categoryID = 0;
		if($nicename != "") $categoryID = $this->Category_model->get_category_id($nicename);

		$content_data = array();

		$content_data['lang'] = $lang;
		$content_data['products'] = $this->_getProductData($categoryID);
		$content_data['pagination'] = $this->_pagination($categoryID);
		$content_data['category_text'] = $this->Category_model->get_category_text($categoryID);

		$data['contents'] = '';

		if($categoryID === 0) $data['contents'] = $this->load->view('contents/'.$lang.'/home_tpl', $content_data, TRUE);

		$data['contents'] .= $this->load->view('contents/catalog_tpl', $content_data, TRUE);

		$data['rblock'] = $this->load->view('blocks/category_block_tpl', array('categories_arr' => ($this->Category_model->get_all_category_ids_recursive()), 'parent' => ($this->Category_model->get_category_parents($categoryID)), 'childs' => ($this->Category_model->get_category_children($categoryID)) , 'current' => $categoryID), TRUE);
		//$data['rblock'] = $this->load->view('blocks/product_type_num_tpl', array('countryID' => NULL), TRUE);
		//$data['tblock'] = $this->load->view('blocks/search_tpl', array('countryID' => NULL), TRUE);

		$data['pagename'] = 'main_catalog';
		$data['lang'] = $lang;
		//$data['categoryID'] = $this->Search_model->getSearchData();
		//$data['categoryID'] = $searchData['categoryID'];
		$data['categoryID'] = $categoryID;
		$data['title'] = $this->Category_model->get_category_name($categoryID);

		$this->load->view('container', $data);
	}

	function _getProductData($categoryID, $products_per_page=6)
	{
		//extract($this->Search_model->getSearchData());

		//$current_page = empty($this->uri->segment(5)) === FALSE ? $this->uri->segment(5) : 0;
		$current_page = $this->uri->segment(5);
		$current_page = empty($current_page) === FALSE ? $current_page : 0;

		$products = $this->Search_model->search_products_by_category_id($categoryID, $products_per_page, $current_page);

		foreach($products as $product => $value) {
			$products[$product] += $this->Product_model->get_product_text($products[$product]['productID']);
			$products[$product] += $this->Category_model->get_category_text($products[$product]['categoryID']);
			$products[$product] += $this->Product_model->get_product_main_image($products[$product]['productID']);
		}

		//print_r($products);

		return $products;
	}

	function _pagination($categoryID, $method='index', $products_per_page=6)
	{
		$lang = $this->config->item('language');
		//extract($this->Search_model->getSearchData());

		$this->load->library('pagination');

		$config['base_url'] = site_url('shop/'.$method.'/'.$lang.'/'.$this->Category_model->get_category_nicename($categoryID).'/');
		$config['total_rows'] = count($this->Search_model->search_products_by_category_id($categoryID));
		$config['per_page'] = $products_per_page;
		$config['uri_segment'] = 5;
		$config['num_links'] = 50;
		$config['first_link'] = $this->lang->line('main_page_first');
		$config['last_link'] = $this->lang->line('main_page_last');

		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}

	function _setAffiliate($affiliate)
	{
		$cart = $this->session->userdata('cart');

		$cart['affiliate'] = (isset($affiliate))? $affiliate:"";
		$this->session->set_userdata(array('cart' => $cart));
	}
}