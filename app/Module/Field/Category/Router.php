<?php
$router->group(PREFIX);

$router->namespace('Module\Field\Category');

$router->get('/rest/modules-categories', 'Controller:getAllItems');
$router->post('/rest/modules-categories', 'Controller:addItem');
$router->put('/rest/modules-categories/link-module', 'Controller:setLinkModule');
$router->put('/rest/modules-categories/unlink-module', 'Controller:setUnlinkModule');
$router->put('/rest/modules-categories/{categoryId}/title', 'Controller:setItemTitle');
$router->delete('/rest/modules-categories/{categoryId}', 'Controller:removeItem');
