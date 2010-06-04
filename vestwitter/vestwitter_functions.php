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

/**
 * Estils per a la pàgina d'administració
 */
function vestwitter_admin_css(){
	echo '<style type="text/css">';
	echo '/*<![CDATA[*/';
	echo '#vestwitter_notices .errors { color:#F33; font-size:10px; list-style-type:circle; padding-left:14px; }';	
	echo '#vestwitter_notices .warn { background:#F00; padding:2px 6px; color:white; -moz-border-radius:4px; -webkit-border-radius:4px; }';
	echo '#vestwitter_notices a { font-weight:bold; color:#c55; text-decoration:none; border-bottom:1px solid #a77; }';
	echo '#vestwitter_notices a:hover { color:#a44; text-decoration:none; }';
	echo '#vestwitter_notices .success { color:#3f3; font-size:10px; list-style-type:circle; padding-left:14px; }';	
	echo '#vestwitter_notices .ok { background:#0f0; padding:2px 6px; color:white; -moz-border-radius:4px; -webkit-border-radius:4px; }';
	echo '/*]]>*/';
	echo '</style>';
}
/**
 * Afegir el item al submenu
 **/
function vestwitter_menu() {
	add_submenu_page( 'plugins.php', 
					  'Configuració del plugin VesTwitter', 
					  'VesTwitter', 
					  'manage_options', 
					  'ves-cat-twitter-config', 
					  'vestwitter_conf');
}
/**
 * Configuració principal del plugin
 **/
function vestwitter_conf() {
	if ( isset($_POST['submit']) ) {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
        // fer la mandanga de despres del post del formulari de configuració
		$utwitter = $_POST["usuari_twitter"];
		$ptwitter = $_POST["contrassenya_twitter"];
		$qenviar  = $_POST["quan_enviar"]; 
		$tmpl_nou_post = $_POST["tmpl_nou_post"];
		$tmpl_post_editat = $_POST["tmpl_post_editat"];
		update_option("usuari_twitter",$utwitter);
		update_option("contrassenya_twitter",$ptwitter);
		update_option("quan_enviar",$qenviar);
		update_option("tmpl_nou_post",$tmpl_nou_post);
		update_option("tmpl_post_editat",$tmpl_post_editat);
	}else{
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		add_option("tmpl_nou_post","Nou post al bloc : %s");
		add_option("tmpl_post_editat","Actualització del post al bloc : %s");
        // printar el formulari on estara tota la informacio del compte de twitter
		include("vestwitter_options.php");
	}
}

function vestwitter_plugin_status(){
	echo '<div id="vestwitter_notices" class="updated"><p><strong>Tests!</strong>';
	
	$tests = vestwitter_check_plugin_status();
	$errors = $tests["errors"];
	$success = $tests["success"];
	
	if(!empty($success)){
		echo '<p><span class="ok">Perfecte</span> S\'han superat els següents tests!</p>';
		echo '<ul class="success">';
		foreach( $success as $succeed ){
			echo sprintf('<li>%s</li>',$succeed);
		};		
		echo '</ul>';
	};
	
	if(!empty($errors)){
		echo '<p><span class="warn">Alerta!</span> S\'han trobat els següents problemes!</p>';
		echo '<ul class="errors">';
		foreach( $errors as $error ){
			echo sprintf('<li>%s</li>',$error);
		};		
		echo '</ul>';
	};
	echo '</p></div>';
}

/**
 * Funcio per a comprovar el estat del complement
 */
function vestwitter_check_plugin_status(){
	$errors = array();
	$success = array();
	if(!valid_php_version()){
		$errors[] = "La versió de php es inferior a la necessaria per a fer funcionar el complement!";	
	}else{
		$success[] = "La versio de php disponible es l'adequada per al complement!"; 
	}
	if(!valid_wp_version()){
		$errors[] = "La versió de wordpress es inferior a la necessaria per a fer funcionar el complement!";	
	}else{
		$success[] = "La versio de wordpress disponible es l'adequada per al complement!"; 
	}
	if(!json_decode_avaliable()){
		$errors[] = "Json decode no esta disponible";
	}else{
		$success[] = "Json decode esta disponible!";
	}
	if(!check_curl_avaliable()){
		$errors[] = "Es necessari tindre <a href=\"http://php.net/manual/en/book.curl.php\" target=\"_blank\">cURL</a> instal·lat, contacta amb el teu administrador per a activar-lo!";
	}else{
		$success[] = "Curl instal·lat i configurat!";
		if(!check_curl_call()){
			$errors[] = "Curl no aconsegueix fer crides externes, esta correctament configurat?";
		}else{
			$success[] = "Curl ha aconseguit fer crides externes!";
		}
		
		$userInfo = valid_twitter_user();
		
		if($userInfo["errCode"]!="0"){
			$errors[] = "El usuari de twitter proveït no es valid, el missatge de resposta es : " . $userInfo["message"];
		}else{
			$success[] = "Usuari i contrassenya de twitter correctes!";
		}
	}
	return array("success"=>$success,"errors"=>$errors);
}

