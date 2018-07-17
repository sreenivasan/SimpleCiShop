<?php

/**
 * Class	Index
 */
class Index extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Product_model');
		$this->load->model('Category_model');
		$this->load->model('Search_model');
		$this->load->model('News_model');
		$this->db->query("SET NAMES 'utf8'");
		$this->db->query("SET CHARACTER SET utf8");
		$this->db->query("SET NAMES 'utf8'");
	}

	function index($lang = NULL)
	{
		redirect('shop/index/greek');
	}

	function guide($lang)
	{
		if($lang!="greek") redirect('shop/index/greek');

		$this->config->set_item('language', $lang);
		$this->lang->load('main');

		$data['title'] = '';
		$data['pagename'] = '';
		$data['lang'] = $lang;

		$content_data = array();
		$data['contents'] = $this->load->view('contents/'.$lang.'/guide_tpl', $content_data, TRUE);

		$rblock_data = array();
		$rblock_data['lang'] = $lang;

		$data['rblock'] = $this->load->view('blocks/category_block_tpl', array('categories_arr' => ($this->Category_model->get_all_category_ids_recursive()), "parent" => array(), "childs" => array(), "current" => 0, "lang" => $lang), TRUE);

		$this->load->view('container', $data);

	}

	function transactions($lang)
	{
		if($lang!="greek") redirect('shop/index/greek');

		$this->config->set_item('language', $lang);
		$this->lang->load('main');

		$data['title'] = '';
		$data['pagename'] = '';
		$data['lang'] = $lang;

		$content_data = array();
		$data['contents'] = $this->load->view('contents/'.$lang.'/transactions_tpl', $content_data, TRUE);

		$rblock_data = array();
		$rblock_data['lang'] = $lang;

		$data['rblock'] = $this->load->view('blocks/category_block_tpl', array('categories_arr' => ($this->Category_model->get_all_category_ids_recursive()), "parent" => array(), "childs" => array(), "current" => 0, "lang" => $lang), TRUE);

		$this->load->view('container', $data);

	}

	function secure($lang)
	{
		if($lang!="greek") redirect('shop/index/greek');

		$this->config->set_item('language', $lang);
		$this->lang->load('main');

		$data['title'] = '';
		$data['pagename'] = '';
		$data['lang'] = $lang;

		$content_data = array();
		$data['contents'] = $this->load->view('contents/'.$lang.'/secure_tpl', $content_data, TRUE);

		$rblock_data = array();
		$rblock_data['lang'] = $lang;

		$data['rblock'] = $this->load->view('blocks/category_block_tpl', array('categories_arr' => ($this->Category_model->get_all_category_ids_recursive()), "parent" => array(), "childs" => array(), "current" => 0, "lang" => $lang), TRUE);

		$this->load->view('container', $data);

	}

}