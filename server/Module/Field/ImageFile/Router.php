<?php
$router->group('admin');

$router->namespace('Module\Field\ImageFile');

$router->get('/rest/modules-images/{itemId}', 'Controller:getAllItems');
$router->get('/rest/modules-images/{itemId}/{imageId}', 'Controller:getItem');
$router->post('/rest/modules-images/image', 'Controller:addItem');
$router->delete('/rest/modules-images/{imageId}', 'Controller:removeItem');
$router->put('/rest/modules-images/order', 'Controller:updateOrder');
$router->put('/rest/modules-images/{imageId}/title', 'Controller:updateTitle');
