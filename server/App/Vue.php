<?php

use Core\Model\Configuration;

$config = Configuration::getConfig('tags');
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

  <?= $config->head_after_begin  ?>

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

  <?= $config->head_before_end  ?>
</head>

<body>
  <?= $config->body_after_begin  ?>

  <script id="config" type="application/json">
    {
      "installed": <?= INSTALLED ? 'true' : 'false' ?>
    }
  </script>
  <div id=app></div>
  <?php foreach (array_reverse(SCRIPTS) as $file) : ?>
    <script src=<?= $file ?>></script>
  <?php endforeach; ?>

  <?= $config->body_before_end  ?>
</body>

</html>