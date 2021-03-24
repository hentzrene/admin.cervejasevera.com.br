<?php
$router->namespace('Core\Controller');
$router->group('admin');
$router->get('/rest/configuration/{key}', 'Configuration:getConfig');
$router->put('/rest/configuration/{key}', 'Configuration:updateConfig');
