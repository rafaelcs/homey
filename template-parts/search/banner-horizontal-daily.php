<?php
global $post, $homey_local, $homey_prefix;
$advanced_filter = (int) homey_option('advanced_filter');
$search_width = homey_option('search_width');

$location_search = isset($_GET['location_search']) ? $_GET['location_search'] : '';
$country = isset($_GET['search_country']) ? $_GET['search_country'] : '';
$city = isset($_GET['search_city']) ? $_GET['search_city'] : '';
$area = isset($_GET['search_area']) ? $_GET['search_area'] : '';
$lat = isset($_GET['lat']) ? $_GET['lat'] : '';
$lng = isset($_GET['lng']) ? $_GET['lng'] : '';

$arrive = isset($_GET['arrive']) ? $_GET['arrive'] : '';
$depart = isset($_GET['depart']) ? $_GET['depart'] : '';
$guest = isset($_GET['guest']) ? $_GET['guest'] : '';

$class = '';
if($advanced_filter != 1) {
	$class = 'without-filters';
}

$listing_type_pre = '';
if(!is_home() && isset($_GET['listing_type'])) {
    $listing_type_pre = $_GET['listing_type'];
}

$listing_country_pre = '';
if(isset($_GET['country'])) {
    $listing_country_pre = $_GET['country'];
}

$listing_state_pre = '';
if(isset($_GET['state'])) {
    $listing_state_pre = $_GET['state'];
}

$listing_city_pre = '';
if(isset($_GET['city'])) {
    $listing_city_pre = $_GET['city'];
}

$listing_area_pre = '';
if(isset($_GET['area'])) {
	$listing_area_pre = $_GET['area'];
}

$location_field = homey_option('location_field');
if($location_field == 'geo_location') {
    $location_classes = "search-destination search-destination-js";
} elseif($location_field == 'keyword') {
    $location_classes = "search-destination search-destination-js";
} else {
    $location_classes = "search-destination with-select search-destination-js";
}

$radius_class = '';
if( homey_option('enable_radius') ) {
    $radius_class = 'search-destination-geolocation search-destination-js';
}

$layout_order = homey_option('search_visible_fields');
$layout_order = $layout_order['enabled'];

