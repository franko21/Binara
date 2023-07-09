<?php
/*
Plugin Name: modeloform
Plugin URI: https://modeloweb.net/dev
Description: Formulario de contacto por email. Simplemente agregue [modeloform] como shortcode en el lugar que prefiere que aparezca el formulario.
Version: 1.0
Author: Alvaro Artagaveytia
Author URI: https://modelohost.net
*/


// Hacemos que WP lo "vea"

require_once('modeloform.php');

// Vamos con el form ahora

function modelo_form() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo '<br/>';
	echo 'Por favor complete todos los campos';
	echo '<br/>';
	echo '<br/>';
	echo 'Su nombre<br/>';
	echo '<input type="text" name="mod-nombre" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["mod-nombre"] ) ? esc_attr( $_POST["mod-nombre"] ) : '' ) . '" size="50" />';
	echo '</p>';
	echo '<p>';
	echo 'Su correo<br/>';
	echo '<input type="email" name="mod-email" value="' . ( isset( $_POST["mod-email"] ) ? esc_attr( $_POST["mod-email"] ) : '' ) . '" size="50" />';
	echo '</p>';
	echo '<p>';
	echo 'Asunto<br/>';
	echo '<input type="text" name="mod-asunto" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["mod-asunto"] ) ? esc_attr( $_POST["mod-asunto"] ) : '' ) . '" size="50" />';
	echo '</p>';
	echo '<p>';
	echo 'Mensaje<br/>';
	echo '<textarea rows="5" cols="50" name="mod-mensaje">' . ( isset( $_POST["mod-mensaje"] ) ? esc_attr( $_POST["mod-mensaje"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><input type="submit" name="mod-enviar" value="Enviar"></p>';
	echo '</form>';
}

function modelo_mail() {

	// Si hay click sobre el bot¨®n ... 
	if ( isset( $_POST['mod-enviar'] ) ) {

		// Pasamos un cepillo por las dudas
		
		$nombre    = sanitize_text_field( $_POST["mod-nombre"] );
		$email   = sanitize_email( $_POST["mod-email"] );
		$asunto = sanitize_text_field( $_POST["mod-asunto"] );
		$mensaje = sanitize_textarea_field( $_POST["mod-mensaje"] );

		// Se lo apuntamos al correo del admin
		
		$to = get_option( 'admin_email' );

		$headers = "From: $nombre <$email>" . "\r\n";

		// Mensaje ok 
		if ( wp_mail( $to, $asunto, $mensaje, $headers ) ) {
			echo '<div>';
			echo '<h4>Gracias por su contacto, le responderemos a la brevedad posible.</h4>';
			echo '</div>';
		} else {
			echo 'Un error inesperado ha sucedido, reintente.';
		}
	}
}

function fx_modeloform() {
	// hacemos arrancar el objeto ...
	
	ob_start();
	modelo_mail();
	modelo_form();

	return ob_get_clean();
}

// Engranamos el shortcode

add_shortcode( 'modeloform', 'fx_modeloform' );

?>