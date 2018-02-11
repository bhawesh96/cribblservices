<?php
namespace Website\Widgets\ContactForm; use Moto; use Moto\Service\AbstractService; use Zend; class Service extends AbstractService { const ERROR_UNABLE_TO_SEND_MESSAGE = 'COMMON.ERROR.UNABLE_TO_SEND'; const ERROR_UNABLE_TO_SEND_CODE = 500; protected $_resourceName = ''; protected $_mailSubject = 'New message from {{ message.email }}'; public function sendMessage($message, $placeholder, $hash = null) { if (Moto\Config::get('isDemoMode')) { return true; } if (isset($message['checkbox'])) { if ($message['checkbox']) { $message['checkbox'] = 'Yes'; } else { $message['checkbox'] = 'No'; } } $inputFilter = new InputFilter\Message(); $inputFilter->setData($message); if (!$inputFilter->isValid()) { throw new Moto\System\Exception(Moto\System\Exception::ERROR_BAD_REQUEST_MESSAGE, Moto\System\Exception::ERROR_BAD_REQUEST_CODE, $inputFilter->getMessagesKeys()); } $message = $inputFilter->getValues(); $filter = new InputFilter\Placeholder(); $filter->setData($placeholder); $placeholder = $filter->getValues(); $params = array(); if (!empty($message['_attachments']) && $message['_attachments'] > 0) { $fileRequest = new Zend\Http\PhpEnvironment\Request(); $inputFilter = new InputFilter\UploadFile(); $inputFilter->setData($fileRequest->getFiles()->toArray()); if (!$inputFilter->isValid()) { throw new Moto\System\Exception(Moto\System\Exception::ERROR_BAD_REQUEST_MESSAGE, Moto\System\Exception::ERROR_BAD_REQUEST_CODE, $inputFilter->getMessagesKeys()); } $file = $inputFilter->getValue('file'); if (empty($file['path'])) { $file['path'] = $file['tmp_name']; } $params['attachments'][] = $file; } $render = Moto\Render::getInstance('string'); $data = array( 'message' => $message, 'placeholder' => $placeholder, 'website' => Moto\Website\Settings::get(), 'user' => $this->_getSenderDetails() ); $subject = $render->render($this->_mailSubject, $data); $body = $render->render($this->_getBodyTemplate(), $data); if (!empty($hash)) { $hash = Moto\System::decrypt($hash); $params['to']['email'] = Moto\Util::getFromArrayDeep($hash, 'settings.recipient'); } $params['mailName'] = 'contactForm'; $params['subject'] = $subject; $params['body'] = $body; try { $result = Moto\Application\Util\Mailer::sendMail($params); if (isset($params['attachments']) && is_array($params['attachments'])) { foreach($params['attachments'] as $file) { @unlink($file['tmp_name']); } } } catch (\Exception $e) { Moto\System\Log::error(static::ERROR_UNABLE_TO_SEND_MESSAGE, array('exception_code' => $e->getCode(), 'exception_message' => $e->getMessage(), 'message' => $message, 'user' => $this->_getSenderDetails())); throw new Moto\System\Exception(static::ERROR_UNABLE_TO_SEND_MESSAGE, static::ERROR_UNABLE_TO_SEND_CODE); } return $result; } protected function _getBodyTemplate() { $template = ''; $file = __DIR__ . '/../templates/default.html.twig'; if (file_exists($file)) { $template = file_get_contents($file); } return $template; } protected function _getSenderDetails() { $data = array( 'browser' => (empty($_SERVER['HTTP_USER_AGENT']) ? null : $_SERVER['HTTP_USER_AGENT']), 'ip' => (empty($_SERVER['REMOTE_ADDR']) ? null : $_SERVER['REMOTE_ADDR']), 'referrer' => (empty($_SERVER['HTTP_REFERER']) ? null : $_SERVER['HTTP_REFERER']), ); return $data; } } 