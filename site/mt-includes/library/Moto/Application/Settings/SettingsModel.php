<?php
namespace Moto\Application\Settings; use Moto\Application\Model; use Moto\Application\Model\AbstractModel; use Moto; class SettingsModel extends AbstractModel { protected $_fields = array( 'id' => array(), 'name' => array( 'default' => '' ), 'value' => array( 'default' => '' ), 'type' => array( 'default' => 'string' ), ); public $id = null; public $name = ''; public $value = ''; public $type = 'string'; public function initFields() { if (Moto\Version::getCurrentBuild() > 0 && Moto\Version::getCurrentBuild() < 26) { unset($this->_fields['type']); } parent::initFields(); } }