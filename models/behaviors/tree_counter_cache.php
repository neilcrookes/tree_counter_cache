<?php
// Include the cake core tree behavior class, which this behavior extends
App::import('Behavior', 'Tree');
/**
 * TreeCounterCache Behavior extends the cake core tree behavior. It can cache
 * the total child count (children and children's children etc) and/or direct
 * child counts for each node in the tree.
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2009 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 *
 * @todo Add counter scope
 * @package cake
 * @subpackage cake.base
 */
class TreeCounterCacheBehavior extends TreeBehavior {

  /**
   * Default settings for TreeCounterCache. Settings are fields names for the
   * fields that store the number of all children and just the direct children
   * of a node.
   *
   * @var array
   */
  var $_ccDefaults = array(
  	'child_count' => 'child_count',
  	'direct_child_count' => 'direct_child_count',
  );

  /**
   * Stores the ids of the nodes that need counter cache's re-calculating.
   *
   * @var array
   */
  var $_parentIds = array();

  /**
   * Merges passed config with defaults and stores in settings for the model
   * we're working on.
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @param mixed $config Any settings declared when the behavior is attached to
   *    the model in the $actsAs property
   * @access public
   */
  function setup(&$model, $config = null) {

    // Process config
    if (!is_array($config)) {
      $config = array();
    }
    $config = array_merge($this->_ccDefaults, $config);

    // Let parent do the setup of settings property
    parent::setup($model, $config);

  }

  /**
   * Identifies the ids of the nodes whose counter caches need updating.
   * - If editing and parent not changed, do not update counter caches
   * - If inserting and parent id is null, do not update counter caches
   * - If editing and parent has changed, update counter caches of old parent
   * and all its parents and new parent and all it's parents.
   * - If inserting and parent is not null, update counter caches of new parent
   * and all it's parents.
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @return boolean Always true
   * @access public
   */
  function beforeSave(&$model) {

    $this->_parentIds[$model->alias] = array();

    // Let TreeBehavior do it's thing first
    $parentResult = parent::beforeSave($model);

    // If parent fails, return
    if ($parentResult === false) {
      return $parentResult;
    }

    // If editing and parent not changed, do not update counter caches
    if ($model->id && !$this->settings[$model->alias]['__parentChange']) {
      return true;
    }

    // Get the new parent id
    $newParentId = null;
    if (isset($model->data[$model->alias][$this->settings[$model->alias]['parent']])) {
      $newParentId = $model->data[$model->alias][$this->settings[$model->alias]['parent']];
    }

    // If inserting and new parent id is null, do not update counter caches
    if (!$model->id && !$newParentId) {
      return true;
    }

    // If editing get ids of the current/previous parent and all it's parents
    if ($model->id) {

      // Get the current/previous parent id
      $currentParentId = $model->field($this->settings[$model->alias]['parent']);

      // Add the current/previous parent id and it's parents to the _parentIds
      // property for updating in afterSave
      $this->_addParentIds($model, $currentParentId);

    }

    // Add the new parent id and it's parents to the _parentIds property for
    // updating in afterSave
    $this->_addParentIds($model, $newParentId);

    return true;

  }

  /**
   * Add parent node ids of a given parent and all it's parents to the parentIds
   * property for updating
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @param integer $parentId
   * @access protected
   */
  function _addParentIds(&$model, $parentId) {

    if (!$parentId) {
      return;
    }

    // Get the path to the parent
    $pathToParent = $model->getpath($parentId);

    // Get the Ids of the nodes in the path to parent
    $idsInPathToParent = Set::extract("/{$model->alias}/{$model->primaryKey}", $pathToParent);

    // Add these to the _parentIds property for updating in afterSave
    $this->_parentIds[$model->alias] = array_merge($this->_parentIds[$model->alias], $idsInPathToParent);

    // Remove duplicates
    $this->_parentIds[$model->alias] = array_unique($this->_parentIds[$model->alias]);

  }

