<?php
namespace Moto\Application\Pages; use Zend\Filter\AbstractFilter; class ZeroFilter extends AbstractFilter { public function filter($value) { return empty($value) ? 0 : $value; } } 