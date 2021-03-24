<?php
$router->group('admin');

$router->namespace('Module\Field\Subcategory');

$router->get('/rest/modules-subcategories', 'Controller:getAllItems');
$router->post('/rest/modules-subcategories', 'Controller:addItem');
$router->put('/rest/modules-subcategories/{subcategoryId}/title', 'Controller:setItemTitle');
$router->delete('/rest/modules-subcategories/{subcategoryId}', 'Controller:removeItem');
