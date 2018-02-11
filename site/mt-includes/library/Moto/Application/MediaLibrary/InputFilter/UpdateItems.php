<?php
namespace Moto\Application\MediaLibrary\InputFilter; use Moto\InputFilter\AbstractInputFilter; use Moto; class UpdateItems extends AbstractInputFilter { protected $_name = 'mediaLibrary.updateItems'; public function init() { $this->add(array( 'name' => 'id', 'type' => 'Zend\InputFilter\ArrayInput', 'required' => true, 'validators' => array( array( 'name' => 'NotEmpty' ), array( 'name' => 'Digits' ) ) )); $this->add(array( 'name' => 'update', 'required' => false, 'validators' => array( array('name' => 'Moto\Application\MediaLibrary\Validator\UpdateItem') ) )); } }