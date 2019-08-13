<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Full Content Template
 *
Template Name:  Institutions
 *
 * @file           institutions.php
 * @package        Responsive
 * @author         Emil Uzelac
 * @copyright      2003 - 2014 CyberChimps
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/openingthearchives-general/institutions.php
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */

get_header(); ?>
<?php if ( has_nav_menu( 'sub-header-menu', 'responsive' ) ) {
    wp_nav_menu( array(
        'container'      => '',
        'menu_class'     => 'sub-header-menu',
        'theme_location' => 'sub-header-menu'
    ) );
} ?>

<div id="content-full" class="grid col-940 full-width-page">
<style>
	#institutions {
		display : flex ;
		flex-wrap : wrap ;  
		list-style-type : none ; 
		align-items : stretch ;
	}
	
	#institutions li {
		width : 14% ; 
		padding : 2% ; 
		margin-left : 6% ; 
		margin-right : 6% ; 
		margin-bottom : 20px ; 
		margin-top : 20px ; 
		border : 1px solid #999 ; 
		text-align : center ; 
		min-width : 100px ; 
	}
	
	#institutions img {
		width : 100px ; 
		margin : auto ;
		margin-bottom : 20px ; 
	}
</style>


	<?php if ( have_posts() ) : ?>

		<?php while( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'loop-header', get_post_type() ); ?>

			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php responsive_entry_top(); ?>

				<?php get_template_part( 'post-meta', get_post_type() ); ?>

				<div class="post-entry">
					<?php the_content( __( 'Read more &#8250;', 'responsive' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="pagination">' . __( 'Pages:', 'responsive' ), 'after' => '</div>' ) ); ?>
				</div>
				
				
				<?php				
				$item_display = "" ;
				$institutions_bdr_endpoint = "https://repository.library.brown.edu/api/search/?q=ir_collection_id:644&facet=true&facet.field=mods_location_physical_location_ssim&facet.mincount=1&rows=0" ;
				
				$ch = curl_init() ;
				curl_setopt($ch, CURLOPT_URL,$institutions_bdr_endpoint) ;
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE) ;
				$institutions_data = curl_exec($ch) ;
				curl_close($ch) ;
				
				$institutions_data_json = json_decode($institutions_data, TRUE) ;
				
				$thumbnail_image_map = array(
					"United+States.+National+Archives+and+Records+Administration" => "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_No9_Department_of_State.png",
					"Military+Intelligence+Unit" => "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_deptofdefense.jpg",
					"Lyndon+Baines+Johnson+Library"=> "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_LBJ_Library_and_Museum_front_view_with_fountain.png",
					"Jimmy+Carter+Presidential+Library+and+Museum"=> "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_Carter_lib2.jpeg",
					"John+F.+Kennedy+Presidential+Library+and+Museum"=> "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_JFK.jpg",
					"Ronald+Reagan+Presidential+Library+and+Museum" => "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_No7_Ronald_Regan_Foundation.jpg",
					"Richard+Nixon+Presidential+Library+and+Museum" => "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_Nixon_Library_and_Gardens.jpg",
					"Central+Intelligence+Agency" => "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_CIA.jpg",
					"George+Meany+Memorial+AFL-CIO+Archive" => "http://library.brown.edu/create/openingthearchives/wp-content/uploads/sites/19/2019/08/ota_final_thumbnail_ford-museum-building.jpg"
				);
				
				$count = 1 ;
				foreach($institutions_data_json['facet_counts']['facet_fields']['mods_location_physical_location_ssim'] as $institutions_item) {	
					if($count&1){ 
						$institutions_item_url_encoded = urlencode($institutions_item) ;
						$thumbnail = $thumbnail_image_map[$institutions_item_url_encoded] ;
						$item_display .= "
						<li class='institution_name'><img src=\"$thumbnail\" alt=\"$institutions_item\" /><br /><a href='https://repository.library.brown.edu/studio/collections/id_644/?selected_facets=mods_location_physical_location_ssim%3A$institutions_item_url_encoded'>$institutions_item</a></li>" ;
					    } 
					++$count ; 
				}
				
				?>
				
				<ul id="institutions">
					<?php echo $item_display ; ?>
				</ul>
				
				
				<!-- end of .post-entry -->

				<?php get_template_part( 'post-data', get_post_type() ); ?>

				<?php responsive_entry_bottom(); ?>
			</div><!-- end of #post-<?php the_ID(); ?> -->
			<?php responsive_entry_after(); ?>

			<?php responsive_comments_before(); ?>
			<?php comments_template( '', true ); ?>
			<?php responsive_comments_after(); ?>

		<?php
		endwhile;

		get_template_part( 'loop-nav', get_post_type() );

	else :

		get_template_part( 'loop-no-posts', get_post_type() );

	endif;
	?>

</div><!-- end of #content-full -->

<?php get_footer(); ?>
