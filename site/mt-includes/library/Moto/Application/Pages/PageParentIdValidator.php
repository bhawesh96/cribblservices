<?php
namespace Moto\Application\Pages; use Zend\Validator\Db; use Moto\Validator\AbstractValidator; use Moto; class PageParentIdValidator extends AbstractValidator { const SAME_PARENT_AS_ID = 'sameParentAsId'; const NOT_DIGIT = 'notDigit'; const NOT_EXISTS = 'notExists'; const BROKEN_TREE = 'brokenTree'; protected $messageTemplates = array( self::SAME_PARENT_AS_ID => 'Specified parent_id is the same as id', self::NOT_DIGIT => "Not a digit", self::NOT_EXISTS => "Page not exists", self::BROKEN_TREE => "Specified id breaks a tree" ); protected $_context = array(); public function isValid($value, array $context = array()) { $this->setValue($value); if ($value == 0) { return true; } $this->_context = $context; if (!is_numeric($value)) { $this->error(self::NOT_DIGIT); return false; } if ($this->_context['id'] == $value) { $this->error(self::SAME_PARENT_AS_ID); return false; } $pageTable = new PagesTable(); $parentPage = $pageTable->getById($value); if (!$parentPage) { $this->error(self::NOT_EXISTS); return false; } $pageTable->useResultAsModel(true); $page = $pageTable->getById($this->_context['id']); $children = $page->getChildren(); foreach($children as $child) { if ($child['id'] == $value) { $this->error(self::BROKEN_TREE); return false; } } return true; } }