<?php
$router->namespace('Custom\Controller');
$router->group('rest');

$router->get('/informations', 'Content:getAllInformations');
$router->get('/slides', 'Content:getAllSlides');
$router->get('/education', 'Content:getAllEducation');
$router->get('/education/{itemId}', 'Content:getEducation');
$router->get('/quick-access', 'Content:getAllQuickAccess');
$router->get('/featured-video', 'Content:getFeaturedVideo');
$router->get('/webcanal', 'Content:getWebCanal');
$router->get('/articles', 'Content:getAllArticles');
$router->get('/articles/{itemId}', 'Content:getArticle');
$router->get('/communications', 'Content:getAllCommunications');
$router->get('/communications/{itemId}', 'Content:getCommunication');
$router->get('/galleries', 'Content:getAllGalleries');
$router->get('/galleries/{itemId}', 'Content:getGallery');
$router->get('/projects', 'Content:getAllProjects');
$router->get('/videos', 'Content:getAllVideos');
$router->get('/videos/{itemId}', 'Content:getVideo');
$router->get('/jean-piaget', 'Content:getJeanPiaget');
$router->get('/about', 'Content:getAbout');
$router->get('/pedagogical-proposal', 'Content:getPedagogicalProposal');
$router->get('/covid', 'Content:getAllCovid');
$router->get('/useful-links', 'Content:getAllUsefulLinks');

$router->post('/email/contact-us', 'Email:contactUs');
