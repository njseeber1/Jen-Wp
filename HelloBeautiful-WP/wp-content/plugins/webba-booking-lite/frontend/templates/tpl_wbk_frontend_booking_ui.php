<?php
    // check if accessed directly 
    if ( ! defined( 'ABSPATH' ) ) exit;
    date_default_timezone_set( get_option( 'wbk_timezone', 'UTC' ) );    
 	if( isset( $_GET['paypal_status'] ) ){
?>
	<div class="wbk-outer-container">
			<div class="wbk-inner-container">
				<div class="wbk-frontend-row">
					<div class="wbk-col-12-12"> 
						<div class="wbk-details-sub-title"><?php
							global $wbk_wording;
							$payment_title =  get_option( 'wbk_payment_result_title', '' );
							if( $payment_title  == '' ){
								$payment_title = sanitize_text_field( $wbk_wording['payment_title']	);
							}
							echo $payment_title;
							?></div>
					</div>
					<div class="wbk-col-12-12"> 
						<?php
							if( $_GET['paypal_status'] == 1 ){
							?>
								<div class="wbk-input-label"><?php
									global $wbk_wording;
									$payment_complete_label  =  get_option( 'wbk_payment_success_message', '' ); 
									if( $payment_complete_label == ''){
										$payment_complete_label = sanitize_text_field( $wbk_wording['payment_complete'] );
									}
									echo $payment_complete_label;
								 ?></div>
						<?php
						    }
						?>
						<?php
							if( $_GET['paypal_status'] == 5 ){
							?>
								<div class="wbk-input-label"><?php
		 							global $wbk_wording;
									$payment_canceled_label  =  get_option( 'wbk_payment_cancel_message', '' ); 
									if( $payment_canceled_label == ''){
										$payment_canceled_label = sanitize_text_field( $wbk_wording['payment_canceled'] );
									}
									echo $payment_canceled_label;

								?></div>
						<?php
						    }
						?>
						<?php
							if( $_GET['paypal_status'] == 2 ){
							?>
								<div class="wbk-input-label">Error 102</div>
						<?php
						    }
						?>
						<?php
							if( $_GET['paypal_status'] == 3 ){
							?>
								<div class="wbk-input-label">Error 103</div>
						<?php
						    }
						?>
						<?php
							if( $_GET['paypal_status'] == 4 ){
							?>
								<div class="wbk-input-label">Error 104</div>
						<?php
						    }
						?>
					</div>
				</div>
			</div>
		</div>

<?php
		date_default_timezone_set( 'UTC' );    
		return;
	}
?>
<?php
	if( get_option( 'wbk_allow_manage_by_link', 'no' ) == 'yes' ){		
 		if( isset( $_GET['admin_cancel'] ) ){
	 		$admin_cancel =  $_GET['admin_cancel'];
			$admin_cancel = str_replace('"', '', $admin_cancel );
			$admin_cancel = str_replace('<', '', $admin_cancel );
			$admin_cancel = str_replace('\'', '', $admin_cancel );
			$admin_cancel = str_replace('>', '', $admin_cancel );
			$admin_cancel = str_replace('/', '', $admin_cancel );
			$admin_cancel = str_replace('\\',  '', $admin_cancel );
			$valid = true;
	 		$appointment_id = WBK_Db_Utils::getAppointmentIdByAdminToken( $admin_cancel );
	 		if( $appointment_id === false ){
	 			$valid = false;
	 		} else {
 				$appointment = new WBK_Appointment();
				if ( !$appointment->setId( $appointment_id ) ) {
					$valid = false;
				}
				if ( !$appointment->load() ) {
					$valid = false;
				}
				WBK_Db_Utils::deleteAppointmentDataAtGGCelendar( $appointment_id );
		        $service_id = WBK_Db_Utils::getServiceIdByAppointmentId( $appointment_id );
        		$noifications = new WBK_Email_Notifications( $service_id, $appointment_id );
        		$noifications->prepareOnCancelCustomer();

				if( $appointment->delete() === false ){
					$value = false;
				} else {
					$noifications->sendOnCancelCustomer();
				}

			}
			if( $valid ){
				?>
					<div class="wbk-outer-container">
						<div class="wbk-inner-container">
							<div class="wbk-frontend-row">
								<div class="wbk-col-12-12">
									<div class="wbk-input-label">
									 	<?php echo __( 'Appointment canceled', 'wbk' ); ?>
									</div>
								</div>
							</div>
							<div class="wbk-frontend-row" id="wbk-payment">
							</div>
						</div>
					</div> 
				<?php
					date_default_timezone_set( 'UTC' ); 
					return;
			}
 		}
 	}
