<?php
$router->group(PREFIX);

$router->namespace('Module\Field\File');

$router->get('/rest/modules-files/{itemId}', 'Controller:getAllItems');
$router->post('/rest/modules-files/file', 'Controller:addItem');
$router->put('/rest/modules-files/{fileId}/title', 'Controller:setTitleItem');
$router->delete('/rest/modules-files/{fileId}', 'Controller:removeItem');
