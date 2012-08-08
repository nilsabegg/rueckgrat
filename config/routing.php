<?php

$routing = array(
	'/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3'
);

$default['controller'] = 'Home';
$default['action'] = 'index';
$_SERVER['SERVER_NAME'] = 'dev.ffuf.de/finanzchecker2';