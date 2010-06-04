<?php
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

// item de menu de vestwitter
add_action('admin_menu', 'vestwitter_menu');

// avisos de problemes de configuració al dashboard de wordpress
add_action( 'plugins_page_ves-cat-twitter-config','vestwitter_plugin_status');

// avisos despres de enviar el post
add_action( 'admin_notices', 'vestwitter_messages' );

// enviament de l'adreça al crear nou post
add_action( 'new_to_publish', 'vestwitter_send_new_to_twitter' );
add_action( 'draft_to_publish', 'vestwitter_send_new_to_twitter' );
add_action( 'pending_to_publish', 'vestwitter_send_new_to_twitter' );
add_action( 'future_to_publish', 'vestwitter_send_new_to_twitter' );

// enviament de l'adreça al editar un post
//add_action( 'modify_post',  'vestwitter_send_edit_to_twitter');

// afegint el css de la seccio admin
add_action( 'admin_head', 'vestwitter_admin_css');

// proves
add_action('admin_init','sandbox');
?>
