<?php
namespace Website\Widgets\MailChimp\InputFilter; use Moto; class Subscribe extends Moto\InputFilter\AbstractInputFilter { public function init() { $this->add(array( 'name' => 'list_id', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'Regex', 'break_chain_on_failure' => true, 'options' => array( 'pattern' => '/^[a-z0-9]{8,16}$/', ) ), ), )); $this->add(array( 'name' => 'email', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'EmailAddress', 'options' => array( 'useMxCheck' => false, 'useDeepMxCheck' => false, 'useDomainCheck' => false, ), ), ), )); $this->add(array( 'name' => 'status', 'required' => false, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => 'subscribed' ) ), ), 'validators' => array( array( 'name' => 'InArray', 'options' => array( 'haystack' => array('subscribed', 'unsubscribed', 'cleaned', 'pending') ) ), ), )); $this->add(array( 'name' => 'name', 'required' => false, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), )); $this->add(array( 'name' => 'surname', 'required' => false, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), )); } }