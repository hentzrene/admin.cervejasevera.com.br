<?php

use Core\Model\Configuration;

$config = Configuration::getConfig('tags');
?>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<script async src=https://kit.fontawesome.com/3adb9befaf.js crossorigin=anonymous></script>

<?php
foreach ($config as $tag) echo $tag;
?>