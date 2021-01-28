<?php
$router->get('/{module}', 'Module:route');
$router->get('/{module}/{moduleItem}', 'Module:route');
$router->post('/{module}', 'Module:route');
$router->delete('/{module}/{moduleItem}', 'Module:route');
$router->put('/{module}/{moduleItem}', 'Module:route');
$router->put('/{module}/{moduleItem}/{prop}', 'Module:route');
