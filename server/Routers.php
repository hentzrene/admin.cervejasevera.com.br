<?php
$router = new CoffeeCode\Router\Router(ROOT);

$router->namespace('Controller');

##### ADMIN #####
if (APP === 'admin') {
  $router->group('admin');
  $router->get("/", 'Meta:admin');
  $router->get("/{module}", 'Meta:admin');
  $router->get("/{module}/{sub}", 'Meta:admin');
}

$router->group('rest');
### MODULES ROUTES ###
$router->get('/{module}', 'Module:route');
$router->get('/{module}/{moduleItem}', 'Module:route');
$router->post('/{module}', 'Module:route');
$router->delete('/{module}/{moduleItem}', 'Module:route');
$router->put('/{module}/{moduleItem}', 'Module:route');
$router->put('/{module}/{moduleItem}/{prop}', 'Module:route');

### SYSTEM ROUTES ###
$router->post('/auth/login', 'Auth:login');
$router->post('/auth/logout', 'Auth:logout');
$router->post('/auth/check-in', 'Auth:checkIn');

$router->get('/accounts', 'Account:getAll');
$router->get('/accounts/{accountId}', 'Account:get');
$router->post('/accounts', 'Account:add');
$router->put('/accounts/{accountId}', 'Account:update');
$router->delete('/accounts/{accountId}', 'Account:remove');

$router->get('/modules', 'Module:getAll');
$router->get('/modules/{moduleId}', 'Module:get');
$router->post('/modules', 'Module:add');
$router->put('/modules/{moduleId}/name', 'Module:setName');
$router->put('/modules/{moduleId}/icon', 'Module:setIcon');
$router->put('/modules/{moduleId}/view-options', 'Module:setViewOptions');
$router->delete('/modules/{moduleId}', 'Module:remove');

$router->get('/modules-basic', 'Module:getBasicOfAll');

$router->get('/modules-fields-types', 'ModuleFieldType:getAll');

$router->get('/modules-views', 'ModuleView:getAll');

$router->post('/modules-fields', 'ModuleField:add');
$router->put('/modules-fields/{moduleFieldId}/name', 'ModuleField:setName');
$router->put('/modules-fields/{moduleFieldId}/type-id', 'ModuleField:setTypeId');
$router->delete('/modules-fields/{moduleFieldId}', 'ModuleField:remove');

$router->get('/modules-images/{itemId}', 'ModuleImage:getAll');
$router->post('/modules-images/image', 'ModuleImage:add');
$router->delete('/modules-images/{imageId}', 'ModuleImage:remove');

$router->get('/modules-files/{itemId}', 'ModuleFile:getAll');
$router->post('/modules-files/file', 'ModuleFile:add');
$router->put('/modules-files/{fileId}/title', 'ModuleFile:setTitle');
$router->delete('/modules-files/{fileId}', 'ModuleFile:remove');

$router->get('/modules-categories/{itemId}', 'ModuleCategory:getAll');
$router->post('/modules-categories', 'ModuleCategory:add');
$router->put('/modules-categories/{categoryId}/title', 'ModuleCategory:setTitle');
$router->delete('/modules-categories/{categoryId}', 'ModuleCategory:remove');

##### SETUP #####
if (!INSTALLED) {
  $router->group('admin');
  $router->get("/setup", 'Meta:setup');

  $router->group('rest');
  $router->post('/setup', 'Setup:exec');
}

##### PROXY #####
$router->group(null);
if (PROXY) {
  $router->get(PROXY, function () {
    require __DIR__ . '/Proxy.php';
  });
}

$router->dispatch();

if ($router->error()) {
  if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    http_response_code($router->error());
  }
}
