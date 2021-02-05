<?php
$router->namespace('Core\Controller');
$router->group('rest');
$router->post('/email/contact-us', 'Email:contactUs');
$router->get('/email/configuration', 'Email:getConfig');
$router->put('/email/configuration', 'Email:updateConfig');
