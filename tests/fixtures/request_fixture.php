<?php 
class RequestFixture extends CakeTestFixture {
	public $name = 'Request';
	
	public $fields = array(
		'ip_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'location_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
		'moment_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'number_of_visits' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 6),
		'indexes' => array('PRIMARY' => array('column' => array('ip_id', 'location_id', 'moment_id'), 'unique' => 1), 'FK_moment_requests' => array('column' => 'moment_id', 'unique' => 0), 'FK_locations_request' => array('column' => 'location_id', 'unique' => 0))
	);
	
	public $records = array(
		array('ip_id' => 1, 'location_id' => 2, 'moment_id' => 3, 'number_of_visits' => 323),
		array('ip_id' => 1, 'location_id' => 1, 'moment_id' => 3, 'number_of_visits' => 112),
		array('ip_id' => 1, 'location_id' => 3, 'moment_id' => 3, 'number_of_visits' => 214),
		array('ip_id' => 1, 'location_id' => 4, 'moment_id' => 3, 'number_of_visits' => 15),
		array('ip_id' => 2, 'location_id' => 2, 'moment_id' => 3, 'number_of_visits' => 124),
		array('ip_id' => 2, 'location_id' => 1, 'moment_id' => 3, 'number_of_visits' => 132),
		array('ip_id' => 2, 'location_id' => 3, 'moment_id' => 3, 'number_of_visits' => 118),
		array('ip_id' => 2, 'location_id' => 4, 'moment_id' => 3, 'number_of_visits' => 257),
		array('ip_id' => 3, 'location_id' => 3, 'moment_id' => 3, 'number_of_visits' => 164),
		array('ip_id' => 3, 'location_id' => 4, 'moment_id' => 3, 'number_of_visits' => 138),
		array('ip_id' => 3, 'location_id' => 3, 'moment_id' => 1, 'number_of_visits' => 234),
		array('ip_id' => 3, 'location_id' => 4, 'moment_id' => 1, 'number_of_visits' => 277),
	);
}
?>