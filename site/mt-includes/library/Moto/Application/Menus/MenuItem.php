<?php
namespace Moto\Application\Menus; use Moto\Application\Model; use Moto\Application\Model\AbstractModel; use Moto\ClickAction\ClickActionInterface; use Moto; class MenuItem extends AbstractModel implements ClickActionInterface { public $status; protected $_fields = array( 'id' => array(), 'menu_id' => array( 'default' => 0 ), 'parent_id' => array( 'default' => 0 ), 'label' => array( 'default' => '' ), 'action' => array( 'default' => 'none' ), 'properties' => array( 'default' => array() ), 'order' => array( 'default' => 0 ), 'status' => array( 'default' => 'draft' ), 'modified' => array(), 'created' => array(), ); protected $_skipDefaults = false; protected $_clickAction = null; public function __construct() { parent::__construct(); $this->created = date('Y-m-d H:i:s'); $this->modified = $this->created; } public function toInsert() { $data = parent::toInsert(); if (!isset($data['properties'])) { $data['properties'] = array(); } if (!is_string($data['properties'])) { if (!empty($data['properties']['target']) && $data['properties']['target'] == 'self') { $data['properties']['target'] = '_self'; } $data['properties'] = json_encode($data['properties']); } return $data; } public function toUpdate() { $this->modified = date('Y-m-d H:i:s'); $data = parent::toUpdate(); if (!isset($data['properties'])) { $data['properties'] = array(); } if (!is_string($data['properties'])) { if (!empty($data['properties']['target']) && $data['properties']['target'] == 'self') { $data['properties']['target'] = '_self'; } $data['properties'] = json_encode($data['properties']); } return $data; } public function publish() { $this->status = 'publish'; } public function getHtmlAttributes() { $options = $this->properties; if (is_string($options)) { $options = \Zend\Json\Json::decode($options, 1); } if (empty($options)) { $options = array(); } $attr = Moto\ClickAction\Factory::create($this->action, $options)->generateTagAttrs(); return $attr; } protected function _postExchangeArray($data) { parent::_postExchangeArray($data); if (is_string($this->properties)) { $this->properties = json_decode($this->properties, true); } } }