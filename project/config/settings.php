<?php
error_reporting(0);

define('ENVIRONMENT', 'GLOBAL');

define('MYSQL_HOST',        'HOST_NAME');
define('MYSQL_USERNAME',    'USER_NAME');
define('MYSQL_PASSWORD',    'DB_PASSWORD');
define('MYSQL_DB',          'DB_NAME');
define('MYSQL_TABLE_PREFIX','DB_PREFIX');

define('BASE_URL',ROOT_URL.'CONFIG_BASE_URL');


define('IMAGE_URL', BASE_URL . 'project/styles/images/');
define('IMAGE_FILE_URL', BASE_URL . 'project/files/');

define('PAGE_LIST_COUNT', 10);
define('SANDBOX', 'no');


define('FAVICON', 'favicon.ico');

define('PRODUCT_BILLING_INTERVAL', 'Y'); // Default Billing Interval
define('PRODUCT_PURCHASE_SPAN', '1'); // Default Billing Duration
define('FREE_TRIAL_SPAN', '14');
define('INVOICE_PREFIX', 'IC-INV-');

define('PRODUCT_PURCHASE_CATEGORY', 6);
define('PRODUCT_PURCHASE_CATEGORY_FREE', 10);
define('DOMAIN_REGISTRATION_ID', 1);
define('USER_CREDIT_CARD_ENCRYPT_KEY', 'BEAGLE');

PageContext::addJsVar('MAIN_URL', BASE_URL);

define('ACTIVE_STATUS', 1);
define('DB_BASED_SESSION', false);
define('DYNAMIC_THEME_ENABLED', false);

define('PRODUCT_ID', 1);
define('CACHE_ENABLED', false);

define('IMAGE_FILE_URL', BASE_URL . 'project/files/');
define('FILE_UPLOAD_DIR', BASE_PATH . "project/files/");
define('FILE_UPLOAD_TABLE', MYSQL_TABLE_PREFIX."files");

define('CONFIG_FILE_NAME','configxml.xml');
define('SECRET_SALT','SALT_KEY');
define('PRODUCT_PACK_NAME','vistacart.zip');
define('HELP_ACTIVE_STATUS',"Active");
define('HELP_DISABLED_STATUS',"Disabled");
define('HELP_ADMIN_CAT',"Admin");
define('HELP_USER_CAT',"User");
define('CMS_ROLES_ENABLED', FALSE);
define('CMS_DEVELOPER_USERNAME','developer');
define('CMS_DEVELOPER_PASSWORD', 'developer');

define('GLOBAL_DATE_FORMAT_SEPERATOR', "/");
define('PRODUCT_INSTALLER',1);
?>