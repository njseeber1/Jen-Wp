<?php /* Template Name: Home Page */ 

$wpdb;
?>

<?php get_header();?>
<div class="col-md-12">
    <div class="row">
        <?php if ( have_posts() ) while ( have_posts() ) :
                    the_post(); ?>            
        <?php the_content(); ?>   
        <?php endwhile; ?>	
    </div>
</div>

<?php get_footer();?>


<script type="text/javascript">


</script>