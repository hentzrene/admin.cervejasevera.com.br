<?php
$requireVue = function () {
  define('SHARE_TAG_NAME', 'Painel de Administração - MRX Web Sites');
  define('SHARE_TAG_TITLE', 'Painel de Administração - MRX Web Sites');
  define('SHARE_TAG_DESCRIPTION', '');
  define('SHARE_TAG_KEYWORDS', '');
  define('SHARE_TAG_IMAGE', '');
  require __DIR__ . '/../Vue.php';
};

if (APP === 'admin') {
  $router->group('admin');
  $router->get("/", $requireVue);
  $router->get("/{module}", $requireVue);
  $router->get("/{module}/{sub}", $requireVue);
}
