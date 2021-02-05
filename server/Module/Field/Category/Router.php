<?php
$router->group('rest');

$router->namespace('Module\Field\Category');

$router->get('/modules-categories', 'Controller:getAllItems');
$router->post('/modules-categories', 'Controller:addItem');
$router->put('/modules-categories/{categoryId}/title', 'Controller:setItemTitle');
$router->delete('/modules-categories/{categoryId}', 'Controller:removeItem');
