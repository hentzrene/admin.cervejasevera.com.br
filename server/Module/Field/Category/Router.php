<?php
$router->group('rest');

$router->namespace('Module\Field\Category');

$router->get('/modules-categories', 'Controller:getAll');
$router->post('/modules-categories', 'Controller:add');
$router->put('/modules-categories/{categoryId}/title', 'Controller:setTitle');
$router->delete('/modules-categories/{categoryId}', 'Controller:remove');
