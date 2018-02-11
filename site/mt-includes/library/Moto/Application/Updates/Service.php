<?php
namespace Moto\Application\Updates; use Moto\Json\Server; use Moto; class Service extends Moto\Service\AbstractStaticService { protected static $_resourceName = 'updates'; protected static $_resourcePrivilegesMap = array( 'getInfo' => 'get', ); protected static $_releaseInfoFile = 'release.json'; protected static $_releaseFile = 'release.zip'; protected static $_releaseFilePath = ''; protected static $_themeSuffix = '_latest.zip'; protected static $_releaseInfo = null; protected static $_updaterInstance = array(); protected static $_updaterClassMap = array( 'engine' => 'Moto\Update\UpdaterEngine', 'theme' => '', 'plugin' => '', ); protected static $_remoteUpdateRequest = array( 'httpMethod' => 'GET', 'postLikeJson' => false, 'checking' => null, 'httpAuthHeader' => 'Authorization', 'httpAuthSchema' => 'Bearer', 'httpProductInformation' => 'X-Product-Information', ); protected static $_accessToken; public static function getInfo($request = null) { $latest = static::_getReleaseInfo(); $currentBuild = Moto\System\Settings::get('build'); $currentVersion = Moto\System\Settings::get('version'); $latestBuild = $latest['engine']['build']; $latestVersion = $latest['engine']['version']; $needUpdate = static::_compareVersions($latestBuild, $currentBuild) == 1; $info = array( 'engine' => array( 'current_version' => $currentVersion, 'current_build' => $currentBuild * 1, 'latest_version' => $latestVersion, 'latest_build' => $latestBuild * 1, 'update' => $needUpdate ), 'themes' => static::_checkThemesUpdates($latest['themes']), 'brand' => Moto\System\Brand::getInstance()->getUpdatedBrandInfo(), 'productInformation' => static::getProductInformation(), ); return $info; } public static function getBrand($request = null) { return Moto\System\Brand::getInstance()->getUpdatedBrandInfo(); } protected static function _checkThemesUpdates($themes) { $updates = array(); foreach ($themes as $name => $settings) { $currentSettings = Moto\Website\Theme::getInfo($name); if (is_null($currentSettings)) { continue; } if (!isset($settings['build']) || !isset($settings['min_engine_build'])) { continue; } if ($settings['build'] > $currentSettings['build']) { $updates[$name] = array( 'version' => $settings['version'], 'min_engine_build' => $settings['min_engine_build'] ); } } return $updates; } public static function download($request = null) { $themeName = null; $updatedFile = null; static::_checkRequirements(); if (null === $request) $request = static::getRequest()->getParams(); static::$_accessToken = Moto\Util::getValue($request, 'token'); if (!empty($request['theme'])) { $themeName = trim((string) $request['theme']); if (!preg_match('/^[a-z0-9\_\-]+$/i', $themeName)) { throw new Server\Exception('COMMON.ERROR.DOWNLOAD_FAILED', 400, array( array( 'name' => 'THEME_NAME_NOT_VALID' ) )); } $url = static::_getThemeUpdateUrl($themeName); $updatedFile = $themeName . static::$_themeSuffix; } else { $releaseInfo = static::_getReleaseInfo(true, 'engine'); static::_checkRequirementsByReleaseInfo($releaseInfo); $url = $releaseInfo['url']; $updatedFile = static::$_releaseFile; } static::$_releaseFilePath = Moto\System::getAbsolutePath('@updateTemp/' . $updatedFile); set_time_limit(0); $client = new Moto\Http\Client($url); static::_updateHttpClient($client); $client->send(); $client->getAdapter()->close(); if ($client->hasErrors()) { throw new Server\Exception('COMMON.ERROR.DOWNLOAD_FAILED', 500, $client->getErrors()); } $body = $client->getResponse()->getBody(); Moto\Util::filePutContents(static::$_releaseFilePath, $body); if (!static::_checkDownloadedRelease($themeName)) { throw new Server\Exception('COMMON.ERROR.DOWNLOAD_FAILED', 500, array( array( 'name' => 'BROKEN_ZIP_ARCHIVE' ) )); } return true; } protected static function _getThemeUpdateUrl($themeName) { $url = null; $updateInfo = static::_getReleaseInfo(true, 'themes'); if (array_key_exists($themeName, $updateInfo)) { $url = $updateInfo[$themeName]['url']; } return $url; } public static function install($request = null) { $themeName = null; if (null === $request) $request = static::getRequest()->getParams(); if (!empty($request['theme'])) { $themeName = trim((string) $request['theme']); if (!preg_match('/^[a-z0-9\_\-]+$/i', $themeName)) { throw new Server\Exception('COMMON.ERROR.DOWNLOAD_FAILED', 400, array( array( 'name' => 'THEME_NAME_NOT_VALID' ) )); } } $result = null; try { $options = array(); $updater = new Moto\Update\Core($options); if (isset($themeName)) { $result = $updater->executeUpdateTheme($themeName, static::$_themeSuffix); } else { $result = $updater->execute(); } } catch (\Exception $e) { $result = false; } if (!$result) { throw new Server\Exception('UPDATE.FAILED', 200, $updater->getErrors()); } return $result; } public static function executeAction($request = null) { $filter = new InputFilter\ExecuteAction(); $filter->setData(static::getRequest()->getParams()); if (!$filter->isValid()) { throw new Server\Exception(Moto\System\Exception::ERROR_BAD_REQUEST_MESSAGE, Moto\System\Exception::ERROR_BAD_REQUEST_CODE, $filter->getMessagesKeys()); } $request = $filter->getValues(); $updater = static::_getUpdaterInstance($request['type']); if (!$updater) { throw new Server\Exception('UPDATE.FAILED.UNKNOWN_UPDATER', 404); } $response = array( 'status' => true, 'action' => $request['action'], ); try { $response['result'] = $updater->executeAction($request['action'], $request['params']); } catch (\Exception $e) { if (Moto\System::isDevelopmentStage()) { Moto\System\Log::critical(__CLASS__ . '::' . __FUNCTION__ . ' catch exception "' . $e->getMessage() . '"', array( 'request' => $request, 'exception' => array( 'code' => $e->getCode(), 'message' => $e->getMessage(), 'class' => get_class($e), ) )); } $response['status'] = false; } if (!$response['status']) { throw new Server\Exception('UPDATE.FAILED', 200, $updater->getErrors()); } return $response; } protected static function _getUpdaterInstance($type) { $type = strtolower($type); if (array_key_exists($type, static::$_updaterInstance)) { return static::$_updaterInstance[$type]; } $className = Moto\Util::getValue(static::$_updaterClassMap, $type, ''); if (is_string($className) && class_exists($className)) { $instance = new $className(); static::$_updaterInstance[$type] = null; if ($instance instanceof Moto\Update\Core) { static::$_updaterInstance[$type] = $instance; } } else { return null; } return static::$_updaterInstance[$type]; } protected static function _checkDownloadedRelease($theme = null) { $isValid = false; if (!is_null($theme)) { $releaseInfo = static::_getReleaseInfo(false, 'themes'); $releaseInfo = Moto\Util::getValue($releaseInfo, $theme, array()); } else { $releaseInfo = static::_getReleaseInfo(false, 'engine'); } if (empty($releaseInfo['fileinfo'])) { return $isValid; } if (!file_exists(static::$_releaseFilePath)) { return false; } if (!empty($releaseInfo['fileinfo']['size'])) { $isValid = ($releaseInfo['fileinfo']['size'] === filesize(static::$_releaseFilePath)); } if (!empty($releaseInfo['fileinfo']['md5'])) { $isValid &= ($releaseInfo['fileinfo']['md5'] === md5_file(static::$_releaseFilePath)); } if (!empty($releaseInfo['fileinfo']['sha1'])) { $isValid &= ($releaseInfo['fileinfo']['sha1'] === sha1_file(static::$_releaseFilePath)); } return $isValid; } protected static function _getReleaseInfo($force = false, $type = '') { if (static::$_releaseInfo === null || $force) { $data = static::_getLatestReleaseInfo(); static::$_releaseInfo = \Zend\Json\Json::decode($data, 1); } switch ($type) { case 'engine': $info = static::$_releaseInfo['engine']; break; case 'themes': $info = static::$_releaseInfo['themes']; break; default: $info = static::$_releaseInfo; } return $info; } protected static function _checkRequirements() { $requirements = new Moto\Application\ServerTest\Validator\Requirements(); if (!file_exists(Moto\System::getAbsolutePath('@updateTemp'))) { @mkdir(Moto\System::getAbsolutePath('@updateTemp')); } $requirements->addValidator('php_extension_loaded', array('extension' => 'curl')); $requirements->addValidator('file_writable', array('name' => Moto\System::getRelativePath('@updateTemp'), 'type' => 'folder')); $requirements->addValidator('disk_free_space', array('enough_space' => Moto\Config::get('serverRequirements.enough_disk_space'))); if (!$requirements->isValid()) { $errors = $requirements->getErrors(); throw new Server\Exception('COMMON.ERROR.TEST_FAILED', 200, $errors); } return true; } protected static function _checkRequirementsByReleaseInfo($releaseInfo) { if (!isset($releaseInfo['requirements'])) { return true; } $errors = array(); $validator = new Moto\Application\ServerTest\Validator\Requirements(); $requirements = $releaseInfo['requirements']; if (isset($requirements['php_version'])) { $phpVersionKey = substr(PHP_VERSION_ID, 0, 3); $phpVersionRequirements = Moto\Util::getFromArrayDeep($requirements, 'php_version.' . $phpVersionKey); if (empty($phpVersionRequirements)) { $errors[] = array( 'name' => 'PHP_VERSION', 'params' => array( 'level' => 'error', 'php_version' => PHP_VERSION ), ); } else { $validator->addValidator('php_version', $phpVersionRequirements); } } if (isset($requirements['enough_disk_space'])) { $validator->addValidator('disk_free_space', array('enough_space' => $requirements['enough_disk_space'])); } if (isset($requirements['extensions'])) { $extensions = $requirements['extensions']; if (!empty($extensions) && is_array($extensions)) { foreach ($extensions as $name => $params) { if (null === $params) { continue; } if (empty($params['extension'])) { $params['extension'] = $name; } $validator->addValidator('php_extension_loaded', $params); } } } if (isset($requirements['functions'])) { $functions = $requirements['functions']; if (!empty($functions) && is_array($functions)) { foreach ($functions as $name => $params) { if (null === $params) { continue; } if (empty($params['function'])) { $params['function'] = $name; } $validator->addValidator('php_function_exists', $params); } } } if (!$validator->isValid() || !empty($errors)) { $errors = array_merge($errors, $validator->getErrors()); throw new Server\Exception('COMMON.ERROR.TEST_FAILED', 200, $errors); } return true; } protected static function _getRequestParams() { $theme = Moto\Website\Settings::get('theme'); $themeInfo = Moto\Website\Theme::getInfo($theme); $timezone = null; if (function_exists('date_default_timezone_get')) { $timezone = @date_default_timezone_get(); } $websitePath = Moto\System::getAbsolutePath('@website'); $data = array( 'theme' => $theme, 'website' => Moto\Website\Settings::get('address'), 'host' => Moto\System\Request::getHttpHost(), 'ip' => Moto\System\Request::getIp(), 'build' => Moto\System\Settings::get('build'), 'version' => Moto\System\Settings::get('version'), 'time' => time(), 'product_id' => Moto\Config::get('__product_id__'), 'template_id' => Moto\Util::getValue($themeInfo, 'template_id', ''), 'theme_version' => Moto\Util::getValue($themeInfo, 'version', ''), 'theme_build' => Moto\Util::getValue($themeInfo, 'build', ''), 'website_path' => $websitePath, 'brand' => Moto\Util::getValue(Moto\System\Brand::getInstance()->getInfo(), 'name', 'motocms'), 'php' => PHP_VERSION, 'mysql' => Moto\Util::getValue(Moto\System::getDatabaseInformation(), 'serverVersion'), 'engine' => Moto\Util::getValue(Moto\System\Settings::getEngine()->getPublicData(), 'type', ''), ); $checking = Moto\Util::getValue(static::$_remoteUpdateRequest, 'checking'); if (!is_array($checking)) { $checking = array(); } if (in_array('php', $checking)) { $data['php'] = array( 'version' => PHP_VERSION, 'os' => PHP_OS, 'memory_limit' => @ini_get('memory_limit'), 'timezone' => $timezone, ); } if (in_array('mysql', $checking)) { $data['mysql'] = Moto\Util::arrayOnly(Moto\System::getDatabaseInformation(), array('driverName', 'serverVersion')); } if (in_array('server', $checking)) { $data['server'] = array( 'disk_free_space' => disk_free_space($websitePath), 'disk_total_space' => disk_total_space($websitePath), ); $env = array( 'HTTP_USER_AGENT', 'DOCUMENT_ROOT', 'REQUEST_SCHEME', 'GATEWAY_INTERFACE', 'SERVER_ADDR', 'SERVER_SIGNATURE', 'SERVER_SOFTWARE', 'SERVER_PROTOCOL', 'SERVER_PORT', ); foreach ($env as $name) { if (empty($_SERVER[$name])) { continue; } $data['server'][$name] = $_SERVER[$name]; } } return $data; } protected static function _getLatestReleaseInfo() { $url = Moto\Config::get('latestReleaseUrl') . static::$_releaseInfoFile; $requestParams = static::_getRequestParams(); $client = new Moto\Http\Client(); $url .= '?version=2'; $httpMethod = Moto\Util::getValue(static::$_remoteUpdateRequest, 'httpMethod', 'GET'); if ($httpMethod === 'POST') { $client->setMethod($httpMethod); if (Moto\Util::getValue(static::$_remoteUpdateRequest, 'postLikeJson', false) === true) { $client->setRawBody(json_encode($requestParams)); } else { $client->setParameterPost($requestParams); } } else { $url .= '&request=' . urlencode(base64_encode(json_encode($requestParams))); } $client->setUri($url); static::_updateHttpClient($client); $client->send(); if ($client->hasErrors()) { throw new Server\Exception(Moto\System\Exception::ERROR_BAD_REQUEST_MESSAGE, Moto\System\Exception::ERROR_BAD_REQUEST_CODE, $client->getErrors()); } $client->getAdapter()->close(); $body = $client->getResponse()->getBody(); $releaseInfoFilePath = Moto\System::getAbsolutePath('@updateTemp/' . static::$_releaseInfoFile); if (file_exists($releaseInfoFilePath) && is_writable($releaseInfoFilePath) || is_writable(Moto\System::getAbsolutePath('@updateTemp'))) { Moto\Util::filePutContents($releaseInfoFilePath, $body); } return $body; } protected static function _compareVersions($version1, $version2) { return $version1 > $version2 ? 1 : ($version1 < $version2 ? -1 : 0); } public static function getProductInformation() { $user = Moto\Authentication\Service::getUser(); $template = Moto\Website\Theme::getInfo(); $brandInfo = Moto\System\Brand::getInstance()->getInfo(); $engineInfo = Moto\System\Settings::getEngine()->getPublicData(); $engineInfo['version'] = Moto\Version::getCurrentVersion(); $engineInfo['build'] = Moto\Version::getCurrentBuild(); $engineInfo['brand'] = Moto\Util::getValue($brandInfo, 'name', 'motocms'); $databaseInfo = Moto\System::getDatabaseInformation(); $response = array( 'version' => 1, 'productId' => Moto\Config::get('__product_id__'), 'engine' => $engineInfo, 'user' => array( 'email' => $user->email, 'locale' => $user->language_code, ), 'server' => array( 'ipServer' => Moto\Util::getValue($_SERVER, 'SERVER_ADDR'), 'hostName' => Moto\System\Request::getHttpHost(), 'ipUser' => Moto\System\Request::getIp(), 'hostDir' => Moto\Util::getValue($_SERVER, 'DOCUMENT_ROOT'), 'mysqlVersion' => Moto\Util::getValue($databaseInfo, 'serverVersion'), 'phpVersion' => PHP_VERSION, 'timestamp' => time(), ), 'template' => array( 'id' => Moto\Util::getValue($template, 'template_id'), ), 'website' => array( 'url' => Moto\System::getAbsoluteUrl(), 'locale' => Moto\Website\Settings::get('language_code'), ), ); return $response; } protected static function _updateHttpClient($client) { if (!$client) { return false; } try { $headers = $client->getRequest()->getHeaders(); if (is_string(static::$_accessToken)) { $headers->addHeaderLine(static::$_remoteUpdateRequest['httpAuthHeader'], static::$_remoteUpdateRequest['httpAuthSchema'] . ' ' . static::$_accessToken); } } catch (\Exception $e) { } return true; } } 