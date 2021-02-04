<?php
$router->group('rest');

$router->namespace('Module\Field\ImageFile');

$router->get('/modules-images/{itemId}', 'Controller:getAll');
$router->post('/modules-images/image', 'Controller:add');
$router->delete('/modules-images/{imageId}', 'Controller:remove');
