<?php
namespace Moto\Application\Styles\InputFilter; use Moto\Application\Styles; use Moto\InputFilter\AbstractInputFilter; use Zend\InputFilter\InputFilterInterface; use Zend\InputFilter\Exception; use Zend\InputFilter; use Traversable; use Moto; class SaveStyle extends AbstractInputFilter { protected $_name = 'styles.save'; protected $_defaultsValue = array( 'class_name' => '', 'properties' => '', 'link' => '', ); public function init() { $this->add(array( 'name' => 'id', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( "name" => "Digits", 'break_chain_on_failure' => true, ), array( 'name' => 'Db\RecordExists', 'options' => array( 'table' => Moto\Config::get('database.prefix') . 'styles', 'field' => 'id', 'adapter' => Moto\Config::get('databaseAdapter'), ) ) ), )); $this->_addElementName(); $this->add(array( 'name' => 'class_name', 'required' => false, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => '' )), array('name' => 'Moto\Filter\Css\ClassName'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'max' => 200, ), ), ), )); $this->add(array( 'name' => 'type', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'max' => 32, ), ), array( 'name' => 'InArray', 'options' => array( 'haystack' => array('text', 'widget', 'background') ) ), ), )); $this->add(array( 'name' => 'properties', 'required' => false, 'allow_empty' => true, 'filters' => array( array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => array() ) ), ) )); $this->add(array( 'name' => 'is_responsive', 'required' => false, 'allow_empty' => false, 'filters' => array( array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => 0 ) ), ) )); $this->add(array( 'name' => 'link', 'required' => false, 'allow_empty' => true, 'filters' => array( array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => array() ) ), ) )); } public function setData($data, $override = true) { $id = isset($data['id']) ? (int)(string)$data['id'] : ''; $this->_addElementName($id); return parent::setData($data); } protected function _addElementName($currentId = '') { $this->remove('name'); $this->add(array( 'name' => 'name', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'min' => 1, 'max' => 200, ), 'break_chain_on_failure' => true, ), array( 'name' => 'Moto\Validator\Db\NoRecordExists', 'options' => array( 'table' => Moto\Config::get('database.prefix') . 'styles', 'field' => 'name', 'where' => array( 'type' => '@equalTo', ), 'exclude' => array( 'field' => 'id', 'value' => $currentId, ) ) ) ), )); } }