?>	
<?php
	if( get_option( 'wbk_allow_manage_by_link', 'no' ) == 'yes' ){		
 		if( isset( $_GET['admin_approve'] ) ){
	 		$admin_approve =  $_GET['admin_approve'];
			$admin_approve = str_replace('"', '', $admin_approve );
			$admin_approve = str_replace('<', '', $admin_approve );
			$admin_approve = str_replace('\'', '', $admin_approve );
			$admin_approve = str_replace('>', '', $admin_approve );
			$admin_approve = str_replace('/', '', $admin_approve );
			$admin_approve = str_replace('\\',  '', $admin_approve );
			$valid = true;

	 		$appointment_id = WBK_Db_Utils::getAppointmentIdByAdminToken( $admin_approve );

 	 		if( $appointment_id === false ){
	 			$valid = false;
	 		} else {							 
	 			$status = WBK_Db_Utils::getAppointmentStatus( $appointment_id );
	 			if( $status == 'pending' || $status == 'paid' ){
	 				if( $status == 'pending' ){
	 					WBK_Db_Utils::setAppointmentStatus( $appointment_id, 'approved' );
	 				}
	 				if( $status == 'paid' ){
	 					WBK_Db_Utils::setAppointmentStatus( $appointment_id, 'paid_approved' );
	 				}
	 			} else {
	 				$valid = false;
	 			}
			}
			if( $valid ){
				?>
					<div class="wbk-outer-container">
						<div class="wbk-inner-container">
							<div class="wbk-frontend-row">
								<div class="wbk-col-12-12">
									<div class="wbk-input-label">
									 	<?php 
											$service_id = WBK_Db_Utils::getServiceIdByAppointmentId( $appointment_id );
							                $noifications = new WBK_Email_Notifications( $service_id, $appointment_id );
							                $noifications->sendOnApprove();
							                if( get_option( 'wbk_email_customer_send_invoice', 'disabled' ) == 'onapproval' ){
							                    $noifications->sendSingleInvoice();
							                }   
							                $expiration_mode = get_option( 'wbk_appointments_delete_not_paid_mode', 'disabled' );
							                if( $expiration_mode == 'on_approve' ){
							                    WBK_Db_Utils::setAppointmentsExpiration( $appointment_id );
							                }
									 		echo __( 'Appointment approved', 'wbk' );
									 	?>
									</div>
								</div>
							</div>
							<div class="wbk-frontend-row" id="wbk-payment">
							</div>
						</div>
					</div> 
				<?php
					date_default_timezone_set( 'UTC' ); 
					return;
			}
 		}
 	}
