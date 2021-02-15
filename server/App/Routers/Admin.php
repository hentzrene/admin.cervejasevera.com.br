<?php
if (APP === 'admin') {
  $router->namespace('App');
  $router->group('admin');
  $router->get("/", 'App:admin');
  $router->get("/{module}", 'App:admin');
  $router->get("/{module}/{sub}", 'App:admin');
}
