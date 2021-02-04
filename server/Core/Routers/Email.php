<?php
$router->namespace('Core\Controller');
$router->group('rest');
$router->post('/email/contact-us', 'Email:contactUs');
