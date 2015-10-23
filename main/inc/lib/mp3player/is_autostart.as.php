<?php
/* For licensing terms, see /license.txt */

use ChamiloSession as Session;

/**
 * This script is used by the mp3 player to see if it should start
 * automatically or not
 * @package chamilo.include
 */
require '../../global.inc.php';
$where = Session::read('whereami');
switch ($where) {
	case 'lp/build' :
	case 'document/create' :
	case 'document/edit' :
		$autostart = 'false';
	break;
	default :
		$autostart = 'true';

}
echo utf8_encode('autostart='.$autostart);
