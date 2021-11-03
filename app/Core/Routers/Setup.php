<?php
if (!INSTALLED) {
  $router->namespace('Core\Controller');
  $router->group(PREFIX);
  $router->post('/rest/setup', 'Setup:exec');
}
