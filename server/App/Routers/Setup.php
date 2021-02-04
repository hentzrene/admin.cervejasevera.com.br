<?php
if (!INSTALLED) {
  $router->group('admin');
  $router->get("/setup", $requireVue);
}
