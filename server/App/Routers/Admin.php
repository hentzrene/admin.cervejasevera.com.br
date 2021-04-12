<?php
if (APP === 'admin') {
  $router->namespace('App');
  $router->group('admin');
  $router->get("/", 'ShareTags:admin');
  $router->get("/{module}", 'ShareTags:admin');
  $router->get("/{module}/{sub}", 'ShareTags:admin');
  $router->get("/{module}/{sub}/{item}", 'ShareTags:admin');
}
