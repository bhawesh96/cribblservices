<?php
namespace Moto\Application\Languages; use Moto\Application\Model; use Moto\Application\Model\AbstractModel; class LanguageModel extends AbstractModel { public $id = null; public $name = 0; public $del = 0; protected $_fields = array( 'id' => array(), 'code' => array( 'default' => '', ), 'locale' => array( 'default' => '', ), 'name' => array( 'default' => '', ), 'del' => array( 'default' => 0, ), 'modified' => array(), 'created' => array(), ); public function __construct() { parent::__construct(); $this->created = date('Y-m-d H:i:s'); $this->modified = $this->created; } } 