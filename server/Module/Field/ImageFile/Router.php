<?php
$router->group('admin');

$router->namespace('Module\Field\ImageFile');

$router->get('/rest/modules-images/{itemId}', 'Controller:getAllItems');
$router->post('/rest/modules-images/image', 'Controller:addItem');
$router->delete('/rest/modules-images/{imageId}', 'Controller:removeItem');
$router->put('/rest/modules-images/order', 'Controller:updateOrder');
