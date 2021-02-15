<?php
$router->namespace('Core\Controller');
$router->group('rest');
$router->get('/configuration/{key}', 'Configuration:getConfig');
$router->put('/configuration/{key}', 'Configuration:updateConfig');
