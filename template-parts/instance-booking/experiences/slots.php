<?php
global $post, $current_user, $homey_prefix, $homey_local;
$current_user = wp_get_current_user();
$userID       = $current_user->ID;
$phone      = $current_user->phone;
$reg_form_phone_number = get_user_meta($userID,'reg_form_phone_number', true);
if(!empty($reg_form_phone_number)){
    $phone = $reg_form_phone_number;
}
$extra_options_data = get_user_meta($userID, 'extra_prices', true);

$owner_name = $owner_pic_escaped = $owner_languages = '';

$experience_id = isset($_GET['experience_id']) ? $_GET['experience_id'] : '';
$guests = isset($_GET['guest']) ? $_GET['guest'] : '';
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$extra_options = isset($_GET['extra_options']) ? $_GET['extra_options'] : '';
$guest_message = isset($_GET['guest_message']) ? $_GET['guest_message'] : '';

if(!empty($experience_id)) {
    $experience_owner_id  =  get_post_field( 'post_author', $experience_id );
    
    $experience_owner = homey_get_author_by_id($w = '70', $h = '70', $classes = 'img-responsive img-circle', $experience_owner_id);

    $owner_pic_escaped = $experience_owner['photo'];
    $owner_name = $experience_owner['name'];
    $owner_languages = $experience_owner['languages'];
}

$check_availability = check_exp_booking_availability($check_in, $experience_id, $guests);
$is_available = $check_availability['success'];
$check_message = $check_availability['message'];


$smoke     = get_post_meta($experience_id, $homey_prefix.'smoke', true);
$pets   = get_post_meta($experience_id, $homey_prefix.'pets', true);
$party       = get_post_meta($experience_id, $homey_prefix.'party', true);
$children      = get_post_meta($experience_id, $homey_prefix.'children', true);
$additional_rules = get_post_meta($experience_id, $homey_prefix.'additional_rules', true);
$cancellation_policy = get_post_meta($experience_id, $homey_prefix.'cancellation_policy', true);

if(!empty($cancellation_policy)){
    $cancellation_policy   = get_the_content( '', '',  $cancellation_policy ); // Where $cancellation_policy is the ID
}else{
    $cancellation_policy = '';
}

if($smoke != 1) {
    $smoke_allow = 'homey-icon homey-icon-close';
    $smoke_text = esc_html__(homey_option('experience_sn_text_no'), 'homey');
} else {
    $smoke_allow = 'homey-icon homey-icon-check-circle-1';
    $smoke_text = esc_html__(homey_option('experience_sn_text_yes'), 'homey');
}

if($pets != 1) {
    $pets_allow = 'homey-icon homey-icon-close';
    $pets_text = esc_html__(homey_option('experience_sn_text_no'), 'homey');
} else {
    $pets_allow = 'homey-icon homey-icon-check-circle-1';
    $pets_text = esc_html__(homey_option('experience_sn_text_yes'), 'homey');
}

if($party != 1) {
    $party_allow = 'homey-icon homey-icon-close';
    $party_text = esc_html__(homey_option('experience_sn_text_no'), 'homey');
} else {
    $party_allow = 'homey-icon homey-icon-check-circle-1';
    $party_text = esc_html__(homey_option('experience_sn_text_yes'), 'homey');
}

if($children != 1) {
    $children_allow = 'homey-icon homey-icon-close';
    $children_text = esc_html__(homey_option('experience_sn_text_no'), 'homey');
} else {
    $children_allow = 'homey-icon homey-icon-check-circle-1';
    $children_text = esc_html__(homey_option('experience_sn_text_yes'), 'homey');
}

$terms_conditions = homey_option('exp_payment_terms_condition');
$privacy_policy = homey_option('exp_payment_privacy_policy');
$ins_learnmore = homey_option('ins_exp_learnmore');

$enable_paypal = homey_option('enable_paypal');
$enable_stripe = homey_option('enable_stripe');
$stripe_processor_link = homey_get_template_link('template/template-stripe-charge-exp.php');
?>

