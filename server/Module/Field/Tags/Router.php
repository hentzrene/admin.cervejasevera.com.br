<?php
$router->group('rest');

$router->namespace('Module\Field\Tags');

$router->get('/modules-tags', 'Controller:getAllItems');
$router->post('/modules-tags', 'Controller:addItem');
$router->put('/modules-tags/{tagId}/title', 'Controller:setItemTitle');
$router->delete('/modules-tags/{tagId}', 'Controller:removeItem');
