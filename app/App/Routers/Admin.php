<?php
$router->namespace('App');
$router->group(PREFIX);
$router->get("/", 'ShareTags:admin');
$router->get("/{module}", 'ShareTags:admin');
$router->get("/{module}/{sub}", 'ShareTags:admin');
$router->get("/{module}/{sub}/{item}", 'ShareTags:admin');
