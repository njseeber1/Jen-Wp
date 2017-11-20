<?php
// webba booking email notifications class
class WBK_Email_Notifications {
	// send email to customer status
	protected $customer_book_status;
	// send email to admin status
	protected $admin_book_status;
	// customer email message
	protected $secondary_email_message;
	// admin email message
	protected $admin_email_message;
	// customer email subject
	protected $customer_email_subject;
	// admin email subject
	protected $admin_email_subject;
	// from: email
	protected $from_email;
	// from: name
	protected $from_name;
	// service id
	protected $service_id;
	// appointment
	protected $appointment_id;
    // super admin email
    protected $super_admin_email;
    // current category (0 if not definded)
    protected $current_category;
	// service_id: int
	// appointment_id: int
	public function __construct( $service_id, $appointment_id, $current_category = 0 ) {
		$this->customer_book_status = get_option( 'wbk_email_customer_book_status', '' );
		$this->admin_book_status = get_option( 'wbk_email_admin_book_status', '' );
		$this->customer_daily_status = get_option( 'wbk_email_customer_daily_status', '' );
		$this->admin_daily_status = get_option( 'wbk_email_admin_daily_status', '' );
		$this->secondary_book_status = get_option( 'wbk_email_secondary_book_status', '' );
		$this->customer_email_message = get_option( 'wbk_email_customer_book_message', '' );
		$this->admin_email_message = get_option( 'wbk_email_admin_book_message', '' );
		$this->customer_daily_message = get_option( 'wbk_email_customer_daily_message', '' );
		$this->admin_daily_message = get_option( 'wbk_email_admin_daily_message', '' );
		$this->secondary_email_message = get_option( 'wbk_email_secondary_book_message', '' );
		$this->customer_email_subject = get_option( 'wbk_email_customer_book_subject', '' );
		$this->admin_email_subject = get_option( 'wbk_email_admin_book_subject', '' );
		$this->customer_daily_subject = get_option( 'wbk_email_customer_daily_subject', '' );
		$this->admin_daily_subject = get_option( 'wbk_email_admin_daily_subject', '' );
		$this->secondary_email_subject = get_option( 'wbk_email_secondary_book_subject', '' );
		$this->super_admin_email = get_option( 'wbk_super_admin_email', '' );
		$this->from_email = get_option( 'wbk_from_email' );
		$this->from_name = get_option( 'wbk_from_name' );
		$this->service_id = $service_id;
		$this->appointment_id = $appointment_id;
		$this->customer_approve_status = get_option( 'wbk_email_customer_approve_status', '' );
		$this->customer_approve_subject = get_option( 'wbk_email_customer_approve_subject', '' );
		$this->customer_approve_message = get_option( 'wbk_email_customer_approve_message', '' );
		$this->admin_cancel_status = get_option( 'wbk_email_adimn_appointment_cancel_status', '' );
		$this->admin_cancel_subject = get_option( 'wbk_email_adimn_appointment_cancel_subject', __( 'Appointment canceled', 'wbk' ) );
		$this->admin_cancel_message = get_option( 'wbk_email_adimn_appointment_cancel_message', '<p>#customer_name canceled the appointment with #service_name on #appointment_day at #appointment_time</p>' );
		$this->customer_cancel_status = get_option( 'wbk_email_customer_appointment_cancel_status', '' );
		$this->customer_cancel_subject = get_option( 'wbk_email_customer_appointment_cancel_subject', __( 'Your appointment canceled', 'wbk' ) );
		$this->customer_cancel_message = get_option( 'wbk_email_customer_appointment_cancel_message', '<p>Your appointment with #service_name on #appointment_day at #appointment_time has been canceled</p>' );
	 	$this->customer_invoice_subject = get_option( 'wbk_email_customer_invoice_subject', __( 'Invoice', 'wbk' ) );
	 	$this->current_category = $current_category;
 	}
 	public function set_email_content_type() {
 		return 'text/html';
 	}
 	public function send( $event ) {
 		global $wbk_wording;
		$date_format = WBK_Date_Time_Utils::getDateFormat();
		$time_format = WBK_Date_Time_Utils::getTimeFormat();
		switch ( $event ) {
		    case 'book':
				$appointment = new WBK_Appointment();
				if ( !$appointment->setId( $this->appointment_id ) ) {
					return;
				}
				if ( !$appointment->load() ) {
					return;
				}
				$service = new WBK_Service();
				if ( !$service->setId( $this->service_id ) ) {
					return;
				}
				if ( !$service->load() ) {
					return;
				}
		    	 
		    	// email to admin
		    	if( $this->admin_book_status != '' ) {
			    	//	validation
			    	if ( !WBK_Validator::checkStringSize( $this->admin_email_message, 1, 50000 ) ||
			    		 !WBK_Validator::checkStringSize( $this->admin_email_subject, 1, 100 ) ||
			    		 !WBK_Validator::checkEmail( $this->from_email ) ||
			    		 !WBK_Validator::checkStringSize( $this->from_name, 1, 100 )
			    	   ) {
			    	   return;
			        }
				    $message = $this->message_placeholder_processing( $this->admin_email_message, $appointment, $service );						
					$subject = 	$this->subject_placeholder_processing( $this->admin_email_subject, $appointment, $service );
					$headers = 'From: ' . $this->from_name . ' <' . $this->from_email .'>' . "\r\n";
					
					// attachments
					if( get_option( 'wbk_allow_attachemnt', 'no' ) == 'yes' ){
						$attachment =  $appointment->getAttachment();
						if( $attachment == '' ){
							$attachment = array();
						} else {
							$attachment = json_decode( $attachment );
						}
					} else {
						$attachment = array();
					}
					add_filter( 'wp_mail_content_type', array( $this, 'set_email_content_type' ) );
			    	wp_mail( $service->getEmail(), $subject, $message, $headers );
 		 			if ( WBK_Validator::checkEmail( $this->super_admin_email )  ) {
 						wp_mail(  $this->super_admin_email, $subject, $message, $headers, $attachment );
 					}
 					remove_filter( 'wp_mail_content_type', array( $this, 'set_email_content_type' ) );
			    }
		    break;
		}
 	}
	public function sendMultipleCustomerNotification( $appointment_ids ) {	 
		return;  
 	}
	public function sendMultipleCustomerInvoice( $appointment_ids ) {
		return; 
		 
 	} 	
	public function sendToSecondary( $data ) {		  
	}
	public function sendOnApprove(){
	}
	public function prepareOnCancel(){
		 
	}
	public function sendOnCancel(){
		 
 	}
	public function prepareOnCancelCustomer(){
	
	}
	public function sendOnCancelCustomer(){
		 
 	}
	protected  function get_string_between( $string, $start, $end ){
	  	    return;
	}
	protected function subject_placeholder_processing( $message, $appointment, $service ){
		if( $this->current_category == 0 ){
			$current_category_name = '';
		} else {
			$current_category_name = WBK_Db_Utils::getCategoryNameByCategoryId( $this->current_category );
			if( $current_category_name == false  ){
				$current_category_name = '';
			}
		}
		$date_format = WBK_Date_Time_Utils::getDateFormat();
		$time_format = WBK_Date_Time_Utils::getTimeFormat(); 
	 	$message = str_replace( '#service_name', $service->getName(), $message );
		$message = str_replace( '#appointment_day', date_i18n( $date_format, $appointment->getDay() ), $message );
		$message = str_replace( '#appointment_time', date_i18n( $time_format, $appointment->getTime() ), $message );
		$message = str_replace( '#current_category_name', $current_category_name, $message );
		return $message;					 
	}
	protected function message_placeholder_processing( $message, $appointment, $service, $total_amount = null  ){
		return WBK_Db_Utils::message_placeholder_processing( $message, $appointment, $service, $total_amount = null, $this->current_category );
		
	}
	public function sendSingleInvoice(){
 	}
}
?>