<?php
$router->group('rest');

$router->namespace('Module\Field\Subcategory');

$router->get('/modules-subcategories', 'Controller:getAllItems');
$router->post('/modules-subcategories', 'Controller:addItem');
$router->put('/modules-subcategories/{subcategoryId}/title', 'Controller:setItemTitle');
$router->delete('/modules-subcategories/{subcategoryId}', 'Controller:removeItem');
