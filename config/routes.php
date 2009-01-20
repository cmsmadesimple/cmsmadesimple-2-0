<?php

//Automatically add some component routes -- see docs for details
//SilkRoute::build_default_component_routes();

SilkRoute::register_route("/:controller/:action/:id");
SilkRoute::register_route("/:controller/:action", array("id" => ''));
SilkRoute::register_route("/:controller", array("id" => '', 'action' => 'index'));

?>