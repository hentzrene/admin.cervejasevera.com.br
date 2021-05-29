<?php
$router->namespace('Custom\Controller');
$router->group('rest');

$router->get('/informations', 'Content:getAllInformations');
$router->get('/articles/{itemId}', 'Content:getArticle');

$router->post('/email/contact-us', 'Email:contactUs');
