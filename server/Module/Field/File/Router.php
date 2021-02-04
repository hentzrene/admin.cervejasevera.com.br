<?php
$router->group('rest');

$router->namespace('Module\Field\File');

$router->get('/modules-files/{itemId}', 'Controller:getAll');
$router->post('/modules-files/file', 'Controller:add');
$router->put('/modules-files/{fileId}/title', 'Controller:setTitle');
$router->delete('/modules-files/{fileId}', 'Controller:remove');
