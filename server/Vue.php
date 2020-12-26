<?php

use \Config\Meta;
?>
<!DOCTYPE html>
<html lang=pt-BR>

<head>
  <meta charset=utf-8>
  <meta http-equiv=X-UA-Compatible content="IE=edge">
  <meta http-equiv='content-language' content='pt-br'>
  <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
  <meta name=viewport content="width=device-width,initial-scale=1">
  <title><?= Meta::$title ?></title>
  <link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
  <script src=https://kit.fontawesome.com/3adb9befaf.js crossorigin=anonymous></script>
  <?php
  include 'Icons.php';
  include 'Share.php';
  ?>

  <!-- ##### PROXY ##### -->
  <?php if (PROXY) : ?>
    <script>
      (() => {
        document.addEventListener('DOMContentLoaded', e => {
          if ('serviceWorker' in navigator)
            navigator.serviceWorker.register('<?= PROXY ?>')
            .catch((e) => {
              alert('Erro no processamento. Tente recarregar a página ou usar o navegador Google Chorme.')
              document.documentElement.innerHTML = ''
            })
        })
      })()
    </script>
  <?php endif; ?>

  <!-- ##### VUE ##### -->
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
  <div id=app></div>
  <?php foreach (array_reverse(SCRIPTS) as $file) : ?>
    <script src=<?= $file ?>></script>
  <?php endforeach; ?>
</body>

</html>