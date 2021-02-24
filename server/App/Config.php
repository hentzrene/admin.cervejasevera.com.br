<?php
$cssPath = "";
if (APP === 'admin' || APP === 'setup') {
  $cssPath = SYSTEM_ROOT . "/admin/css/";
} else {
  $cssPath = SYSTEM_ROOT . "/css/";
}
$css = [];
if (is_dir($cssPath)) {
  $cssDir = array_slice(scandir($cssPath), 2);
  foreach ($cssDir as $n => $v) {
    if (APP === 'admin' || APP === 'setup') {
      $css[] = "/admin/css/$v";
    } else {
      $css[] = "/css/$v";
    }
  }
}

define('STYLES', $css);

$jsPath = "";
if (APP === 'admin' || APP === 'setup') {
  $jsPath = SYSTEM_ROOT . "/admin/js/";
} else {
  $jsPath = SYSTEM_ROOT . "/js/";
}

$js = [];
if (is_dir($jsPath)) {
  $jsDir = array_slice(scandir($jsPath), 2);
  foreach ($jsDir as $n => $v) {
    if (preg_match('/\.map$/', $v)) {
      continue;
    }
    if (APP === 'admin' || APP === 'setup') {
      $js[] = "/admin/js/$v";
    } else {
      $js[] = "/js/$v";
    }
  }
}

define('SCRIPTS', $js);

define('PROXY', true);

define('MANIFEST', true);
