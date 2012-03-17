<?php
class IpFixture extends CakeTestFixture {
	public $name = 'Ip';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'address' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

	public $records = array(
		array('id' => 1, 'address' => '192.168.1.100'),
		array('id' => 2, 'address' => '77.132.125.12'),
		array('id' => 3, 'address' => '24.214.123.200'),
		array('id' => 4, 'address' => '127.0.0.1')
	);
}
?>