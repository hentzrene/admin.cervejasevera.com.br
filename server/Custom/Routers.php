<?php
$router->namespace('Custom\Controller');
$router->group('rest');

$router->get('/informations', 'Content:getInformations');
$router->get('/biography', 'Content:getBiography');
$router->get('/schedule', 'Content:getAllSchedule');
$router->get('/shows', 'Content:getAllShows');
$router->get('/shows/{itemId}', 'Content:getShow');

$router->post('/email/contact-us', 'Email:ContactUs');
$router->post('/email/work-with-us', 'Email:WorkWithUs');
