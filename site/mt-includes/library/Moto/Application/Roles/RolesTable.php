<?php
namespace Moto\Application\Roles; use Moto; use Zend\Db\Sql\Select; use Moto\Application\Model\AbstractTable; use Zend\Db\ResultSet\ResultSet; class RolesTable extends AbstractTable { protected $table = 'roles'; protected $_resultModel = 'Moto\Application\Roles\RoleModel'; protected $_primaryKey = 'id'; public function fetchAll() { $resultSet = $this->select(); return $resultSet; } public function getList($request = null) { $select = new Select($this->table); $select->columns(array( 'count' => new \Zend\Db\Sql\Expression("COUNT(*)") )); $select->where(array( 'del' => 0, 'hidden' => 0 )); $result = $this->executeSelect($select); $total = $result->current()->count; $select->columns(array('id', 'name')); $select->order(array('priority' => 'asc')); $records = $this->executeSelect($select); $result = array( 'meta' => array( 'total' => $total, 'limit' => 0, 'page' => 0, ), 'records' => $records->toArray() ); return $result; } public function getById($id) { $row = is_numeric($id); if ($row) { $select = new Select($this->table); $select->where(array( 'id' => $id )); $resultSet = $this->executeSelect($select); $row = $resultSet->current(); } return $row; } public function getByName($name) { return $this->select(array('name' => $name))->current(); } }