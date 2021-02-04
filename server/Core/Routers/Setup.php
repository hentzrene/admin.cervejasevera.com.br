<?php
if (!INSTALLED) {
  $router->namespace('Core\Controller');
  $router->group('rest');
  $router->post('/setup', 'Setup:exec');
}
