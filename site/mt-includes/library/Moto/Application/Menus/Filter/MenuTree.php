<?php
namespace Moto\Application\Menus\Filter; use Moto\Filter\AbstractFilter; use Moto\Application\Menus\InputFilter; class MenuTree extends AbstractFilter { public function filter($items) { if (!is_array($items) || empty($items)) { return $items; } $inputFilter = new InputFilter\MenuTreeItem(); $result = array(); foreach($items as $item) { $inputFilter->setData($item); $result[] = $inputFilter->getValues(); } return $result; } } 