<?php
$router->group('rest');

$router->namespace('Module\Field\File');

$router->get('/modules-files/{itemId}', 'Controller:getAllItems');
$router->post('/modules-files/file', 'Controller:addItem');
$router->put('/modules-files/{fileId}/title', 'Controller:setTitleItem');
$router->delete('/modules-files/{fileId}', 'Controller:removeItem');
