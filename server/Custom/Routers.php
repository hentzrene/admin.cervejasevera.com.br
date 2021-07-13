<?php
$router->namespace('Custom\Controller');
$router->group('rest');

$router->get('/informations', 'Content:getInformations');

$router->post('/email/contact-us', 'Email:ContactUs');
$router->post('/email/work-with-us', 'Email:WorkWithUs');
