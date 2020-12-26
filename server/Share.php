<?php

use \Config\Meta; ?>

<meta name="url" content="<?= URL; ?>" />
<meta name='copyright' content='© <?= date('Y') ?> <?= Meta::$title ?>'>
<meta name='description' content='<?= Meta::$description ?>'>
<meta name='keywords' content='<?= Meta::$keywords ?>'>
<meta itemprop="name" content="<?= Meta::$title ?>">
<meta itemprop="description" content="<?= Meta::$description ?>">
<meta itemprop="image" content="<?= Meta::$image ?>">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="<?= URL ?>">
<meta name="twitter:title" content="<?= Meta::$title ?>">
<meta name="twitter:description" content="<?= Meta::$description ?>">
<meta name="twitter:image" content="<?= Meta::$image ?>">

<meta property="og:title" content="<?= Meta::$title ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?= URL ?>" />
<meta property="og:image" content="<?= Meta::$image ?>" />
<meta property="og:description" content="<?= Meta::$description ?>" />
<meta property="og:site_name" content="<?= Meta::$title ?>" />