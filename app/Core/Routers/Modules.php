<?php
$router->namespace('Core\Controller\Module');
$router->group(PREFIX);
$router->get('/rest/{module}', 'Route:getAll');
$router->get('/rest/{module}/{moduleItem}', 'Route:get');
$router->post('/rest/{module}', 'Route:add');
$router->post('/rest/{module}/export', 'Route:export');
$router->post('/rest/{module}/broadcast', 'Route:broadcast');
$router->delete('/rest/{module}/{moduleItem}', 'Route:remove');
$router->put('/rest/{module}/{moduleItem}', 'Route:update');
$router->put('/rest/{module}/{moduleItem}/{prop}', 'Route:setProp');
