<?php
if (!INSTALLED) {
  $router->namespace('App');
  $router->group('admin');
  $router->get("/setup", 'App:setup');
}
