<?php
/**
 * Please don't edit this file.
 * This file will be rewrite on update.
 * Thanks.
 */
defined('MOTO_ENGINE') or die;

if (!isset($config)) {
    $config = array();
}

$config['html5Mode'] = false;
$config['apiUrl'] = 'api.php';
$config['allowedExtList'] = array(
    'png', 'jpeg', 'jpg', 'gif', 'ico', 'svg',
    'mp4', 'avi', 'swf', 'mp3',
    'pdf', 'djv', 'djvu', 'doc', 'docx', 'gdoc', 'rtf', 'rtx', 'txt', 'xls', 'xlsx', 'ots', 'odt', 'xml', 'ppt', 'pptx', 'pps', 'log', 'csv',
    'zip', 'rar', '7z', 'tar', 'tgz', 'gz', 'gzip', 'bz2',
    'cdr'
);
$config['deniedExtList'] = array(
    'htaccess', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'cfm', 'cfc', 'bat', 'exe', 'com', 'dll',
    'vbs', 'js', 'reg', 'asis', 'phtm', 'phtml', 'pwml', 'inc', 'pl', 'py', 'jsp', 'asp', 'aspx', 'ascx', 'shtml', 'sh', 'cgi',
    'cgi4', 'pcgi', 'pcgi5', 'cmd',
);

$config['path'] = array(
    'websiteSettings' => '@website/mt-includes/config/settings.php',
    'websiteContentDir' => '@website/mt-content',
    'systemIncludes' => '@website/mt-includes',
    'mainTemplates' => '@website/mt-includes/templates',
    'layoutTemplates' => '@website/mt-includes/templates/layouts',
    'lessTemplates' => '@website/mt-includes/templates/less',
    'emailTemplates' => '@website/mt-includes/templates/emails',
    'phpLibrary' => '@website/mt-includes/library',
    'library' => '@website/mt-includes/library',
    'systemAssets' => '@website/mt-includes/assets',
    'admin' => '@website/mt-admin',
    'cpBrand' => '@website/mt-admin/brand',
    'systemUpdate' => '@website/mt-admin/update',
    'adminTemplates' => '@website/mt-admin/views',
    'adminApplication' => '@website/mt-admin/js',
    'adminStyles' => '@website/mt-admin/css',
    'adminImages' => '@website/mt-admin/images',
    'editorApplication' => '@website/mt-admin/js',
    'editorStyles' => '@website/mt-admin/css',
    'install' => '@website/mt-admin/install',
    'updateTemp' => '@website/mt-content/temp/update',
    'temp' => '@website/mt-content/temp',
    'tempUploads' => '@website/mt-content/temp/uploads',
    'systemLogDir' => '@website/mt-content/temp/logs',
    'themes' => '@website/mt-content/themes',
    'userUploads' => '@website/mt-content/uploads',
    'websiteStorageDir' => '@websiteContentDir/storage/website',
    'productInformationFile' => '@websiteStorageDir/product_information.php',
    'pluginsStorageDir' => '@websiteContentDir/storage/plugins',
    'pluginsTempDir' => '@temp/plugins',
    'userStylesFile' => '@website/mt-content/assets/styles.css',
    'userAssets' => '@website/mt-content/assets',
    'userFontsFile' =>  '@website/mt-content/assets/fonts.css',
    'userLessDir' =>  '@website/mt-content/assets/less',
    'systemWebsiteStyleLessDir' =>  '@website/mt-includes/templates/less/website',
    //default value
    'currentTheme' => '@website/mt-content/themes',

    'websiteWidgets' => '@website/mt-includes/widgets',
    'plugins' => '@website/mt-content/plugins',
    'systemFavicon' => '@website/mt-admin/images/favicon.ico',
    'themeBlocksDataFile' => '@currentTheme/data/blocks/blocks.json',
    'webFontsListFile' => '@websiteStorageDir/web-fonts/data.json',
);
$config['url'] = array(
    'tutorialCenter' => 'https://help.cms-guide.com/tutorial/',
);
$config['mail'] = array(
    //global enabled flag
    'enabled' => true,
    //global options
    'options' => array(
        'fromName' => 'Robot',
        'transport' => array(
            'class' => 'Sendmail', // Sendmail | Smtp | File
            'parameters' => null,
        )
    ),
//@templates settings
);

$config['mail']['forgotPassword'] = array(
    //required
    'template' => 'forgot_password.html',
    //required
    'subject' => 'Reset Password ' . date('Y-m-d H:i:s'),
);

