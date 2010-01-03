<?php 
class LocationFixture extends CakeTestFixture {
	public $name = 'Location';
	
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'primary'),
		'path' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	
	public $records = array(
		array('id' => 1, 'path' => 'app/articles/show'),
		array('id' => 2, 'path' => 'app/users/index'),
		array('id' => 3, 'path' => 'content_management/pages/display'),
		array('id' => 4, 'path' => 'app/pages/display')
	);
}
?>