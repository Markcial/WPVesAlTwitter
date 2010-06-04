<div class="wrap">
<h2>Ves.cat Twitter</h2>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(document).ready(function(){
	if( jQuery("#quan_enviar").val() != 2 )jQuery("#tmpl_post_editat").attr("disabled","disabled");
	jQuery("#quan_enviar").bind("change",function(){
		if( jQuery(this).val() == 2 ){
			jQuery("#tmpl_post_editat").removeAttr("disabled");
		}else{
			jQuery("#tmpl_post_editat").attr("disabled","disabled");
		} 
	})
})
/*]]>*/
</script>
<form action="options.php" method="post">
	<?php wp_nonce_field('update-options'); ?>
	<input type="hidden" name="submit" value="true" />
	<h3>Panell de configuració de VesTwitter</h3>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">
					<label for="usuari_twitter" class="regular-text">Usuari de twitter :</label>
				</th>
				<td>
					<input type="text" class="regular-text" id="usuari_twitter" name="usuari_twitter" value="<?php echo get_option("usuari_twitter"); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="contrassenya_twitter" class="regular-text">Contrassenya de twitter :</label>
				</th>
				<td>
					<input type="password" class="regular-text" id="contrassenya_twitter" name="contrassenya_twitter" value="<?php echo get_option("contrassenya_twitter"); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="quan_enviar">Enviar a twitter al :</label>
				</th>
				<?php $index = get_option("quan_enviar"); ?>
				<td>
					<select id="quan_enviar" name="quan_enviar">
						<option value="1"<?php echo $index==1?' selected="selected" ':false; ?>>Crear nou post</option>
						<option value="2"<?php echo $index==2?' selected="selected" ':false; ?>>Crear i editar post</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="tmpl_nou_post" class="regular-text">Texte al enviar nou post :</label>
				</th>
				<td>
					<textarea class="regular-text" id="tmpl_nou_post" name="tmpl_nou_post"><?php echo get_option("tmpl_nou_post"); ?></textarea>
					<span class="description">On <i>%s</i> serà reemplaçat per la adreça escurçada</span> 
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="tmpl_post_editat" class="regular-text">Texte al editar el post :</label>
				</th>
				<td>
					<textarea class="regular-text" id="tmpl_post_editat" name="tmpl_post_editat"><?php echo get_option("tmpl_post_editat"); ?></textarea>
					<span class="description">On <i>%s</i> serà reemplaçat per la adreça escurçada</span>
				</td>
			</tr>
		</tbody>
	</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="usuari_twitter,contrassenya_twitter,quan_enviar,tmpl_nou_post,tmpl_post_editat" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>