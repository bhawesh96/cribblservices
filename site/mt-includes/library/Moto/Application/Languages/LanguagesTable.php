<?php
namespace Moto\Application\Languages; use Moto; use Zend\Db\Sql\Select; use Moto\Application\Model\AbstractTable; use Zend\Db\ResultSet\ResultSet; class LanguagesTable extends AbstractTable { protected $table = 'languages'; protected $_resultModel = 'Moto\Application\Languages\LanguageModel'; protected $_primaryKey = 'id'; public function fetchAll() { $resultSet = $this->select(); return $resultSet; } public function getList($request = null) { $select = new Select($this->table); $select->columns(array( 'count' => new \Zend\Db\Sql\Expression("COUNT(*)") )); $select->where(array( 'del' => 0, )); $result = $this->executeSelect($select); $total = $result->current()->count; $columns = array('id', 'code', 'name'); if (Moto\Version::getCurrentBuild() > 61) { $columns = array('id', 'code', 'locale', 'name'); } $select->columns($columns); $select->order(array('priority' => 'asc', 'id' => 'asc')); $records = $this->executeSelect($select); $result = array( 'meta' => array( 'total' => $total, 'limit' => 0, 'page' => 0, ), 'records' => $records->toArray() ); return $result; } public function getById($id) { $record = is_numeric($id); if ($record) { $record = $this->select(array('id' => $id))->current(); } return $record; } public function getByCode($code) { return $this->select(array('code' => $code))->current(); } public function getByLocale($locale) { return $this->select(array('locale' => $locale))->current(); } } 