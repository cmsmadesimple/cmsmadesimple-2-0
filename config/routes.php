<?php

//Automatically add some component routes -- see docs for details
//SilkRoute::build_default_component_routes();

SilkRoute::register_route("/admin/:controller/:action/:id", array("component" => 'admin'));
SilkRoute::register_route("/admin/:controller/:action", array("id" => '', "component" => 'admin'));
SilkRoute::register_route("/admin/:controller", array("id" => '', 'action' => 'index', "component" => 'admin'));
SilkRoute::register_route("/admin", array("id" => '', 'action' => 'index', "component" => 'admin', 'controller' => 'admin'));

//Catch-all goes here
SilkRoute::register_route_callback("*", array("CmsRoute", "run"), array());

?>