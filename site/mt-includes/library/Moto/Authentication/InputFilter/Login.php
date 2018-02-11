<?php
namespace Moto\Authentication\InputFilter; use Moto; class Login extends Moto\InputFilter\AbstractInputFilter { public function init() { $this->add(array( 'name' => 'email', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'min' => 3, 'max' => 100, ), ), array( 'name' => 'EmailAddress', 'options' => array( 'useMxCheck' => false, 'useDeepMxCheck' => false, 'useDomainCheck' => false, ), ), ), )); $this->add(array( 'name' => 'password', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'min' => 6, 'max' => 100, ), ), ), )); $this->add(array( 'name' => 'remember', 'filters' => array( array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => false ) ), ), 'required' => false, 'validators' => array( array( 'name' => 'InArray', 'options' => array( 'haystack' => array(false, true) ) ), ), )); } } 