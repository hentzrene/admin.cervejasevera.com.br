<?php

use \Model\Meta; ?>

<meta name="url" content="<?= URL; ?>" />
<meta name='copyright' content='© <?= date('Y') ?> <?= Meta::getTitle() ?>'>
<meta name='description' content='<?= Meta::getDescription() ?>'>
<meta name='keywords' content='<?= Meta::getKeywords() ?>'>
<meta itemprop="name" content="<?= Meta::getTitle() ?>">
<meta itemprop="description" content="<?= Meta::getDescription() ?>">
<meta itemprop="image" content="<?= Meta::getImage() ?>">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="<?= URL ?>">
<meta name="twitter:title" content="<?= Meta::getTitle() ?>">
<meta name="twitter:description" content="<?= Meta::getDescription() ?>">
<meta name="twitter:image" content="<?= Meta::getImage() ?>">

<meta property="og:title" content="<?= Meta::getTitle() ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?= URL ?>" />
<meta property="og:image" content="<?= Meta::getImage() ?>" />
<meta property="og:description" content="<?= Meta::getDescription() ?>" />
<meta property="og:site_name" content="<?= Meta::getTitle() ?>" />