<?php
namespace Moto\Application\Presets\InputFilter; use Moto; class Create extends Moto\InputFilter\AbstractInputFilter { protected $_name = 'presets.new'; public function init() { if (1==11) $this->add(array( 'name' => 'name', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'min' => 1, 'max' => 200, ), ), ), )); $this->add(array( 'name' => 'name', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'min' => 2, 'max' => 200, ), 'break_chain_on_failure' => true, ), array( 'name' => 'Moto\Validator\Db\NoRecordExists', 'options' => array( 'table' => Moto\Config::get('database.prefix') . 'presets', 'field' => 'name', 'where' => array( 'widget_name' => '@equalTo', ), 'exclude' => array( 'field' => 'id', 'value' => '@equalTo', ) ) ) ), )); $this->add(array( 'name' => 'widget_name', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), array('name' => 'StringToLower'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'min' => 1, 'max' => 32, ), ), ), )); $this->add(array( 'name' => 'is_responsive', 'required' => false, 'allow_empty' => false, 'filters' => array( array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => 0 ) ), ) )); $this->add(array( 'name' => 'properties', 'required' => false, 'allow_empty' => true, 'filters' => array( array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => array() ) ), ) )); parent::init(); } }