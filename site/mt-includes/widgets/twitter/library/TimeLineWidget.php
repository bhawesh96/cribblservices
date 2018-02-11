<?php
namespace Website\Widgets\Twitter; use Moto; class TimeLineWidget extends Moto\System\Widgets\AbstractWidget { protected $_name = 'twitter.time_line'; protected static $_defaultProperties = array( 'spacing' => array( 'top' => 'auto', 'right' => 'auto', 'bottom' => 'auto', 'left' => 'auto', ), 'title' => '', 'params' => array( 'tweet_limit' => 5, 'lang' => 'en', 'theme' => 'light', 'chrome_noheader' => false, 'chrome_nofooter' => false ), ); protected $_widgetId = true; public function getTemplatePath($preset = null) { return '@websiteWidgets/twitter/templates/time_line.twig.html'; } public function getTwitterParam($name, $default = '') { return $this->getPropertyValue('params.' . $name, $default); } public function getTwitterChrome() { $result = array(); if ($this->getTwitterParam('chrome_noheader', false)) { $result[] = 'noheader'; } if ($this->getTwitterParam('chrome_nofooter', false)) { $result[] = 'nofooter'; } if ($this->getTwitterParam('theme') == 'transparent') { $result[] = 'transparent'; } $result = implode(' ', $result); return $result; } public function isValidTwitterSource() { $sourceType = $this->getTwitterParam('sourceType', 'widget'); if ($sourceType == 'widget') { $twitterWidgetId = $this->getTwitterParam('widget_id'); if (!empty($twitterWidgetId) && preg_match('/^([0-9]{18})$/i', $twitterWidgetId)) { return true; } } else { $twitterScreenName = $this->getTwitterParam('screenName'); if (!empty($twitterScreenName)) { return true; } } return false; } } 