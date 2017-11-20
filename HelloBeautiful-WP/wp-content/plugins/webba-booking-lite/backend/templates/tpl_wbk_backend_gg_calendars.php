<!-- Webba Booking backend Google Calendar template --> 
<?php
    // check if accessed directly
    if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
    <h2 class="wbk_panel_title"><?php  echo __( 'Google calendars', 'wbk' ); ?>
    <a style="text-decoration:none;" href="http://webba-booking.com/documentation/" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
    </h2>
    <div class="slf_row slf_overflow_visible slf_pad_10">
        <div class="slf_col_12_12_12">           
            <?php       
                global $current_user;                             
                if( current_user_can('manage_options') ){
                    $table = new WBK_GG_Calendar_Table();
                    $html = $table->render();              
                } 
      	        echo $html;
            ?>
        </div>
        <div class="slf-clear"></div>
    </div>                                        
</div>

 