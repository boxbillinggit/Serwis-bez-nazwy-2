<?php

defined('SYSPATH') or die('No direct script access.');

return array(
    'native' => array(
        'name' => 'session',
        'encrypted' => TRUE,
        'domain' => '.ospwidow.jcom.pl'
    ),
    'database' => array(
        'name' => 'session-webcms',
        'encrypted' => TRUE,
        'lifetime' => 14200,
        'domain' => '.ospwidow.jcom.pl',
        'group' => 'default',
        'table' => 'sessions',
        'columns' => array(
            'session_id' => 'session_id',
            'last_active' => 'last_active',
            'contents' => 'contents'
        ),
        'gc' => 0,
    ),
);