<?php
return array(
  'utf_mode' =>
  array(
    'value' => true,
    'readonly' => true,
  ),
  'cache_flags' =>
  array(
    'value' =>
    array(
      'config_options' => 3600,
      'site_domain' => 3600,
    ),
    'readonly' => false,
  ),
  'cookies' =>
  array(
    'value' =>
    array(
      'secure' => false,
      'http_only' => true,
    ),
    'readonly' => false,
  ),
  'exception_handling' =>
  array(
    'value' =>
    array(
      'debug' => true,
      'handled_errors_types' => E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE & ~E_DEPRECATED,
      'exception_errors_types' => E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_USER_WARNING & ~E_USER_NOTICE & ~E_COMPILE_WARNING,
      'ignore_silence' => true,
      'assertion_throws_exception' => false,
      'assertion_error_type' => 256,
      'log' => array(
        'settings' => array(
          'file' => 'bitrix/admin/error.log',
          'log_size' => 1000000,
        ),
      ),
    ),
    'readonly' => true,
  ),
  'connections' =>
  array(
    'value' =>
    array(
      'default' =>
      array(
        'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
        'host' => '127.0.0.1:3310',
        'database' => 'optima_base1',
        'login' => 'optima_optima1',
        'password' => 'optima1/',
        'options' => 1,
      ),
    ),
    'readonly' => true,
  ),
  'crypto' =>
  array(
    'value' =>
    array(
      'crypto_key' => '136b33fc12277ce66a1d265fccd9875a',
    ),
    'readonly' => true,
  ),
);
