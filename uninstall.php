<?php

defined('ABSPATH') or die('<h1>One does not simply try to access plugin files directly.</h1>');
if (!defined('WP_UNINSTALL_PLUGIN')) { exit(); }
delete_option('piskotki_input');