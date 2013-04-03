<!-- footer.php -->



<?php /* opened in assets/templates/header_body.php */ ?>
</div><!-- /content_container -->



<div id="footer">	

<div id="footer_inner">

	<?php 
	
	// are we using the page footer widget?
	if ( !dynamic_sidebar( 'cp-license-8' ) ) {
		
		// no - make other provision here
	
		// compat with wplicense plugin
		if ( function_exists( 'isLicensed' ) AND isLicensed() ) {
		
			// show the license from wpLicense
			cc_showLicenseHtml();
			
		} else {
			
			// show copyright
			?>
			
			<p>&copy; <?php echo date('Y'); ?> <a href="http://ncfr.org">NCFR</a>. All rights reserved.</p>
			<p>National Council on Family Relations<br />
			1201 West River Parkway, Suite 200, Minneapolis, MN 55454<br />
			888.781.9331</p>
			
			<?php 
			
		}
		
	}
	
	?>

	
	
	<?php wp_footer() ?>



</div><!-- /footer_inner -->

</div><!-- /footer -->



</div><!-- /container -->



</body>



</html>