<?php
namespace Moto\Application\Users; use Moto; use Zend\Db\Sql\Select; use Moto\Application\Model\AbstractTable; use Zend\Db\ResultSet\ResultSet; class UserTable extends AbstractTable { protected $table = 'users'; protected $_resultModel = 'Moto\Application\Users\UserModel'; protected $_primaryKey = 'id'; public function __construct() { parent::__construct(); } public function fetchAll() { $method = 1; if ($method === 1) { $resultSet = $this->select(); } if ($method === 2) { $select = new Select($this->table); $select->limit(2); $resultSet = $this->executeSelect($select); } return $resultSet; } public function getUsersList($request) { $select = $this->_getSelectJoined(); $select->columns(array( 'count' => new \Zend\Db\Sql\Expression('COUNT(*)') )); $select->group('user.id'); $resultSet = $this->_executeSelectJoined($select); $total = $resultSet->current()->count; $select->columns(array('id', 'email', 'name', 'role_id', 'language_id', 'enabled', 'created', 'modified', 'del')); if ($request->isPagingActive()) { $select->limit($request->getLimit()); $select->offset($request->getOffset()); } $resultSet = $this->_executeSelectJoined($select); $result = array( 'meta' => array( 'total' => $total, 'limit' => $request->getLimit(), 'page' => $request->getPage(), ), 'records' => $resultSet->toArray() ); return $result; } protected function _getSelectJoined($where = null) { $select = new Select(); $select->from(array('user' => $this->table)); $select->join(array('role' => self::$_tablePrefix . 'roles'), 'user.role_id = role.id', array('role_name' => 'name')); $languagesColumns = array('language_name' => 'name', 'language_code' => 'code'); if (Moto\Version::getCurrentBuild() > 61) { $languagesColumns['language_locale'] = 'locale'; } $select->join(array('language' => self::$_tablePrefix . 'languages'), 'user.language_id = language.id', $languagesColumns); if (null !== $where) { if ($where instanceof \Closure) { $where($select); } else { $select->where($where); } } return $select; } public function getUserByLogin($login) { $select = $this->_getSelectJoined(array( 'user.login' => $login )); $resultSet = $this->_executeSelectJoined($select); $row = $resultSet->current(); return $row; } public function getUserByEmail($email) { $select = $this->_getSelectJoined(array( 'user.email' => $email )); $resultSet = $this->_executeSelectJoined($select); $row = $resultSet->current(); return $row; } public function getById($id) { $row = is_numeric($id); if ($row) { $select = $this->_getSelectJoined(array( 'user.id' => $id )); $resultSet = $this->_executeSelectJoined($select); $row = $resultSet->current(); } return $row; } public function getUser($id) { return $this->getById($id); } public function updateUser($id, $data) { try { return $this->update($data, array('id' => $id)); } catch (Exception $e) { var_dump($e); die; } } public function deleteUser($user, $reassign = null, $params = array()) { if (strtolower($user->role_name) === 'root') { return false; } if ($reassign !== null) { $reassign = (int) $reassign; } Moto\Hook::trigger(Moto\Hook::USER_DELETE, $user, array('reassign' => $reassign)); $result = $this->delete(array('id' => $user->id)); return $result; } public function getWebmaster() { $select = $this->_getSelectJoined(array( 'role.id' => array(Moto\Application\Roles\RoleModel::ROOT_ID, Moto\Application\Roles\RoleModel::ADMIN_ID) )); $select->columns(array('id', 'email', 'name')); $select->order(array('role_id' => 'ASC', 'id' => 'ASC')); $resultSet = $this->_executeSelectJoined($select); $row = $resultSet->current(); return $row; } } 