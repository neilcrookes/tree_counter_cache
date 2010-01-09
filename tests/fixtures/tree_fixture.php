<?php
class TreeFixture extends CakeTestFixture {
	var $name = 'Tree';
	var $table = 'trees';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL),
		'lft' => array('type'=>'integer', 'null' => true, 'default' => NULL),
		'rght' => array('type'=>'integer', 'null' => true, 'default' => NULL),
		'child_count' => array('type'=>'integer', 'null' => false, 'default' => '0'),
		'direct_child_count' => array('type'=>'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(
		array('id' => 1, 'name' => '1', 'parent_id' => NULL, 'lft' => 1, 'rght' => 14, 'child_count' => 6, 'direct_child_count' => 3),
		array('id' => 2, 'name' => '2', 'parent_id' => NULL, 'lft' => 15, 'rght' => 16, 'child_count' => 0, 'direct_child_count' => 0),
		array('id' => 3, 'name' => '1.1', 'parent_id' => 1, 'lft' => 2, 'rght' => 9, 'child_count' => 3, 'direct_child_count' => 3),
		array('id' => 4, 'name' => '1.2', 'parent_id' => 1, 'lft' => 10, 'rght' => 11, 'child_count' => 0, 'direct_child_count' => 0),
		array('id' => 5, 'name' => '1.3', 'parent_id' => 1, 'lft' => 12, 'rght' => 13, 'child_count' => 0, 'direct_child_count' => 0),
		array('id' => 6, 'name' => '1.1.1', 'parent_id' => 3, 'lft' => 3, 'rght' => 4, 'child_count' => 0, 'direct_child_count' => 0),
		array('id' => 7, 'name' => '1.1.2', 'parent_id' => 3, 'lft' => 5, 'rght' => 6, 'child_count' => 0, 'direct_child_count' => 0),
		array('id' => 8, 'name' => '1.1.3', 'parent_id' => 3, 'lft' => 7, 'rght' => 8, 'child_count' => 0, 'direct_child_count' => 0),
	);
}
?>