  /**
   * Trigger the update counter cache
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @param boolean $created Whether the record was created, or false if edited
   * @access public
   */
  function afterSave(&$model, $created) {

    // Get parent after save logic out of the way
    parent::afterSave($model, $created);

    // Trigger the update counter cache
    $result = $this->_updateCounterCache($model);

    return $result;

  }

  /**
   * Identifies the ids of the nodes whose counter caches need updating. I.e.
   * all parents of the current node
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @return boolean Always true
   * @access public
   */
  function beforeDelete(&$model) {

    $this->_parentIds[$model->alias] = array();

    // Get parent before delete logic out of the way
    parent::beforeDelete($model);

    // Get the current/previous parent id
    $currentParentId = $model->field($this->settings[$model->alias]['parent']);

    // Add the current/previous parent id and it's parents to the _parentIds
    // property for updating in afterSave
    $this->_addParentIds($model, $currentParentId);

    return true;

  }

  /**
   * Trigger the update counter cache
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @return boolean
   * @access public
   */
  function afterDelete(&$model) {

    // Trigger the update counter cache
    $result = $this->_updateCounterCache($model);

    return $result;

  }

  /**
   * Updates the counter caches of each node in the parentIds property. Called
   * after save or after deletion of a node.
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @access protected
   */
  function _updateCounterCache(&$model) {

    // Foreach item in the parentIds property array...
    foreach ($this->_parentIds[$model->alias] as $k => $parentId) {

      // Reset the $model
      $model->create();

      // Initialise the data array for saving
      $data = array(
        $model->alias => array(
          $model->primaryKey => $parentId,
        ),
      );

      // Set the child count of the current node
      if ($this->settings[$model->alias]['child_count']) {
        $data[$model->alias][$this->settings[$model->alias]['child_count']] = $model->childCount($parentId);
      }

      // Set the direct child count of the current node
      if ($this->settings[$model->alias]['direct_child_count']) {
        $data[$model->alias][$this->settings[$model->alias]['direct_child_count']] = $model->childCount($parentId, true);
      }

      // Save the data with new counter caches
      $model->save($data, array('validate' => false, 'callbacks' => false));

      unset($this->_parentIds[$model->alias][$k]);

    }

  }

  /**
   * Moves a node up or down the order of ndoes at a particular level, i.e.
   * affects the relative position of a node with respect to it's siblings.
   *
   * Used as a replacement for the SequenceBehavior for hierarchical models,
   * which the SequenceBehavior is not suitable for.
   *
   * @param AppModel $model The model object that the behavior is attached to
   * @param integer $id ID of the node to affect
   * @param integer $newOrder New position in the sequence
   * @return boolean
   * @access public
   */
  function setOrder(&$model, $id, $newOrder) {

    // Validate id supplied
    if (!$id) {
      $this->errors[] = "Id not valid";
      return false;
    }

    // Validate new order
    if (!is_numeric($newOrder)) {
      $this->errors[] = "New order must be numeric";
      return false;
    }

    // Get parent id
    $model->recursive = -1;
    $parentId = $model->field($this->settings[$model->alias]['parent'], array($model->primaryKey => $id));

    // Get all children of node's parent, i.e. it and siblings
    $siblings = $model->find('list', array(
      'conditions' => array(
        $this->settings[$model->alias]['parent'] => $parentId,
      ),
    ));

    // Get sibling ids in a numerically indexed array, starting at 0
    $siblingIds = array_keys($siblings);

    // Get the current order the node in question in the array of sibling ids
    $currentOrder = array_search($id, $siblingIds);

    // Get the difference between the new position and old position
    $difference = $newOrder - $currentOrder;

    // Get the number of places to move the node
    $places = abs($difference);

    // Move the node up or down (depending on the sign of the difference) by the
    // appropriate number of places
    if ($difference == 0) {
      return true;
    } elseif ($difference > 0) {
      return $this->movedown($model, $id, $places);
    } else {
      return $this->moveup($model, $id, $places);
    }

  }

}

?>
