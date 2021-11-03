<?php
$cssPath = "";
if (APP === 'admin' || APP === 'setup') {
  $cssPath = SYSTEM_ROOT . "/public/css/";
} else {
  $cssPath = SYSTEM_ROOT . "/css/";
}
$css = [];
if (is_dir($cssPath)) {
  $cssDir = array_slice(scandir($cssPath), 2);
  foreach ($cssDir as $n => $v) {
    if (APP === 'admin' || APP === 'setup') {
      $css[] = BASE . "/css/$v";
    } else {
      $css[] = BASE . "/css/$v";
    }
  }
}

define('STYLES', $css);

$jsPath = "";
if (APP === 'admin' || APP === 'setup') {
  $jsPath = SYSTEM_ROOT . "/public/js/";
} else {
  $jsPath = SYSTEM_ROOT . "/public/js/";
}

$js = [];
if (is_dir($jsPath)) {
  $jsDir = array_slice(scandir($jsPath), 2);
  foreach ($jsDir as $n => $v) {
    if (preg_match('/\.map$/', $v)) {
      continue;
    }
    if (APP === 'admin' || APP === 'setup') {
      $js[] = BASE . "/js/$v";
    } else {
      $js[] = BASE . "/js/$v";
    }
  }
}

define('SCRIPTS', $js);

define('PROXY', true);

define('MANIFEST', true);
