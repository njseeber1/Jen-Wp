<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SKT Girlie
 */
?>
<div id="footer-wrapper">
    	<div class="container">
        
            <div class="cols-3 widget-column-1">  
            
             <h5><?php echo get_theme_mod('about_title',__('About Girlie','skt-girlie')); ?></h5>            	
				<p><?php echo get_theme_mod('about_description',__('Consectetur, adipisci velit, sed quiaony on numquam eius modi tempora incidunt, ut laboret dolore agnam aliquam quaeratine voluptatem. ut enim ad minima veniamting suscipit lab velit, sed quiaony on numquam eius.','skt-girlie')); ?></p>   
                
                 <div class="social-icons">
					 <?php if ( '' !== get_theme_mod( 'fb_link' ) ) { ?>
                  <a title="facebook" class="fb" target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link','http://www.facebook.com')); ?>"></a>
                  <?php } ?>
                  <?php if ( '' !== get_theme_mod( 'twitt_link' ) ) { ?>
                  <a title="twitter" class="tw" target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link','http://www.twitter.com')); ?>"></a>
                  <?php } ?>
                  <?php if ( '' !== get_theme_mod('gplus_link') ) { ?>
                  <a title="google-plus" class="gp" target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link','http://plus.google.com')); ?>"></a>
                  <?php }?>
                  <?php if ( '' !== get_theme_mod('linked_link') ) { ?>
                  <a title="linkedin" class="in" target="_blank" href="<?php echo esc_url(get_theme_mod('linked_link','http://www.linkedin.com')); ?>"></a>
                <?php } ?>
                  </div>          	
             
            </div><!--end .col-3-->
			         
             
             <!--end .col-3-->
                      
               <div class="cols-3 widget-column-3">
               
                <h5><?php echo get_theme_mod('contact_title',__('Contact Info','skt-girlie')); ?></h5> 
                  <?php if( get_theme_mod('contact_add', '100 King St, Melbourne PIC 4000, Australia') ) { ?>
                    <span class="mapicon"><?php echo get_theme_mod('contact_add', '100 King St, Melbourne PIC 4000, <br> Australia'); ?></span>
                  <?php } ?>
				  
				  <?php if( get_theme_mod('contact_no', '+123 456 7890/ +123 456 9190') ) { ?>
                    <span class="phoneno"><?php echo get_theme_mod('contact_no', '+123 456 7890/ +123 456 9190'); ?></span>
                  <?php } ?>
                  
                  <?php if( get_theme_mod('contact_mail', 'contact@company.com') ) { ?>
                    <a href="mailto:<?php echo get_theme_mod('contact_mail','contact@company.com'); ?>"><span class="emailicon"><?php echo get_theme_mod('contact_mail', 'contact@company.com'); ?></span></a>
                  <?php } ?>
                  
                  
                    
                </div><!--end .col-3-->
                
            <div class="clear"></div>
         </div><!--end .container-->
              
            
       <div class="copyright-wrapper">
        	<div class="container">
            	<div class="copyright-txt">
				<?php echo '&copy; '.date('Y').'';?>&nbsp;<?php bloginfo('name');?>&nbsp;<?php esc_attr_e('All Rights Reserved.','skt-girlie');?> </div>                
            </div>
        </div>
      
    </div><!--end .footer-->
<?php wp_footer(); ?>

</body>
</html>