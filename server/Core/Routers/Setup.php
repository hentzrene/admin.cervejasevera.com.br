<?php
if (!INSTALLED) {
  $router->namespace('Core\Controller');
  $router->group('admin');
  $router->post('/rest/setup', 'Setup:exec');
}
