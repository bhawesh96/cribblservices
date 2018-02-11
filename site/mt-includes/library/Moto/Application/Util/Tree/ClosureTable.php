<?php
namespace Moto\Application\Util\Tree; use Moto\Application\Model\AbstractTable; use Zend\Db\Sql\Delete; use Zend\Db\Sql\Insert; use Zend\Db\Sql\Where; use Moto; class ClosureTable extends Tree { protected $_options = array( 'ancestor' => 'ancestor', 'descendant' => 'descendant', 'level' => 'level', 'treepath' => 'treepath' ); public function __construct(AbstractTable $context, $options = array()) { parent::__construct($context, $options); } public function getTreePathTableName() { return Moto\Config::get('database.prefix') . $this->_options['treepath']; } public function insert(array $data) { $tableName = $this->getTableName(); $treePathTable = $this->getTreePathTableName(); $ancestorField = $this->_options['ancestor']; $descendantField = $this->_options['descendant']; $levelField = $this->_options['level']; $insert = new Insert($tableName); $insert->values($data); $result = $this->_context->insertWith($insert); if ($result) { $parentId = $data['parent_id']; $id = $this->_context->getLastInsertValue(); $sql = "INSERT IGNORE INTO `$treePathTable` (`$ancestorField`, `$descendantField`, `$levelField`)
                SELECT `$ancestorField`, $id, (SELECT p.$levelField FROM `$treePathTable` as p WHERE p.$descendantField = $parentId AND p.$ancestorField = page.$ancestorField) + 1 FROM `$treePathTable` as page
                WHERE $descendantField = $parentId
                UNION ALL SELECT $id, $id, 0"; $result = $this->_query($sql); } return $result; } public function getParentBranch($pageId) { $tableName = $this->getTableName(); $treePathTable = $this->getTreePathTableName(); $ancestorField = $this->_options['ancestor']; $descendantField = $this->_options['descendant']; $levelField = $this->_options['level']; $sql = "SELECT p.*, t.*
                FROM `$tableName` AS p
                LEFT JOIN `$treePathTable` AS t ON p.id = t.$ancestorField
                WHERE t.$descendantField = $pageId
                ORDER BY t.$levelField DESC"; $res = $this->_query($sql); return $res; } public function delete($where) { $tableName = $this->getTableName(); $treePathTable = $this->getTreePathTableName(); $ancestorField = $this->_options['ancestor']; $descendantField = $this->_options['descendant']; $nodes = $this->_context->select($where)->toArray(); foreach ($nodes as $node) { $pageId = (int) $node['id']; $sql = "DELETE FROM `$treePathTable` WHERE $ancestorField = $pageId OR $descendantField = $pageId"; $this->_query($sql); $sql = "DELETE FROM `$tableName` WHERE id = $pageId"; $this->_query($sql); } } public function moveNode($id, $newParentId) { $newParentId = (int) $newParentId; $treePathTable = $this->getTreePathTableName(); $ancestorField = $this->_options['ancestor']; $descendantField = $this->_options['descendant']; $levelField = $this->_options['level']; $sql = "DELETE FROM `$treePathTable`
                WHERE `$descendantField` IN (SELECT p.$descendantField
                                     FROM (SELECT * FROM `$treePathTable`) as p
                                     WHERE p.$ancestorField = $id)
                    AND `$ancestorField` IN (SELECT p.$ancestorField
                                     FROM (SELECT * FROM `$treePathTable`) as p
                                     WHERE p.$descendantField = $id
                                     AND p.$ancestorField != p.$descendantField);"; $this->_query($sql); $sql = "INSERT IGNORE INTO `$treePathTable` (`$ancestorField`, `$descendantField`, `$levelField`)
                  SELECT supertree.$ancestorField, subtree.$descendantField, supertree.$levelField + subtree.$levelField + 1
                  FROM `$treePathTable` AS supertree
                  CROSS JOIN `$treePathTable` AS subtree
                  WHERE supertree.$descendantField = $newParentId
                  AND subtree.$ancestorField = $id;"; $this->_query($sql); } }