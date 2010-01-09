<?php

class Tree extends CakeTestModel {
  var $name = 'Tree';
	var $actsAs = array('TreeCounterCache.TreeCounterCache' => 'invalid config');
}

class TreeCounterCacheTestCase extends CakeTestCase {

	var $fixtures = array('plugin.tree_counter_cache.tree');

	public function testSaveNoMove() {
		$Tree = new Tree();
    $before = $Tree->find('all');
    $before[0]['Tree']['name'] = 'Root Node Edit';
    $Tree->save($before[0]);
    $after = $Tree->find('all');
		$this->assertIdentical($before, $after);
	}

  public function testInsertAtRoot() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '14', 'child_count' => '6', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'child_count' => '3', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '15', 'rght' => '16', 'child_count' => '0', 'direct_child_count' => '0')),
      8 => array('Tree' => array('id' => '9', 'name' => '3', 'parent_id' => NULL, 'lft' => '17', 'rght' => '18', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->save(array('Tree' => array('name' => '3')));
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testInsertInMiddle() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '16', 'child_count' => '7', 'direct_child_count' => '4')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'child_count' => '3', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '9', 'name' => '1.4', 'parent_id' => '1', 'lft' => '14', 'rght' => '15', 'child_count' => '0', 'direct_child_count' => '0')),
      8 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '17', 'rght' => '18', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->save(array('Tree' => array('name' => '1.4', 'parent_id' => 1)));
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testInsertLeafNode() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '16', 'child_count' => '7', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '11', 'child_count' => '4', 'direct_child_count' => '4')),
      2 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '9', 'name' => '1.1.4', 'parent_id' => '3', 'lft' => '9', 'rght' => '10', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '14', 'rght' => '15', 'child_count' => '0', 'direct_child_count' => '0')),
      8 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '17', 'rght' => '18', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->save(array('Tree' => array('name' => '1.1.4', 'parent_id' => '3')));
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testEditParentFromNULLToNotNull() {
    $expected = array(
      0 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '1', 'rght' => '16', 'child_count' => '7', 'direct_child_count' => '1')),
      1 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => '2', 'lft' => '2', 'rght' => '15', 'child_count' => '6', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '3', 'rght' => '10', 'child_count' => '3', 'direct_child_count' => '3')),
      3 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '4', 'rght' => '5', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '6', 'rght' => '7', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '8', 'rght' => '9', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '11', 'rght' => '12', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '13', 'rght' => '14', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->save(array('Tree' => array('id' => '1', 'parent_id' => '2')));
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testEditParentToSelf() {
		$Tree = new Tree();
    $expected = $Tree->find('all', array('order' => 'lft'));
    $Tree->save(array('Tree' => array('id' => '3', 'parent_id' => '3')));
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testEditParentLeafNodeToDifferentBranch() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '12', 'child_count' => '5', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '7', 'child_count' => '2', 'direct_child_count' => '2')),
      2 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '8', 'rght' => '9', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '13', 'rght' => '16', 'child_count' => '1', 'direct_child_count' => '1')),
      7 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '2', 'lft' => '14', 'rght' => '15', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->save(array('Tree' => array('id' => '7', 'parent_id' => '2')));
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testDeleteLeafNode() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '12', 'child_count' => '5', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '7', 'child_count' => '2', 'direct_child_count' => '2')),
      2 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '8', 'rght' => '9', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '13', 'rght' => '14', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->delete(7);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testDeleteMiddleNode() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '6', 'child_count' => '2', 'direct_child_count' => '2')),
      1 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '2', 'rght' => '3', 'child_count' => '0', 'direct_child_count' => '0')),
      2 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '4', 'rght' => '5', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->delete(3);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testDeleteRootNode() {
    $expected = array(
      0 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '1', 'rght' => '2', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->delete(1);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  protected function _dump($data) {
    echo "<pre>".PHP_EOL;
    foreach ($data as $k => $row) {
      echo "\n$k";
      foreach (current($row) as $field => $value) {
        echo "\t$value";
      }
    }
  }

  public function testMoveLeafNodeDown() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '14', 'child_count' => '6', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'child_count' => '3', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '15', 'rght' => '16', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->setOrder(6, 2);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  function testSetOrderWithNoId() {
		$Tree = new Tree();
    $result = $Tree->setOrder(null, 2);
    $this->assertFalse($result);
		$this->assertEqual(count($Tree->Behaviors->TreeCounterCache->errors), 1);
  }

  function testSetOrderWithNonNumericOrder() {
		$Tree = new Tree();
    $Tree->Behaviors->TreeCounterCache->errors = array();
    $result = $Tree->setOrder(1, '2nd');
    $this->assertFalse($result);
		$this->assertEqual(count($Tree->Behaviors->TreeCounterCache->errors), 1);
  }

  public function testMoveLeafNodeUp() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '14', 'child_count' => '6', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'child_count' => '3', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '15', 'rght' => '16', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->setOrder(8, 0);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testMoveLeafNodeToSamePos() {
		$Tree = new Tree();
    $expected = $Tree->find('all', array('order' => 'lft'));
    $Tree->setOrder(6, 0);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testMoveLeafNodeDownTooFar() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '14', 'child_count' => '6', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'child_count' => '3', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '15', 'rght' => '16', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->setOrder(6, 3);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testMoveLeafNodeUpTooFar() {
    $expected = array(
      0 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '1', 'rght' => '14', 'child_count' => '6', 'direct_child_count' => '3')),
      1 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'child_count' => '3', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '3', 'rght' => '4', 'child_count' => '0', 'direct_child_count' => '0')),
      3 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '10', 'rght' => '11', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '15', 'rght' => '16', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->setOrder(8, -1);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

  public function testMoveRootNodeDown() {
    $expected = array(
      0 => array('Tree' => array('id' => '2', 'name' => '2', 'parent_id' => NULL, 'lft' => '1', 'rght' => '2', 'child_count' => '0', 'direct_child_count' => '0')),
      1 => array('Tree' => array('id' => '1', 'name' => '1', 'parent_id' => NULL, 'lft' => '3', 'rght' => '16', 'child_count' => '6', 'direct_child_count' => '3')),
      2 => array('Tree' => array('id' => '3', 'name' => '1.1', 'parent_id' => '1', 'lft' => '4', 'rght' => '11', 'child_count' => '3', 'direct_child_count' => '3')),
      3 => array('Tree' => array('id' => '6', 'name' => '1.1.1', 'parent_id' => '3', 'lft' => '5', 'rght' => '6', 'child_count' => '0', 'direct_child_count' => '0')),
      4 => array('Tree' => array('id' => '7', 'name' => '1.1.2', 'parent_id' => '3', 'lft' => '7', 'rght' => '8', 'child_count' => '0', 'direct_child_count' => '0')),
      5 => array('Tree' => array('id' => '8', 'name' => '1.1.3', 'parent_id' => '3', 'lft' => '9', 'rght' => '10', 'child_count' => '0', 'direct_child_count' => '0')),
      6 => array('Tree' => array('id' => '4', 'name' => '1.2', 'parent_id' => '1', 'lft' => '12', 'rght' => '13', 'child_count' => '0', 'direct_child_count' => '0')),
      7 => array('Tree' => array('id' => '5', 'name' => '1.3', 'parent_id' => '1', 'lft' => '14', 'rght' => '15', 'child_count' => '0', 'direct_child_count' => '0')),
    );
		$Tree = new Tree();
    $Tree->setOrder(1, 1);
    $results = $Tree->find('all', array('order' => 'lft'));
		$this->assertEqual($results, $expected);
  }

}

?>