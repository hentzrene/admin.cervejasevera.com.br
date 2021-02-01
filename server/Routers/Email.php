<?php
$router->namespace('Controller');
$router->group('rest');
$router->post('/email/contact-us', 'Email:contactUs');