?>	
<?php
 	if( isset( $_GET['order_payment'] ) ){
 		$order_payment =  $_GET['order_payment'];

		$order_payment = str_replace('"', '', $order_payment );
		$order_payment = str_replace('<', '', $order_payment );
		$order_payment = str_replace('\'', '', $order_payment );
		$order_payment = str_replace('>', '', $order_payment );
		$order_payment = str_replace('/', '', $order_payment );
		$order_payment = str_replace('\\',  '', $order_payment );

 		$appointment_id = WBK_Db_Utils::getAppointmentIdByToken( $order_payment );
 		if( $appointment_id === false ){
 		} else {
 				$service_id = WBK_Db_Utils::getServiceIdByAppointmentId( $appointment_id );
 				$valid = true;
 				$appointment = new WBK_Appointment();
				if ( !$appointment->setId( $appointment_id ) ) {
					$valid = false;
				}
				if ( !$appointment->load() ) {
					$valid = false;
				}
				$service = new WBK_Service();
				if ( !$service->setId( $service_id ) ) {
					$valid = false;
				}
				if ( !$service->load() ) {
					$valid = false;
				}
				$appointment_status = WBK_Db_Utils::getStatusByAppointmentId( $appointment_id );
				if(  $appointment_status != 'paid' && $appointment_status != 'paid_approved' ){			
					global $wbk_wording;
					$title = get_option( 'wbk_appointment_information', '' );
					if( $title == '' ){
						$title = $wbk_wording['appointment_info'];
					}					 
					$title = WBK_Db_Utils::landing_appointment_data_processing( $title, $appointment, $service ); 
					$title .= WBK_PayPal::renderPaymentMethods( $service_id, array( $appointment_id ) );
				} else {
					global $wbk_wording;
					$title = get_option( 'wbk_nothing_to_pay_message', '' );
					if( $title == ''){
						$title = $wbk_wording['nothing_to_pay'];
					}
				}
 				if( $valid == true ){
			?>
					<div class="wbk-outer-container">
						<div class="wbk-inner-container">
							<div class="wbk-frontend-row">
								<div class="wbk-col-12-12">
									<div class="wbk-input-label">
									 	<?php echo $title; ?>
									</div>
								</div>
							</div>
							<div class="wbk-frontend-row" id="wbk-payment">
							</div>
						</div>
					</div> 
					<?php
					date_default_timezone_set( 'UTC' ); 
					return;
			}
 		}
?>
<?php
	}					
