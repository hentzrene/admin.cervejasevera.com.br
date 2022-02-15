<?php

use Core\Model\Configuration;

$config = INSTALLED ? Configuration::getConfig('tags') : (object) [];
?>
<!DOCTYPE html>
<html lang=pt-BR>

<head>
  <meta charset=utf-8>
  <meta http-equiv=X-UA-Compatible content="IE=edge">
  <meta http-equiv='content-language' content='pt-br'>
  <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
  <meta name=viewport content="width=device-width,initial-scale=1">

  <?php if (SHARE_TAGS_DEFINEDS) : ?>
    <title><?= SHARE_TAG_TITLE ?></title>
  <?php else : ?>
    <title></title>
  <?php endif; ?>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
  <script src=https://kit.fontawesome.com/3adb9befaf.js crossorigin=anonymous></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <?php if (MANIFEST) : ?>
    <link rel=manifest href="<?= BASE ?>/manifest.json">
  <?php endif; ?>

  <?php
  if (SHARE_TAGS_DEFINEDS) include __DIR__ . '/Document/Share.php';
  if (INSTALLED) {
    include __DIR__ . '/Document/Icons.php';
  }
  ?>

  <?php if (PROXY) : ?>
    <script async>
      if ('serviceWorker' in window.navigator)
        window.navigator.serviceWorker.register('/proxy.js')
        .catch((e) => {
          console.log('Erro no processamento. Tente recarregar a página ou usar o navegador Google Chorme.')
        })
    </script>
  <?php endif; ?>

  <?php foreach (STYLES as $file) : ?>
    <link href=<?= $file ?> rel=preload as=style>
  <?php endforeach; ?>
  <?php foreach (SCRIPTS as $file) : ?>
    <link href=<?= $file ?> rel=preload as=script>
  <?php endforeach; ?>

  <?php foreach (array_reverse(STYLES) as $file) : ?>
    <link href=<?= $file ?> rel=stylesheet>
  <?php endforeach; ?>
</head>

<body>
  <script id="config" type="application/json">
    {
      "installed": <?= INSTALLED ? 'true' : 'false' ?>,
      "prefixPath": "<?= PREFIX ?>"
    }
  </script>
  <div id=app></div>
  <?php foreach (array_reverse(SCRIPTS) as $file) : ?>
    <script src=<?= $file ?>></script>
  <?php endforeach; ?>
</body>

</html>
