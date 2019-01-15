<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_orders_table extends CI_Migration
{
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}

	public function up()
	{
		$fields = [
			'order_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			],
			'user_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
			],
			'status' => [
				'type' => 'INT',
				'constraint' => 11,
				'default' => NULL,
			],
			'shipment_express' => [
				'type' => 'INT',
				'constraint' => 1,
				'default' => 0,
			],
			'shipment_to_door' => [
				'type' => 'INT',
				'constraint' => 1,
				'default' => 0,
			],
			'shipment_cash_on_delivery' => [
				'type' => 'INT',
				'constraint' => 1,
				'default' => 0,
			],
			'price' => [
				'type' => 'DECIMAL',
				'constraint' => [
					11,
					2
				],
				'default' => NULL,
			],
			'coupon_id' => [
				'type' => 'INT',
				'constraint' => '11',
				'default' => NULL,
			],
			'questionnaire' => [
				'type' => 'TEXT',
				'default' => NULL,
			],
		];
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('order_id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_table('orders', TRUE);
	}

	public function down()
	{
		$this->dbforge->drop_table('orders', TRUE);
	}
}