?>
<?php
 	if( isset( $_GET['cancelation'] ) ){	 	
 	 		$cancelation =  $_GET['cancelation'];
			$cancelation = str_replace('"', '', $cancelation );
			$cancelation = str_replace('<', '', $cancelation );
			$cancelation = str_replace('\'', '', $cancelation );
			$cancelation = str_replace('>', '', $cancelation );
			$cancelation = str_replace('/', '', $cancelation );
			$cancelation = str_replace('\\',  '', $cancelation );
			$appointment_id = WBK_Db_Utils::getAppointmentIdByToken( $cancelation );
	 		if( $appointment_id === false ){
				$valid = false; 
				?>
				<div class="wbk-outer-container">
						<div class="wbk-inner-container">
							<div class="wbk-frontend-row">
								<div class="wbk-col-12-12">
									<div class="wbk-input-label">
									 	<?php echo __( 'appointment not found', 'wbk' ) ?>
									</div>						
								</div>
							</div>
							<div class="wbk-frontend-row" id="wbk-cancel-result">
							</div>
						</div>
					</div> 
				<?php	
				exit;		
	 		} else {
 				$service_id = WBK_Db_Utils::getServiceIdByAppointmentId( $appointment_id );
 				$valid = true;
 				$appointment = new WBK_Appointment();
				if ( !$appointment->setId( $appointment_id ) ) {
					$valid = false;
				}
				if ( !$appointment->load() ) {
					$valid = false;
				}
				// check buffer
				$buffer = get_option( 'wbk_cancellation_buffer', '' );
				if( $buffer != '' ){
					if( intval( $buffer ) > 0 ){
						$buffer_point = ( intval( $appointment->getTime() - intval( $buffer ) * 86400 ) );
						if( time() >  $buffer_point ){
							?>
								<div class="wbk-outer-container">
									<div class="wbk-inner-container">
										<div class="wbk-frontend-row">
											<div class="wbk-col-12-12">
												<div class="wbk-input-label">
 													<?php 
 														$cancel_error_message = get_option( 'wbk_booking_couldnt_be_canceled2', '' );
 														if( $cancel_error_message == ''){
 															global $wbk_wording;
 															$cancel_error_message = $wbk_wording['paid_booking_cancel2'];
 														} 														
 														echo $cancel_error_message;
 													?>
												</div>			
											</div>
										</div>
									</div>
								</div>	
							<?php
							exit;
						}
					}
				}
				// end check buffer
				$service = new WBK_Service();
				if ( !$service->setId( $service_id ) ) {
					$valid = false;
				}
				if ( !$service->load() ) {
					$valid = false;
				}				 			
				global $wbk_wording;
				$title = get_option( 'wbk_appointment_information', '' );
				if( $title == '' ){
					$title = $wbk_wording['appointment_info'];
				}
				$title = WBK_Db_Utils::landing_appointment_data_processing( $title, $appointment, $service );			
	 			$appointment_status = WBK_Db_Utils::getStatusByAppointmentId( $appointment_id );
				if( $appointment_status == 'paid' || $appointment_status == 'paid_approved' ){	
					global $wbk_wording;
					$paid_error_message = get_option( 'wbk_booking_couldnt_be_canceled',  '' );
					if( $paid_error_message == '' ){
						$paid_error_message = sanitize_text_field( $wbk_wording['paid_booking_cancel'] );
					}
					$title .= '<p>' . $paid_error_message . '</p>';
					$content = '';
				} else {
					global $wbk_wording;
					$email_cancel_label = get_option( 'wbk_booking_cancel_email_label', '' );
					if( $email_cancel_label == '' ){
						$email_cancel_label =  sanitize_text_field( $wbk_wording['cancelation_email'] );
					}
					$content = '<label class="wbk-input-label" for="wbk-customer_email">'. $email_cancel_label .'</label>';	
					$content .= '<input name="wbk-email" class="wbk-input wbk-width-100 wbk-mb-10" id="wbk-customer_email" type="text">';
					$cancel_label =  get_option( 'wbk_cancel_button_text', '' );
					if( $cancel_label == '' ){
						$cancel_label = sanitize_text_field( $wbk_wording['cancel_label'] );	
					}
					$content .= '<input class="wbk-button wbk-width-100 wbk-mt-10-mb-10" id="wbk-cancel_booked_appointment" data-appointment="'. $cancelation .'" value="' . $cancel_label . '" type="button">';
				}			
			}  
 				if( $valid == true ){
			?>
					<div class="wbk-outer-container">
						<div class="wbk-inner-container">
							<div class="wbk-frontend-row">
								<div class="wbk-col-12-12">
									<div class="wbk-input-label">
									 	<?php echo $title . $content; ?>
									</div>
								</div>
							</div>
							<div class="wbk-frontend-row" id="wbk-cancel-result">
							</div>
						</div>
					</div> 
					<?php
					date_default_timezone_set( 'UTC' ); 
					return;
				}
 	}
?>

