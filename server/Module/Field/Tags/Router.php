<?php
$router->group('admin');

$router->namespace('Module\Field\Tags');

$router->get('/rest/modules-tags', 'Controller:getAllItems');
$router->post('/rest/modules-tags', 'Controller:addItem');
$router->put('/rest/modules-tags/link-module', 'Controller:setLinkModule');
$router->put('/rest/modules-tags/unlink-module', 'Controller:setUnlinkModule');
$router->put('/rest/modules-tags/{tagId}/title', 'Controller:setItemTitle');
$router->delete('/rest/modules-tags/{tagId}', 'Controller:removeItem');
