<?php
$router->group('rest');

$router->namespace('Core\Controller\Account');

$router->post('/auth/login', 'Auth:login');
$router->post('/auth/logout', 'Auth:logout');
$router->post('/auth/check-in', 'Auth:checkIn');

$router->get('/accounts', 'Account:getAll');
$router->get('/accounts/{accountId}', 'Account:get');
$router->post('/accounts', 'Account:add');
$router->put('/accounts/{accountId}', 'Account:update');
$router->delete('/accounts/{accountId}', 'Account:remove');


$router->namespace('Core\Controller\Module');

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
$router->put('/modules-fields/{fieldId}/name', 'Field:setName');
$router->put('/modules-fields/{fieldId}/type-id', 'Field:setTypeId');
$router->put('/modules-fields/{fieldId}/options', 'Field:setOption');
$router->delete('/modules-fields/{fieldId}', 'Field:remove');
