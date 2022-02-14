<?php
$router->group(PREFIX);

$router->namespace('Core\Controller\Account');

$router->post('/rest/auth/login', 'Auth:login');
$router->post('/rest/auth/logout', 'Auth:logout');
$router->post('/rest/auth/check-in', 'Auth:checkIn');

$router->get('/rest/accounts', 'Account:getAll');
$router->get('/rest/accounts/{accountId}', 'Account:get');
$router->post('/rest/accounts', 'Account:add');
$router->put('/rest/accounts/{accountId}', 'Account:update');
$router->delete('/rest/accounts/{accountId}', 'Account:remove');


$router->namespace('Core\Controller\Module');

$router->get('/rest/modules', 'Module:getAll');
$router->get('/rest/modules/{moduleId}', 'Module:get');
$router->post('/rest/modules', 'Module:add');
$router->post('/rest/modules/{moduleId}/export', 'Module:export');
$router->put('/rest/modules/{moduleId}/name', 'Module:setName');
$router->put('/rest/modules/{moduleId}/icon', 'Module:setIcon');
$router->put('/rest/modules/{moduleId}/view-options', 'Module:setViewOptions');
$router->put('/rest/modules/{moduleId}/menu', 'Module:setMenu');
$router->delete('/rest/modules/{moduleId}', 'Module:remove');

$router->get('/rest/modules-basic', 'Module:getBasicOfAll');

$router->get('/rest/modules-fields-types', 'FieldType:getAll');

$router->get('/rest/modules-views', 'View:getAll');

$router->post('/rest/modules-fields', 'Field:add');
$router->put('/rest/modules-fields/{fieldId}/name', 'Field:setName');
$router->put('/rest/modules-fields/{fieldId}/type-id', 'Field:setTypeId');
$router->put('/rest/modules-fields/{fieldId}/options', 'Field:setOption');
$router->delete('/rest/modules-fields/{fieldId}', 'Field:remove');

$router->get('/rest/modules-menu', 'Menu:getAllItems');
$router->post('/rest/modules-menu', 'Menu:addItem');
$router->put('/rest/modules-menu/{menuId}/title', 'Menu:setItemTitle');
$router->delete('/rest/modules-menu/{menuId}', 'Menu:removeItem');
