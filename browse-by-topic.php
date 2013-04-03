<?php 
/*
Template Name: Browse by Topic
*/



get_header(); ?>



<!-- browse-by-topic.php -->

<div id="wrapper">



<div id="main_wrapper" class="clearfix">



<div id="page_wrapper">



<div id="content" class="clearfix">

<div class="post">



<?php the_post(); ?>

<h2 class="post_title"><?php the_title(); ?></h2>



<?php 

global $more; $more = true; the_content(''); 

?>



<div class="qmt-categories">

<?php 



// test for Query Multiple Taxonomies plugin
if ( function_exists( '_qmt_init' ) ) {
	
	// insert widget
	the_widget('Taxonomy_Drill_Down_Widget', array(
		'title' => '',
		'mode' => 'checkboxes',
		'taxonomies' => array( 'category', 'date_filter' ) // list of taxonomy names
	));

}



?>

</div>



</div><!-- /post -->

</div><!-- /content -->



</div><!-- /page_wrapper -->



</div><!-- /main_wrapper -->



</div><!-- /wrapper -->



<?php get_sidebar(); ?>



<?php get_footer(); ?>