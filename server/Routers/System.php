<?php
$router->group('rest');

$router->namespace('Controller\Account');

$router->post('/auth/login', 'Auth:login');
$router->post('/auth/logout', 'Auth:logout');
$router->post('/auth/check-in', 'Auth:checkIn');

$router->get('/accounts', 'Account:getAll');
$router->get('/accounts/{accountId}', 'Account:get');
$router->post('/accounts', 'Account:add');
$router->put('/accounts/{accountId}', 'Account:update');
$router->delete('/accounts/{accountId}', 'Account:remove');

$router->namespace('Controller\Module');

$router->get('/modules', 'Module:getAll');
$router->get('/modules/{moduleId}', 'Module:get');
$router->post('/modules', 'Module:add');
$router->put('/modules/{moduleId}/name', 'Module:setName');
$router->put('/modules/{moduleId}/icon', 'Module:setIcon');
$router->put('/modules/{moduleId}/view-options', 'Module:setViewOptions');
$router->delete('/modules/{moduleId}', 'Module:remove');

$router->get('/modules-basic', 'Module:getBasicOfAll');

$router->get('/modules-fields-types', 'FieldType:getAll');

$router->get('/modules-views', 'View:getAll');

$router->post('/modules-fields', 'Field:add');
$router->put('/modules-fields/{FieldId}/name', 'Field:setName');
$router->put('/modules-fields/{FieldId}/type-id', 'Field:setTypeId');
$router->delete('/modules-fields/{FieldId}', 'Field:remove');

$router->get('/modules-images/{itemId}', 'Image:getAll');
$router->post('/modules-images/image', 'Image:add');
$router->delete('/modules-images/{imageId}', 'Image:remove');

$router->get('/modules-files/{itemId}', 'File:getAll');
$router->post('/modules-files/file', 'File:add');
$router->put('/modules-files/{fileId}/title', 'File:setTitle');
$router->delete('/modules-files/{fileId}', 'File:remove');

$router->get('/modules-categories', 'Category:getAll');
$router->post('/modules-categories', 'Category:add');
$router->put('/modules-categories/{categoryId}/title', 'Category:setTitle');
$router->delete('/modules-categories/{categoryId}', 'Category:remove');
