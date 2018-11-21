<?php
/**
 * Credits
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
add_action( "wp_ajax_realtime_notifications", "realtime_notifications" );
function realtime_notifications() {
	if ( ! wp_verify_nonce( $_REQUEST['nonce'], "realtime_notifications" ) ) {
		exit( "You think you are smart?" );
	}

	$title = $_REQUEST['title'];

	global $wpdb;

	date_default_timezone_set( 'Asia/Beirut' );

	$date      = new DateTime( null, new DateTimeZone( 'Asia/Beirut' ) );
	$date_time = $date->getTimestamp();

	$formated_date_time = date( 'm/d/Y h:i:s a', $date_time );

	$formated_time = date( 'g:i a', $date_time );

	$current_user = wp_get_current_user();
	$user_id      = $current_user->ID;
	$user         = get_userdata( $user_id );

	if ( $title ) {
		$insert_not = $wpdb->insert( $wpdb->prefix . 'realtime_notifications', array(
			'title'     => $title,
			'by_uid'    => $user_id,
			'date_time' => $date_time,
		) );
		$last_id    = $wpdb->insert_id;
		if ( $last_id == "" ) {
			$last_id = 0;
		}

		if ( $insert_not ) {

			$title = stripslashes( $title );

			$result['type']         = "success";
			$result['id']           = $last_id;
			$result['title']        = $title;
			$result['by_uid']       = $user->user_login;
			$result['date_time']    = $formated_date_time;
			$result['time']         = $formated_time;
			$result['html_success'] = "Message sent successfully";

		} else {
			$result['type']       = "error";
			$result['html_error'] = "Error sending message";

		}
	} else {
		$result['type']             = "error_field";
		$result['html_error_field'] = "Please add message title";
	}

	if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
		$result = json_encode( $result );
		echo $result;
	} else {
		header( "Location: " . $_SERVER["HTTP_REFERER"] );
	}
	die();
}