<div class="wbk-outer-container">
	<div class="wbk-inner-container">
 	<img src=<?php echo get_site_url() . '/wp-content/plugins/webba-booking-lite/frontend/images/loading.svg' ?> style="display:block;width:0px;height:0px;">
		<div class="wbk-frontend-row" id="wbk-service-container" >
			<div class="wbk-col-12-12" >		
				 <?php 			
				 	if ( $data[0] <> 0 ){
				 		echo '<input type="hidden" id="wbk-service-id" value="' . $data[0] . '" />';
				 		echo '<input type="hidden" id="wbk_current_category" value="0">'; 	 		
				 	} else {			 
					 	if( get_option( 'wbk_allow_service_in_url', 'no' ) == 'yes'  && isset( $_GET['service'] ) && is_numeric( $_GET['service'] ) ){
					 		echo '<input type="hidden" id="wbk-service-id" value="' . $_GET['service'] . '" />';
					 		echo '<input type="hidden" id="wbk_current_category" value="0">'; 	 		
					 	} else {
						 	$label = get_option( 'wbk_service_label',  __( 'Select service', 'wbk' ) );
					 	 	if( $label == '' ){
					 	 		global $wbk_wording;
					 	 		$label =  sanitize_text_field( $wbk_wording['service_label'] );
					 	 	}
							echo  '<label class="wbk-input-label">' . $label . '</label>';
					 		echo '<select class="wbk-select wbk-input" id="wbk-service-id">'; 
					 		echo '<option value="0" selected="selected">' . __( 'select...', 'wbk' ) . '</option>';
							if( $data[1] == 0 ){
						 		$arrIds = WBK_Db_Utils::getServices();					 	
					 		} else {
						 		$arrIds = WBK_Db_Utils::getServicesInCategory( $data[1] );
					 		}
					 		foreach ( $arrIds as $id ) {
					 			$service = new WBK_Service();
					 			if ( !$service->setId( $id ) ) {  
					 				continue;
					 			}
					 			if ( !$service->load() ) {  
					 				continue;
					 			}
					 			$show_desc =  get_option( 'wbk_show_service_description', 'disabled' );
					 			if( $show_desc == 'disabled' ){
						 			echo '<option value="' . $service->getId() . '" >' . $service->getName( true ) . '</option>';
					 			} else {
						 			echo '<option data-desc="' . $service->getDescription( true ) . '" value="' . $service->getId() . '" >' . $service->getName( true ) . '</option>';
					 			}
					 			
					 		}
					 		echo '</select>';
					 		echo '<input type="hidden" id="wbk_current_category" value="' . $data[1] . '">';				 		
					 		if( $show_desc == 'enabled' ){
					 			echo '<div id="wbk_description_holder"></div>';
					 		}
				 		}
				 	}
				 ?>
			</div>
			<?php 
				if ( get_option( 'wbk_date_input', 'popup' ) == 'popup' ){
					echo WBK_Date_Time_Utils::renderBHDisabilitiesFull();
					echo WBK_Date_Time_Utils::renderServiceLimits();
				} else {
					echo WBK_Date_Time_Utils::renderBHAbilities();
				}
				// add get parameters
				$html_get  = '<script type=\'text/javascript\'>';
      			$html_get .= 'var wbk_get_converted = {';
				foreach ( $_GET as $key => $value ) {
					$value = urldecode($value);
					$key = urldecode($key);		 		
			 		$value = str_replace('"', '', $value);
			 		$key = str_replace('"', '', $key);
			 		$value = str_replace('\'', '', $value);
			 		$key = str_replace('\'', '', $key);
			 		$value = str_replace('/', '', $value);
			 		$key = str_replace('/', '', $key);
			 		$value = str_replace('\\', '', $value);
			 		$key = str_replace('\\', '', $key);		
					$value = sanitize_text_field($value);
					$key = sanitize_text_field($key);
					if ( $key != 'action' && $key != 'time' && $key != 'service' && $key != 'step' ){
					}
					$html_get .= '"'.$key.'"'. ':"' . $value . '",';			  						 
				}  					
				$html_get .= '"blank":"blank"';
  				$html_get .= '};</script>';
  				echo $html_get;
			?>

		</div>
		<div class="wbk-frontend-row" id="wbk-date-container">	
		</div>
		<?php
			if( get_option( 'wbk_mode', 'extended' ) == 'extended' ){
		?>

			<div class="wbk-frontend-row" id="wbk-time-container">
			</div>
		<?php
			}
		?>
		<div class="wbk-frontend-row" id="wbk-slots-container">				 
		</div>
		<div class="wbk-frontend-row" id="wbk-booking-form-container">		 
		</div>
		<div class="wbk-frontend-row" id="wbk-booking-done">
		</div>
		<div class="wbk-frontend-row" id="wbk-payment">
		</div>
	</div>	
</div>
<?php
	date_default_timezone_set( 'UTC' ); 
?>