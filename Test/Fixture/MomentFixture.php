<?php
class MomentFixture extends CakeTestFixture {
	public $name = 'Moment';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'hour' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
		'day' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
		'month' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
		'year' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

	public $records = array(
		array('id' => 1, 'hour' => 11, 'day' => 14, 'month' => 4, 'year' => 2010),
		array('id' => 2, 'hour' => 8, 'day' => 14, 'month' => 4, 'year' => 2010),
		array('id' => 3, 'hour' => 8, 'day' => 14, 'month' => 9, 'year' => 2010),
		array('id' => 4, 'hour' => 23, 'day' => 22, 'month' => 9, 'year' => 2010),
	);
}
?>