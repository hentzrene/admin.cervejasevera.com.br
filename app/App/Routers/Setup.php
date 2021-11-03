<?php
if (!INSTALLED) {
  $router->namespace('App');
  $router->group(PREFIX);
  $router->get("/setup", 'ShareTags:setup');
}
