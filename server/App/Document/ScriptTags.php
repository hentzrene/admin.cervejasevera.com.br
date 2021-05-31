<?php

use Core\Model\Configuration;

$config = Configuration::getConfig('tags');
?>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<script async src=https://kit.fontawesome.com/3adb9befaf.js crossorigin=anonymous></script>

<?php
foreach ($config as $tag) echo $tag;
?>