<section class="main-content-area booking-page">
    <?php
    if(!$is_available || empty($experience_id)) { ?>

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <?php
                    if(!$is_available) {
                        $ins_warning = $check_message;

                    } elseif(empty($experience_id)) {
                        $ins_warning = $homey_local['ins_no_experience'];
                    }
                    ?>

                    <div class="alert alert-danger" role="alert">
                        <i class="homey-icon homey-icon-alert-circle-interface-essential"></i> <?php echo esc_html($ins_warning); ?>
                    </div>
                    <a href="<?php echo get_permalink($experience_id); ?>" class="btn btn-primary"><?php echo esc_attr($homey_local['continue_btn']); ?></a>
                </div><!-- col-xs-12 col-sm-12 col-md-12 col-lg-12 -->
            </div><!-- .row -->
        </div><!-- .container -->

    <?php    
    } else {
    ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="page-title text-center">
                        <div class="block-top-title">
                            <h1 class="experience-title"><?php echo homey_option('ins_exp_page_title'); ?></h1>
                            <p class="experience-title"><?php echo homey_option('ins_exp_page_subtitle'); ?>
                            
                            <?php if(!empty($ins_learnmore)) { ?>
                            <a target="_blank" href="<?php echo esc_url($ins_learnmore); ?>">
                                <?php echo esc_attr($homey_local['learnmore']); ?>        
                            </a>
                            <?php } ?>
                            </p>
                        </div><!-- block-top-title -->
                    </div><!-- page-title -->
                    <div class="alert alert-info" role="alert">
                        <i class="homey-icon homey-icon-time-clock-circle"></i> <?php echo esc_attr($homey_local['ins_notic']); ?>
                    </div>
                </div><!-- col-xs-12 col-sm-12 col-md-12 col-lg-12 -->
            </div><!-- .row -->
        </div><!-- .container -->

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-push-7 col-lg-push-7">
                    
                    <?php get_template_part('single-experience/booking/sidebar-instance-booking'); ?>
                    <input type="hidden" id="is-experience-step-1" name="is-experience-step-1" value="1" />
                </div>

                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-md-pull-5 col-lg-pull-5">
                    <div class="block homey-booking-block-title-1">
                        <div class="block-head table-block">
                            <h2 class="title"><span class="circle-icon">1</span> <?php echo esc_attr($homey_local['start_booking']); ?>
                                <i class="homey-icon homey-icon-check-circle-1 text-success hidden" aria-hidden="true"></i>
                                <a class="edit-booking-form hidden" href=""><?php echo esc_attr($homey_local['edit_btn']); ?></a>
                            </h2>
                        </div>
                        
                        <div class="block-body homey-booking-block-body-1">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="first-name"><?php echo esc_attr($homey_local['fname_label']); ?></label>
                                        <input type="text" id="first-name" class="form-control" value="<?php echo esc_attr($current_user->first_name); ?>" placeholder="<?php echo esc_attr($homey_local['fname_plac']); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="last-name"><?php echo esc_attr($homey_local['lname_label']); ?></label>
                                        <input type="text" id="last-name" class="form-control" value="<?php echo esc_attr($current_user->last_name); ?>" placeholder="<?php echo esc_attr($homey_local['lname_plac']); ?>">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="phone"><?php echo esc_attr($homey_local['phone_label']); ?></label>
                                        <input type="text" id="phone" class="form-control" value="<?php echo esc_attr($phone); ?>" placeholder="<?php echo esc_attr($homey_local['phone_plac']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="block-body homey-booking-block-body-1" style="padding-top: 0">
                            <input type="hidden" id="renter_message" name="renter_message" value="<?php if( isset($_GET['guest_message']) ) { echo $_GET['guest_message']; }?>">
                            <div class="continue-block-button">
                                <button type="button" class="btn homey-booking-step-1 btn-booking btn-full-width"><?php echo esc_attr($homey_local['continue_btn']); ?></button>
                                    <label class="control control--checkbox mb-0">
                                        <input type="checkbox" name="click_to_agreement">
                                        <span class="contro-text"> <?php echo sprintf( wp_kses(__( 'By clicking Continue you agree to our <a href="%s">Terms & Conditions</a> and <a href="%s">Privacy Policy</a>', 'homey' ), homey_allowed_html()), get_permalink($terms_conditions), get_permalink($privacy_policy) );
                                            ?></span>
                                        <span class="control__indicator"></span>
                                    </label>
                            </div>
                        </div>
                    </div>

                    <div class="block homey-booking-block-title-2 inactive mb-0">
                        <div class="block-head table-block">
                            <h2 class="title"><span class="circle-icon">2</span> <?php echo esc_attr($homey_local['rules_policies']); ?>
                                <i class="homey-icon homey-icon-check-circle-1 text-success hidden" aria-hidden="true"></i>
                                <a class="edit-booking-form hidden" href=""><?php echo esc_attr($homey_local['edit_btn']); ?></a>
                            </h2>
                        </div>
                        <div class="block-body homey-booking-block-body-2" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php if(!empty($cancellation_policy)) { ?>
                                        <h3><?php echo esc_attr($homey_local['cancel_policy']); ?></h3>
                                        <p><?php echo $cancellation_policy; ?></p>
                                    <?php } ?>

                                    <h3><?php echo esc_attr(homey_option('experience_sn_terms_rules')); ?></h3>
                                    <ul class="list-unstyled rules-options">
                                        <li>
                                            <i class="<?php echo esc_attr($smoke_allow); ?>" aria-hidden="true"></i> 
                                            <?php echo esc_attr(homey_option('experience_sn_smoking_allowed')); ?>:
                                            <span><?php echo esc_attr($smoke_text); ?></span>
                                        </li>                    
                                        <li>
                                            <i class="<?php echo esc_attr($pets_allow); ?>" aria-hidden="true"></i> 
                                           <?php echo esc_attr(homey_option('experience_sn_pets_allowed')); ?>:
                                            <span><?php echo esc_attr($pets_text); ?></span>
                                        </li>
                                        <li>
                                            <i class="<?php echo esc_attr($party_allow); ?>" aria-hidden="true"></i> 
                                            <?php echo esc_attr(homey_option('experience_sn_party_allowed')); ?>:
                                            <span><?php echo esc_attr($party_text); ?></span>
                                        </li>
                                        <li>
                                            <i class="<?php echo esc_attr($children_allow); ?>" aria-hidden="true"></i> 
                                            <?php echo esc_attr(homey_option('experience_sn_children_allowed')); ?>:
                                            <span><?php echo esc_attr($children_text); ?></span>
                                        </li>
                                    </ul>

                                    <?php if( !empty($additional_rules)) { ?>
                                    
                                        <p><?php echo $additional_rules; ?></p>
                                    
                                    <?php } ?>
                                
                                    
                                    <div class="well">
                                        <!-- the div here below must be hidden and visible only if users doesn't check the policy agreement -->
                                        <div style="display: none;" class="mb-10"><i class="homey-icon homey-icon-alert-circle-interface-essential text-danger" aria-hidden="true"></i> <?php echo esc_attr($homey_local['agr_policy']); ?></div>
                                        
                                        <label class="control control--checkbox mb-0">
                                            <input type="checkbox" name="agreement">
                                            <span class="contro-text"><?php echo esc_attr($homey_local['read_agr_text']); ?></span>
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="continue-block-button">
                                <button type="button" class="btn homey-booking-exp-step-2 btn-booking btn-full-width"><?php echo esc_attr($homey_local['continue_btn']); ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="block homey-booking-block-title-3 inactive mb-0">
                        <div class="block-head table-block">
                            <h2 class="title"><span class="circle-icon">3</span> <?php echo esc_attr($homey_local['payment_label']); ?></h2>
                        </div>
                        
                        <div class="block-body homey-booking-block-body-3" style="display: none;">
                            
                            <?php if( homey_is_woocommerce() ) { ?>

                                <button id="make_woocommerce_instant_experience_payment" type="button" class="btn btn-booking btn-full-width"><?php echo esc_attr($homey_local['btn_process_pay']); ?></button>
                                    <div id="instance_noti"></div>

                                    <div id="without_stripe" class="continue-block-button">
                                        <input type="hidden" id="check_in_date" value="<?php echo esc_attr($check_in); ?>">
                                        <input type="hidden" id="guests" value="<?php echo esc_html($guests); ?>">
                                
                                        <?php
                                        if(!empty($extra_options)) {
                                            foreach ($extra_options as $extra_price) {

                                                $ex_single_price = explode('|', $extra_price);
                                                $ex_name = $ex_single_price[0];
                                                $ex_price = $ex_single_price[1];
                                                $ex_type = $ex_single_price[2];
                                                echo '<input type="hidden" class="homey_extra_price" data-name="'.$ex_name.'" data-price="'.$ex_price.'" data-type="'.$ex_type.'">';
                                            }
                                        }
                                        ?>

                                        <input type="hidden" id="experience_id" value="<?php echo intval($experience_id); ?>">
                                        <input type="hidden" name="checkout-security" id="checkout-security" value="<?php echo wp_create_nonce('checkout-security-nonce'); ?>"/>
                                    </div>
                            <?php    
                            } else { ?>
                            <form name="homey_checkout" method="post" class="homey_payment_form" action="<?php echo esc_url($stripe_processor_link); ?>">
                                <div class="row">

                                    <div class="col-sm-12">
                                        <h3><?php echo esc_attr($homey_local['select_payment']); ?></h3>
                                        <div class="payment-method">
                                            <?php if( $enable_paypal != 0 ) { ?>
                                            <div class="payment-method-block paypal-method">
                                                <div class="form-group">
                                                    <label class="control control--radio radio-tab">
                                                        <input class="homey_check_gateway" name="payment_gateway" value="paypal" type="radio">
                                                        <span class="control-text"><?php esc_html_e('Paypal', 'homey'); ?></span>
                                                        <span class="control__indicator"></span>
                                                        <span class="radio-tab-inner"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <?php if( $enable_stripe != 0 ) { ?>
                                            <div class="payment-method-block stripe-method">
                                                <div class="form-group">
                                                    <label class="control control--radio radio-tab">
                                                        <input class="homey_check_gateway" name="payment_gateway" value="stripe" type="radio">
                                                        <span class="control-text"><?php esc_html_e('Stripe', 'homey'); ?></span>
                                                        <span class="control__indicator"></span>
                                                        <span class="radio-tab-inner"></span>
                                                    </label>
                                                    
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <?php 
                                if( $enable_stripe != 0 ) {
                                    homey_stripe_payment_instance_exp($experience_id, $check_in, $guests, $guest_message);
                                }
                                ?>

                                <div id="without_stripe" class="continue-block-button">
                                    <input type="hidden" id="check_in_date" value="<?php echo esc_attr($check_in); ?>">
                                    <input type="hidden" id="guests" value="<?php echo esc_html($guests); ?>">
                            
                                    <?php
                                    if(!empty($extra_options)) {
                                        foreach ($extra_options as $extra_price) {

                                            $ex_single_price = explode('|', $extra_price);
                                            $ex_name = $ex_single_price[0];
                                            $ex_price = $ex_single_price[1];
                                            $ex_type = $ex_single_price[2];
                                            echo '<input type="hidden" class="homey_extra_price" data-name="'.$ex_name.'" data-price="'.$ex_price.'" data-type="'.$ex_type.'">';
                                        }
                                    }
                                    ?>

                                    <input type="hidden" id="experience_id" value="<?php echo intval($experience_id); ?>">
                                    <input type="hidden" name="checkout-security" id="checkout-security" value="<?php echo wp_create_nonce('checkout-security-nonce'); ?>"/>

                                    <button id="make_instance_exp_booking_payment" type="button" class="btn btn-booking btn-full-width"><?php echo esc_attr($homey_local['btn_process_pay']); ?></button>
                                    <div id="instance_noti"></div>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                

            </div><!-- .row -->

        </div><!-- .container -->
    <?php } ?> <!-- end check availability if -->

</section><!-- main-content-area -->
