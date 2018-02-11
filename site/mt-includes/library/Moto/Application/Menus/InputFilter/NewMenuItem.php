<?php
namespace Moto\Application\Menus\InputFilter; class NewMenuItem extends SaveMenuItem { protected $_name = 'menuItem.new'; public function init() { parent::init(); $this->remove('id'); } }