<?php
namespace Moto\Application\Settings; use Zend\Uri\Uri; class WebsiteAddressHandler extends Uri { protected $validHostTypes = self::HOST_DNS; public function isValid() { if (!in_array($this->getScheme(), array('http', 'https'))) { return false; } if (!$this->host || (strlen($this->path) > 0 && substr($this->path, 0, 1) != '/')) { return false; } if ($this->userInfo || $this->port) { return false; } if ($this->path && substr($this->path, 0, 2) == '//') { return false; } return true; } }