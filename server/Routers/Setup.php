<?php
if (!INSTALLED) {
  $router->group('admin');
  $router->get("/setup", $requireVue);

  $router->group('rest');
  $router->post('/setup', 'Setup:exec');
}
