<?php

use Module\View\Item\Model as ItemModel;

$informations = ItemModel::get('informations');
?>
<link rel=apple-touch-icon sizes=57x57 href="<?= BASE . $informations->icon ?>?resize=1&w=57&h=57'">
<link rel=apple-touch-icon sizes=60x60 href="<?= BASE  . $informations->icon  ?>?resize=1&w=60&h=60">
<link rel=apple-touch-icon sizes=72x72 href="<?= BASE  . $informations->icon  ?>?resize=1&w=72&h=72">
<link rel=apple-touch-icon sizes=76x76 href="<?= BASE  . $informations->icon  ?>?resize=1&w=76&h=76">
<link rel=apple-touch-icon sizes=114x114 href="<?= BASE  . $informations->icon  ?>?resize=1&w=114&h=114">
<link rel=apple-touch-icon sizes=120x120 href="<?= BASE  . $informations->icon  ?>?resize=1&w=120&h=120">
<link rel=apple-touch-icon sizes=144x144 href="<?= BASE  . $informations->icon  ?>?resize=1&w=144&h=144">
<link rel=apple-touch-icon sizes=152x152 href="<?= BASE  . $informations->icon  ?>?resize=1&w=152&h=152">
<link rel=apple-touch-icon sizes=180x180 href="<?= BASE  . $informations->icon  ?>?resize=1&w=180&h=180">
<link rel=icon type=image/png sizes=192x192 href="<?= BASE  . $informations->icon  ?>?resize=1&w=192&h=192">
<link rel=icon type=image/png sizes=32x32 href="<?= BASE  . $informations->icon  ?>?resize=1&w=32&h=32">
<link rel=icon type=image/png sizes=96x96 href="<?= BASE  . $informations->icon  ?>?resize=1&w=96&h=96">
<link rel=icon type=image/png sizes=16x16 href="<?= BASE  . $informations->icon  ?>?resize=1&w=16&h=16">
<link rel=icon type=image/png sizes=295x295 href="<?= BASE  . $informations->icon  ?>?resize=1&w=295&h=295">
<meta name=msapplication-TileColor content=#ffffff>
<meta name=msapplication-TileImage content="<?= BASE . $informations->icon ?>144x144">
<meta name=theme-color content=#ffffff>