/**
 * comprova si json_decode esta disponible
 */
function json_decode_avaliable(){
	return function_exists('json_decode');
}

/**
 * comprova si curl pot fer crides a l'exterior
 */
function check_curl_call(){
	$user = get_option("usuari_twitter");
	$data = twitter_call("statuses/user_timeline",array('screen_name'=>$user));
	return !empty($data);
}

/**
 * fa una crida a twitter a traves de curl
 */
function twitter_call($call,$params=array(),$http_method='get'){	
	$format = "json";
	$api_call = sprintf('http://twitter.com/%s.%s', $call, $format);
	$username = get_option("usuari_twitter");
	$password = get_option("contrassenya_twitter").'wrong!';
	
    count( $params ) > 0 ? $api_call .= '?' . http_build_query($params) : false;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_call);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, $http_method=='post'?1:0);
	
	//$headers = curl_getinfo($ch);
	
	$twitter_data = curl_exec($ch);
	curl_close($ch);
	return $twitter_data;
}

/**
 * funcio de l'API de twitter per a actualitzar el estatus
 */
function twitter_call_update_status($message){
	$data = twitter_call("statuses/update",array('status'=>$message),'post');
	return $data;
}

/** 
 * Mira si la versió de wordpress es valida
 */
function valid_wp_version(){
	return version_compare(WP_VERSION,'2.7',">=");
}

/** 
 * Assegura que la versio de php sigui la adecuada
 */
function valid_php_version(){
	return version_compare(PHP_VERSION,'5.0',">");
}

/**
 * comprova les credencials proporcionades anteriorment
 */
function valid_twitter_user(){
	$data = twitter_call("account/verify_credentials");
	if(empty($data))return array("mesage"=>'cUrl no funciona!',"errCode"=>'0');
	$jsdata = json_decode($data);
	if( in_array( "errors", array_keys( get_object_vars( $jsdata ) ) ) ){
		$msg = $jsdata->errors[0]->message;
		$errCode = $jsdata->errors[0]->message;
	}else{
		$msg = "All Ok!";
		$errCode = "0";
	}
	return array("message"=>$msg,"errCode"=>$errCode);
}

/**
 * Comprova que el modul de curl estigui instal·lat i disponible
 */
function check_curl_avaliable() {
	return function_exists('curl_init');
}

function vescat_shorten_link($link){
	$vescat_call = sprintf('http://localhost/vescat/?url=%s&format=json',urlencode($link));
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $vescat_call);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 0);
	
	$vescat_data = curl_exec($ch);
	curl_close($ch);
	
	return $vescat_data;
}

global $test;

function vestwitter_send_new_to_twitter($postID){
	$missatges = get_option('missatges');
	$link = get_permalink($postID);
	$vescat_data = vescat_shorten_link($link);
	$jsdata = json_decode($vescat_data);
	if($jsdata && $jsdata->status == 'Ok' && false ){
		$status = sprintf(get_option("tmpl_nou_post"),$jsdata->link); 
		$twitter_data = twitter_call_update_status($status);
		$js_result = json_decode($twitter_data);
		if( !in_array( "errors", array_keys( get_object_vars( $js_result ) ) ) && $js_result->text === $status ){
			$missatges[] = 'Missatge envïat a twitter amb exit!';
		}else if( in_array( "errors", array_keys( get_object_vars( $js_result ) ) ) ){
			$missatges[] = "No s'ha pogut enviar : ".$js_result->errors[0]->message; 
		}
	}else{
		$error = 'Ves.cat no ha aconseguit escurçar l\'adreça web';
		if($jsdata->status){
			$error .= ' rao : '.$jsdata->status; 
		}
		$missatges[] = $error;
	}
	update_option('missatges',$missatges);
}

function vestwitter_messages(){
	$missatges = get_option('missatges');
	if(!empty($missatges)){
		echo '<div id="vestwitter_notices" class="updated"><p><strong>Missatge de VesTwitter : </strong>';
		echo '<ul>';
		foreach($missatges as $missatge){
			echo sprintf('<li>%s</li>',$missatge);
		}
		echo '</ul>';
		echo '</p></div>';	
	}
	delete_option('missatges');
}

function vestwitter_send_edit_to_twitter($postID){
	
}

function vestwitter_show_error($ref){
	wp_die($ref);
}

function sandbox(){
	/*var_dump(json_decode(vescat_shorten_link('http://ves.cat/api.html')));
	//var_dump(query_posts("ID=1"));
	$status = 'Probando a ver que devuelve twitter al hacer un update del status a traves de cUrl!';
	$twitter_data = twitter_call_update_status($status);
	$js_result = json_decode($twitter_data);
	if( !in_array( "errors", array_keys( get_object_vars( $js_result ) ) ) && $js_result->text === $status ){
			vestwitter_show_message('Missatge envïat amb exit!');
		}else if( in_array( "errors", array_keys( get_object_vars( $js_result ) ) ) ){
			vestwitter_show_message( " No s'ha pogut enviar, raó : ".$js_result->errors[0]->message);
		}*/
		//var_dump($_POST);
}
?>