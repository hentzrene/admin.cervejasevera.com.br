<?php
$router->namespace('Core\Controller\Module');
$router->group('rest');
$router->get('/{module}', 'Route:getAll');
$router->get('/{module}/{moduleItem}', 'Route:get');
$router->post('/{module}', 'Route:add');
$router->delete('/{module}/{moduleItem}', 'Route:remove');
$router->put('/{module}/{moduleItem}', 'Route:update');
$router->put('/{module}/{moduleItem}/{prop}', 'Route:setProp');
