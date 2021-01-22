<!DOCTYPE html>
<html lang=pt-BR>

<head>
  <meta charset=utf-8>
  <meta http-equiv=X-UA-Compatible content="IE=edge">
  <meta http-equiv='content-language' content='pt-br'>
  <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
  <meta name="google" content="nositelinkssearchbox" />
  <meta name=viewport content="width=device-width,initial-scale=1">
  <?php if (SHARE_TAGS_DEFINEDS) : ?>
    <title><?= SHARE_TAG_TITLE ?></title>
  <?php else : ?>
    <title></title>
  <?php endif; ?>
  <link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
  <script src=https://kit.fontawesome.com/3adb9befaf.js crossorigin=anonymous></script>
  <?php
  include 'Icons.php';
  if (SHARE_TAGS_DEFINEDS) include __DIR__ . '/Share.php';
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


  <!-- Global site tag (gtag.js) - Google Ads: 966320982 -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-966320982"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'AW-966320982');
  </script>


  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-51916696-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-51916696-1');
  </script>

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "http://www.mrxweb.com",
      "logo": "http://www.mrxweb.com/img/logo.svg"
    }
  </script>

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
  <script id="config" type="application/json">
    {
      "installed": <?= INSTALLED ? 'true' : 'false' ?>
    }
  </script>
  <div id=app></div>
  <?php foreach (array_reverse(SCRIPTS) as $file) : ?>
    <script src=<?= $file ?>></script>
  <?php endforeach; ?>
</body>

</html>