$total_fields = count($layout_order);
$total_fields = $total_fields - 1;
?>
<div class="hori-daily search-wrap search-banner search-banner-desktop hidden-xs">
	<form class="clearfix" action="<?php echo homey_get_search_result_page(); ?>" method="GET">
		
		<?php
        if ($layout_order) { 
            foreach ($layout_order as $key=>$value) {

                switch($key) { 
                    case 'location':
                        ?>
                        <div class="<?php echo esc_attr($location_classes).' '.esc_attr($radius_class); ?>">
							
							
                            <?php if($location_field == 'geo_location') { ?>
                            <label class="animated-label"><?php echo esc_attr(homey_option('srh_whr_to_go')); ?></label>    
                            <input type="text" name="location_search" autocomplete="off" id="location_search_banner" value="<?php echo esc_attr($location_search); ?>" class="form-control input-search" placeholder="<?php echo esc_attr(homey_option('srh_whr_to_go')); ?>">
							<input type="hidden" name="search_city" data-value="<?php echo esc_attr($city); ?>" value="<?php echo esc_attr($city); ?>"> 
							<input type="hidden" name="search_area" data-value="<?php echo esc_attr($area); ?>" value="<?php echo esc_attr($area); ?>"> 
							<input type="hidden" name="search_country" data-value="<?php echo esc_attr($country); ?>" value="<?php echo esc_attr($country); ?>">

                            <input type="hidden" name="lat" value="<?php echo esc_attr($lat); ?>">
                            <input type="hidden" name="lng" value="<?php echo esc_attr($lng); ?>">

							<button type="reset" class="btn clear-input-btn"><i class="homey-icon homey-icon-close" aria-hidden="true"></i></button>

                            <?php } elseif($location_field == 'keyword') { ?>

                                        <label class="animated-label"><?php echo esc_attr(homey_option('srh_whr_to_go')); ?></label>
                                        <input type="text" name="keyword" autocomplete="off" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" class="form-control input-search" placeholder="<?php echo esc_attr(homey_option('srh_whr_to_go')); ?>">

                                    <?php } elseif($location_field == 'country') { ?>

                                <select name="country" class="selectpicker" data-live-search="true" data-live-search-normalize="true">
                                <?php
                                // All Option
                                echo '<option value="">'.esc_html__(esc_attr(homey_option('srh_whr_to_go')),'homey').'</option>';

                                $listing_country = get_terms (
                                    array(
                                        "listing_country"
                                    ),
                                    array(
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => false,
                                        'parent' => 0
                                    )
                                );
                                homey_hirarchical_options('listing_country', $listing_country, $listing_country_pre );
                                ?>
                                </select>
                                
                            <?php } elseif($location_field == 'state') { ?>

                            <select name="state" class="selectpicker" data-live-search="true">
                            <?php
                            // All Option
                            echo '<option value="">'.esc_attr(homey_option('srh_whr_to_go')).'</option>';

                            $listing_state = get_terms (
                                array(
                                    "listing_state"
                                ),
                                array(
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'parent' => 0
                                )
                            );
                            homey_hirarchical_options('listing_state', $listing_state, $listing_state_pre );
                            ?>
                            </select>
                            
                            <?php } elseif($location_field == 'city') { ?>

                            <select name="city" class="selectpicker" data-live-search="true">
                            <?php
                            // All Option
                            echo '<option value="">'.esc_attr(homey_option('srh_whr_to_go')).'</option>';

                            $listing_city = get_terms (
                                array(
                                    "listing_city"
                                ),
                                array(
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'parent' => 0
                                )
                            );
                            homey_hirarchical_options('listing_city', $listing_city, $listing_city_pre );
                            ?>
                            </select>

                            <?php } elseif($location_field == 'area') { ?>

                            <select name="area" class="selectpicker" data-live-search="true">
                            <?php
                            // All Option
                            echo '<option value="">'.esc_attr(homey_option('srh_whr_to_go')).'</option>';

                            $listing_area = get_terms (
                                array(
                                    "listing_area"
                                ),
                                array(
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'parent' => 0
                                )
                            );
                            homey_hirarchical_options('listing_area', $listing_area, $listing_area_pre );
                            ?>
                            </select>

                            <?php } ?>

							
						</div>


                        <?php if( homey_option('enable_radius') ) { ?>
                        <?php $radius_show_type = homey_option('show_radius') == 0 ? "style='display: none;'" : '' ?>

                        <div <?php echo $radius_show_type; ?> class="search-type search-radius-dropdown">
                            <select name="radius" data-size="5" class="selectpicker" title="<?php esc_html_e('Radius','homey');?>">
                                <option value=""><?php esc_html_e('Radius','homey');?></option>
                                <?php
                                $radius_unit = homey_option('radius_unit', 'km');
                                $selected_radius = homey_option('default_radius', '30');
                                if( isset( $_GET['radius'] ) ) {
                                    $selected_radius = $_GET['radius'];
                                }
                                $i = 0;
                                for( $i = 1; $i <= 100; $i++ ) {
                                    echo '<option '.selected( $selected_radius, $i, false).' value="'.esc_attr($i).'">'.esc_attr($i).' '.esc_attr($radius_unit).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                        }
                    break;

                    case 'arrive_depart':
                    	?>
                    	<div class="search-date-range main-search-date-range-js">
							<div class="search-date-range-arrive">
								<label class="animated-label"><?php echo esc_attr(homey_option('srh_arrive_label')); ?></label>
								<input type="text" autocomplete="off" name="arrive" value="<?php echo esc_attr($arrive); ?>" class="form-control" placeholder="<?php echo esc_attr(homey_option('srh_arrive_label')); ?>">
							</div>
							<div class="search-date-range-depart">
								<label class="animated-label"><?php echo esc_attr(homey_option('srh_depart_label')); ?></label>
								<input type="text" autocomplete="off" name="depart" value="<?php echo esc_attr($depart); ?>" class="form-control" placeholder="<?php echo esc_attr(homey_option('srh_depart_label')); ?>">
							</div>
							<?php get_template_part ('template-parts/search/search-calendar'); ?>
						</div>
                    	<?php
                    break;

                    case 'guests':
                    	?>
                    	<div class="search-guests search-guests-js">
							<label class="animated-label"><?php echo esc_html__(esc_attr(homey_option('srh_guests_label')), 'homey'); ?></label>
							<input type="text" name="guest" autocomplete="off" value="<?php echo esc_html__(esc_attr($guest), 'remix'); ?>" class="form-control" placeholder="<?php echo esc_html__(esc_attr(homey_option('srh_guests_label')), 'homey'); ?>">
							<?php get_template_part ('template-parts/search/search-guests'); ?>
						</div>
                    	<?php
                    break;

                    case 'listing_type':
                    	?>
                    	<div class="search-type">
                        	<select data-random-search-field="<?php echo rand(100, 9999); ?>" autocomplete="off" autofocus="on" name="listing_type" class="selectpicker" data-live-search="false">
                                <?php
                                // All Option
                                echo '<option value="" selected="selected">'.esc_attr(homey_option('srh_listing_type')).'</option>';

                                $listing_type = get_terms (
                                    array(
                                        "listing_type"
                                    ),
                                    array(
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => false,
                                        'parent' => 0
                                    )
                                );
                                homey_hirarchical_options('listing_type', $listing_type, $listing_type_pre );
                                ?>
                            </select>
                        </div>
                    	<?php
                    break;
             
                }
            }
        }
        ?>

		<div class="search-button">
			<button type="submit" class="btn btn-primary"><?php echo esc_attr($homey_local['search_btn']); ?></button>
		</div>
	</form>
</div><!-- search-wrap -->

<div class="search-wrap search-banner search-banner-mobile mobile-search-js">
	<form class="clearfix">
		<div class="search-destination">
            <input value="<?php echo esc_attr($location_search); ?>" type="text" class="form-control" placeholder="<?php echo esc_attr(homey_option('srh_whr_to_go')); ?>" onfocus="blur();">
		</div>
	</form>
</div><!-- search-wrap -->

