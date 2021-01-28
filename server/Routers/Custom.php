<?php
$router->namespace('Controller\Custom');
$router->group('rest');
$router->post('/accreditation', 'Accreditation:generatePaymentLink');
$router->get('/pagseguro-notification', 'Accreditation:pagseguroNotification');
$router->post('/pagseguro-notification', 'Accreditation:pagseguroNotification');