$config['mail']['usersAddNew'] = array(
    //required
    'template' => 'users_add_new.html',
    //required
    'subject' => 'Welcome {{user.name}} ' . date('Y-m-d H:i:s'),
);

$config['database'] = array(
    'driver' => 'Pdo_Mysql',
    'database' => '',
    'username' => '',
    'password' => '',
    'hostname' => 'localhost',
    //'port' => '',
    'charset' => 'utf8',
    'prefix' => '',
    'profiler' => false,
    'driver_options' => array(
        1002 => 'SET NAMES utf8'
    )
);

$config['messenger'] = array(
    'timeout' => 5000
);

$config['serverRequirements'] = array(
    'php_version' => array(
        //@TODO: no good info about 5.4+ version
        '504' => array(
            'min' => '5.4.04',
            'recommended' => '5.4.20'
        ),
        '505' => array(
            'min' => '5.5.0',
            'recommended' => '5.5.09'
        ),
        '506' => array(
            'min' => '5.6.03',
            'recommended' => '5.6.03'
        ),
        '700' => array(
            'min' => '7.0.0',
            'recommended' => '7.0.0',
        ),
        '701' => array(
            'min' => '7.1.0',
            'recommended' => '7.1.0',
        ),
    ),
    'php_upload_file_size' => '2M',
    'enough_disk_space' => '40M',
    'extensions' => array(
        'curl' => array(),
        'spl' => array(),
        'openssl' => array(),
        'gd' => array('level' => 'warning'),
        'pdo_mysql' => array(),
        'mbstring' => array(),
        'iconv' => array(),
        //'fileinfo' => array(), temporary remove, need more testing
    ),
    'files' => array(
        'path.websiteSettings' => array('type' => 'file'),
        'path.userStylesFile' => array('type' => 'file'),
        'path.userFontsFile' => array('type' => 'file'),
        'path.userAssets' => array('type' => 'folder'),
        'path.userLessDir' => array('type' => 'folder'),
        'path.userUploads' => array('type' => 'folder'),
        'path.websiteStorageDir' => array('type' => 'folder'),
        'path.pluginsStorageDir' => array('type' => 'folder'),
        'path.temp' => array('type' => 'folder'),
        'path.pluginsTempDir' => array('type' => 'folder'),
        'path.updateTemp' => array('type' => 'folder'),
        'path.tempUploads' => array('type' => 'folder'),

        'path.mainTemplates' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.layoutTemplates' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.lessTemplates' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.emailTemplates' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.phpLibrary' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.systemAssets' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.adminTemplates' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.systemUpdate' => array('type' => 'folder'),
        'path.systemLogDir' => array('type' => 'folder'),
        'path.themes' => array('type' => 'folder', 'validator' => 'file_readable'),
        'path.websiteWidgets' => array('type' => 'folder', 'validator' => 'file_readable'),
    ),
    'functions' => array(
        'json_encode' => array(),
        'session_start' => array(),
        'session_name' => array(),
        'xml_parser_create' => array(),
        'iconv_mime_decode' => array(),
        'timezone_identifiers_list' => array('level' => 'warning'),
        'date_default_timezone_set' => array('level' => 'warning'),
    ),
    /*
    // sample for extra validator list
    'validators' => array(
        array(
            'type' => 'php_extension_loaded',
            'params' => array(
                'extension' => 'aaaaaaaa'
            )
        ),
    ),
    */
);

$config['latestReleaseUrl'] = 'http://updates.motocms.com/moto3/latest/';

