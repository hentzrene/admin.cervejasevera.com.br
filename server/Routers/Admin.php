<?php
if (APP === 'admin') {
  $router->group('admin');
  $router->get("/", $requireVue);
  $router->get("/{module}", $requireVue);
  $router->get("/{module}/{sub}", $requireVue);
}
