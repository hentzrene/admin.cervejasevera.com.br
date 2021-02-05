<?php
$router->group('rest');

$router->namespace('Module\Field\ImageFile');

$router->get('/modules-images/{itemId}', 'Controller:getAllItems');
$router->post('/modules-images/image', 'Controller:addItem');
$router->delete('/modules-images/{imageId}', 'Controller:removeItem');
