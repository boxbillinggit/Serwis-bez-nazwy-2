<?php


Route::set('dokumentacja', 'dokumentacja(/<action>)')->defaults(array(
			'controller' => 'dokumentacja',
			'action'     => 'index',
		));


Route::set('admin/dokumentacja', 'admin/dokumentacja(/<action>)')->defaults(array(
			'directory'  => 'admin',
			'controller' => 'dokumentacja',
			'action'     => 'index',
		));

?>