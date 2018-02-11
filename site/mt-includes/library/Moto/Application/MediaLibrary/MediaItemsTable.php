<?php
namespace Moto\Application\MediaLibrary; use Moto; use Zend\Db\Sql\Select; use Moto\Application\Model\AbstractTable; use Zend\Db\ResultSet\ResultSet; use Zend\Db\Sql\Where; class MediaItemsTable extends AbstractTable { protected $table = 'media_items'; protected $_resultModel = 'Moto\Application\MediaLibrary\MediaItemModel'; protected $_primaryKey = 'id'; public function fetchAll() { $resultSet = $this->select(); return $resultSet; } public function getList($where = null) { $select = new Select($this->table); $select->columns(array( 'count' => new \Zend\Db\Sql\Expression("COUNT(*)") )); if (!empty($where)) { $select->where($where); } $result = $this->executeSelect($select); $total = $result->current()->count; $select->columns(array('id', 'folder_id', 'name', 'path', 'type', 'filesize', 'properties', 'thumbnails', 'title', 'author_id', 'caption', 'alt', 'is_protected', 'modified', 'created', 'del')); $select->order(array('created' => 'DESC')); $records = $this->executeSelect($select); $result = array( 'meta' => array( 'total' => $total, 'limit' => 0, 'page' => 0, ), 'records' => $records->toArray() ); return $result; } public function getById($id) { $row = is_numeric($id); if ($row) { $select = new Select($this->table); $select->where(array( 'id' => $id )); $resultSet = $this->executeSelect($select); $row = $resultSet->current(); } return $row; } public function deleteById($id) { $where = array( $this->_primaryKey => $id ); return parent::delete($where); } public function updateItems($ids, $attributes) { if (!is_array($attributes)) { return false; } if (!is_array($ids)) { $ids = array($ids); } $this->useResultAsModel(true); $result = array(); foreach($ids as $id) { $model = $this->getById($id); if (!$model) { continue; } $model->setFromArray($attributes); if ($this->save($model)) { $result[] = $model->toArray(); } } return $result; } }