// brand value
$config['brand'] = array(
    'apiUrl' => 'http://accounts.motocms.com/api/brand/checkbrand/',
    'options' => array(
        'support_feedback_recipient_email' => 'feedback@motocms.com',
    ),
    'features' => array(
        'cp_help_center' => true,
        'cp_tutorial_center' => true,
        'cp_support_feedback' => true,
        'cp_theme_full_info' => true,
    ),
    'media' => array(
        'favicon.ico' => array(
            'path' => '@adminImages/favicon.ico',
        ),
        'loader.gif' => array(
            'path' => '@adminImages/loader.gif',
        ),
        'logo_small.png' => array(
            'path' => '@adminImages/logo_small.svg',
        ),
        'logo_medium.png' => array(
            'path' => '@adminImages/logo_medium.svg',
        ),
        'logo_large.png' => array(
            'path' => '@adminImages/logo_large.svg',
        ),
    ),
    'translations' => array(
        'COMPANY' => array(
            'NAME' => 'Moto',
            'WEBSITE' => 'http://www.motocms.com/',
            'EMAIL' => '',
            'DESCRIPTION' => '',
        ),
        'CONTROL_PANEL' => array(
            'NAME' => 'MotoCMS',
        ),
        'LINK' => array(
            'ACTIVATION' => 'https://accounts.motocms.com/',
            'HELP_CENTER' => "https://support.cms-guide.com/hc/{{'CURRENT_LANGUAGE'|translate}}/categories/200224390-MotoCMS-3",
            'HELP_CENTER_REQUEST' => "http://support.cms-guide.com/hc/{{'CURRENT_LANGUAGE'|translate}}/requests/new",
            'HELP_ERROR_NETWORK' => 'http://support.cms-guide.com/entries/20683912-network-error-please-check-your-connection',
            //EN http://support.cms-guide.com/entries/20683912-network-error-please-check-your-connection
            //DE http://support.cms-guide.com/entries/20683912-network-error-please-check-your-connection
            //RU http://support.cms-guide.com/entries/20902091
            'TERMS_OF_USE' => '#',
            'PRIVACY_POLICY' => '#',
            'LICENCE_AGREEMENT' => '#',
        ),
    )
);

// Configuration for render engine
$config['renderEngine'] = array(
    '__default' => array(
        'loader' => array(
            'type' => 'Twig_Loader_Filesystem',
            'options' => array()
        ),
        'environment' => array(
            'auto_reload' => true,
            'cache' => '@temp/twig/'
        ),
        'extensions' => array(
            'assetExtension' => array(
                'options' => array(
                    'antiCache' => true
                )
            ),
        ),
        'addPath' => array(
            'systemAssets' => 'systemAssets',
            'layoutTemplates' => 'layoutTemplates',
            'mainTemplates' => 'mainTemplates',
            'websiteWidgets' => 'websiteWidgets',
            'lessTemplates' => 'lessTemplates',
            'emailTemplates' => 'emailTemplates',
            'currentTheme' => 'currentTheme',
            'userAssets' => 'userAssets',
            'systemIncludes' => 'systemIncludes',
            'userUploads' => 'userUploads',
            'websiteStorageDir' => 'websiteStorageDir',
            'pluginsStorageDir' => 'pluginsStorageDir',
            'plugins' => 'plugins',
        ),
    ),
    '__main__' => array(
        'loader' => array(
            'options' => array('@mainTemplates')
        ),
    ),
    'admin' => array(
        'loader' => array(
            'options' => array('@adminTemplates')
        ),
        'extensions' => array(
            'assetExtension' => array(
                'options' => array(
                    'baseUrl' => '../'
                )
            )
        ),
    ),
    'string' => array(
        'loader' => array(
            'type' => 'Twig_Loader_String'
        ),
        'environment' => array(
            'cache' => null
        ),
    ),
);

$config['cacheEngine'] = array(
    'disabled' => array(
        'enabled' => true,
        'adapter' => array(
            'name' => 'memory',
            'options' => array(
                'memory_limit' => '10%',
            ),
        ),
    ),
    'default' => array(
        'enabled' => false,
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'ttl' => 3600,
                'cache_dir' => '@temp/cache/',
            ),
        ),
    ),
);

$config['autoloaderOptions'] = array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            'Zend' => '@library/Zend',
            'Moto\Update' => '@admin/update/library/Moto/Update',
            'Moto' => '@library/Moto',
        ),
        'prefixes' => array(
            'Twig' => '@library/Twig',
        )
    ),
    'Zend\Loader\ClassMapAutoloader' => array(),
    'Moto\System\Widgets\Autoloader' => array(
        'paths' => array(
            '@websiteWidgets'
        )
    )
);

$config['systemRecommend'] = array(
    'memory_limit' => '256M'
);

$config['__features__'] =  array(
    'thumbnails' => extension_loaded('gd')
);

$config['api'] = array(
    'settings' => array(
        // 0 - no limits
        'concurrentConnections' => 3,
        'url' => 'api.php',
    ),
);

// @TODO : review for names
$config['externalModules'] = array(
    'accounts' => array(
        'appId' => '687373f3f6f25a50d46d497c5e037f43',
        'authUrl' => 'https://accounts.motocms.com/signin/jwt/authorize',
        'productInfoUrl' => 'https://accounts.motocms.com/install/product/',
    ),
    'webFonts' => array(
        'apiUrl' => 'http://fonts-service.moto3dev.cms-guide.com/api',
    ),
);
