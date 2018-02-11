<?php
namespace Moto\Filter; use Moto; class IntValue extends AbstractFilter { public function filter($value) { if (!is_scalar($value)) { return $value; } return (int) $value; } } 