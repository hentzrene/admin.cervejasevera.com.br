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
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="preconnect" href="https://connect.facebook.net">
  <link rel="preconnect" href="https://www.googletagmanager.com">

  <link rel="preload" href="/img/top.png" as="image">
  <link rel="preload" href="/img/footer.png" as="image">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <script async src=https://kit.fontawesome.com/3adb9befaf.js crossorigin=anonymous></script>
  <?php
  include 'Icons.php';
  if (SHARE_TAGS_DEFINEDS) include __DIR__ . '/Share.php';
  ?>


  <!-- ##### PROXY ##### -->
  <?php if (PROXY) : ?>
    <script async>
      if ('serviceWorker' in window.navigator)
        window.navigator.serviceWorker.register('<?= PROXY ?>')
        .catch((e) => {
          console.log('Erro no processamento. Tente recarregar a página ou usar o navegador Google Chorme.')
        })
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

  <!-- Facebook Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '509937639200650');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=509937639200650&ev=PageView&noscript=1" /></noscript>
  <!-- End Facebook Pixel Code -->

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "http://www.mrxweb.com.br",
      "logo": "http://www.mrxweb.com.br/img/logo.svg"
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