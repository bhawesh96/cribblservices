<?php
namespace Moto\InputFilter; use Zend\InputFilter\Exception; class ClickAction extends AbstractInputFilter { public function init() { $this->add(array( 'name' => 'action', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'Regex', 'break_chain_on_failure' => true, 'options' => array( 'pattern' => '/^([a-z0-9\_\-\.]{1,24})$/', ) ), ), )); $this->add(array( 'name' => 'properties', 'required' => true, 'filters' => array( array('name' => 'Moto\Filter\ClickAction', 'options' => array( 'inputFilter' => $this ) ), ), 'continue_if_empty' => true, 'validators' => array( array( 'name' => 'Moto\Validator\ClickAction', ) ), )); $this->add(array( 'name' => 'build', 'required' => false, 'filters' => array( array('name' => 'Moto\Filter\IntValue'), ), 'continue_if_empty' => true, )); } }