<?php
$router->namespace('Custom\Controller');
$router->group('rest');
$router->post('/email/contact-us', 'Email:contactUs');
