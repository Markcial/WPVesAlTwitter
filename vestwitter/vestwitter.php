<?php
/*
Plugin Name: VesTwitter
Plugin URI:
Description: Plugin per a enviar els posts nous o editats a twitter per mitjà de l'eina d'escurçament d'adreces web ves.cat
Version: 0.1a
Author: Marc Garcia a.k.a. Markcial
Author URI: http://blog.illcode4food.com
*/

/*  Copyright 2010  Marc Garcia a.k.a. Markcial  (email : Markcial@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

error_reporting(E_ALL);
ini_set('display_errors','On');

if(!defined('DS'))define('DS',DIRECTORY_SEPARATOR);
if(!defined('WP_VERSION'))define('WP_VERSION',$wp_version);
if(!defined('VESTWITTER_VERSION'))define('VESTWITTER_VERSION','0.1a');
if(!defined('QUAN_NOU_POST'))define('QUAN_NOU_POST',1);
if(!defined('QUAN_NOU_O_CREA_POST'))define('QUAN_NOU_O_CREA_POST',2);

require_once dirname(__FILE__). DS ."vestwitter_actions.php";
require_once dirname(__FILE__). DS ."vestwitter_functions.php";
?>
