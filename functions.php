<?php
/**
 * Divi Cake Child Theme Functions.php
 *
 * ===== NOTES ==================================================================
 * 
 * Unlike style.css, the functions.php of a child theme does not override its 
 * counterpart from the parent. Instead, it is loaded in addition to the parent's 
 * functions.php. (Specifically, it is loaded right before the parent's file.)
 * 
 * In that way, the functions.php of a child theme provides a smart, trouble-free 
 * method of modifying the functionality of a parent theme. 
 * 
 * Discover Divi Child Themes: https://divicake.com/products/category/divi-child-themes/
 * Sell Your Divi Child Themes: https://divicake.com/open/
 * 
 * =============================================================================== */
 
function divichild_enqueue_styles() {
  
	$parent_style = 'parent-style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version')
	);
}
add_action( 'wp_enqueue_scripts', 'divichild_enqueue_styles' );

// Register Centers Sidebar
function centers_sidebar() {
    register_sidebar( array(
        'name' => 'centers_sidebar',
        'id' => 'centers_sidebar',
        'description' => __( 'Widgets in this area will be shown on the centers sidebar.', 'theme-slug' ),
		'before_widget' => '<div id="%1$s" class="centers_sidebar--container et_pb_widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widgettitle centers_sidebar--title">',
        'after_title'   => '</h4>',
    ) );
}



add_filter('general_search', 'general_searchs');
add_filter('page_count', 'page_count');
add_filter('free_ads_page', 'free_ads_page');
add_filter('get_center', 'center_detial');
add_filter('properties', 'get_properties', 10, 2);
add_filter('radius', 'radius_search', 10, 3);
add_filter('filter', 'filter_search', 10, 2);

function free_ads_page($page){
    return  db_connection()->get_results("SELECT facility_name, address, phone_1 FROM ads WHERE active = 1 AND level_id = 2 ORDER BY facility_name LIMIT ".($page - 1).", 20");
}

function radius_search($type, $zip, $radius){
    $sql = "SELECT
            a.*,
            c.name,
            s.full_name AS 'state',
            s.short_name AS 'state_code',
            c.name AS 'city_name'
            FROM ads a
            INNER JOIN cities c ON a.city_id = c.id 
            INNER JOIN states s ON a.state_id = s.id
            WHERE 
            (3958 * 3.1415926 * SQRT((a.lat - (SELECT CAST(z.lat AS DECIMAL(10,6) ) FROM zip_codes z WHERE zip = {$zip})) * (a.lat - (SELECT CAST(z.lat AS DECIMAL(10,6) ) FROM zip_codes z WHERE zip = {$zip})) + COS(a.lat / 57.29578) * COS(33.417187 / 57.29578) * (a.lon - (SELECT CAST(z.lon AS DECIMAL(10,6) ) FROM zip_codes z WHERE zip = {$zip})) * (a.lon - (SELECT CAST(z.lon AS DECIMAL(10,6) ) FROM zip_codes z WHERE zip = {$zip}))) / 180)  <= {$radius}
            AND a.level_id = ".($type == 'free' ? 2 : 1)."
            AND a.active = 1 ";

    
     return  db_connection()->get_results($sql);
}

function filter_search($type, $filters){

    $the_filters = array();

    (isset($filters["facilities"]) && $filters["facilities"] !== '' ) && ($the_filters[] = 'a.id IN (SELECT DISTINCT (fxa.ad_id) FROM facilities_x_ad fxa WHERE fxa.facility_id = '.$filters['facilities'].')');

    (isset($filters["services"]) && $filters["services"] !== '' ) && ($the_filters[] = 'a.id IN (SELECT DISTINCT (sxa.ad_id) FROM services_x_ad sxa WHERE sxa.service_id = '.$filters['services'].')');

    (isset($filters["profiles"]) && $filters["profiles"] !== '' ) && ($the_filters[] = 'a.id IN (SELECT DISTINCT (pxa.ad_id) FROM profiles_x_ad pxa WHERE pxa.profile_id = '.$filters['profiles'].')');

    (isset($filters["therapies"]) && $filters["therapies"] !== '' ) && ($the_filters[] = 'a.id IN (SELECT DISTINCT (txa.ad_id) FROM therapies_x_ad txa WHERE txa.therapy_id = '.$filters['therapies'].')');

    (isset($filters["accreditations"]) && $filters["accreditations"] !== '' ) && ($the_filters[] = 'a.id IN (SELECT DISTINCT  (axa.ad_id)  FROM accreditations_x_ad axa WHERE axa.accreditation_id = '.$filters['accreditations'].')');

    (isset($filters["insurances"]) && $filters["insurances"] !== '' ) && ($the_filters[] = 'a.id IN (SELECT DISTINCT (ixa.ad_id) FROM insurances_x_ad ixa  WHERE ixa.insurance_id = '.$filters['insurances'].')');

    isset($filters["state"]) && ($the_filters[] = 'a.state_id = '.$filters['state']);

    $the_filters = implode(' AND ', $the_filters);
    

    $the_filters !== '' && ($the_filters = ' AND '.$the_filters);


    if ($type ===  'free') {
        $the_state = $filters['state'];
       $sql = "SELECT
            a.*,
            c.name,
            s.full_name AS 'state',
            s.short_name AS 'state_code',
            c.name AS 'city_name'
            FROM ads a
            INNER JOIN cities c ON a.city_id = c.id 
            INNER JOIN states s ON a.state_id = s.id
            WHERE a.active = 1 AND a.level_id = 2  AND a.state_id = {$the_state} ";
    }else{
        $sql = "SELECT
            a.*,
            c.name,
            s.full_name AS 'state',
            s.short_name AS 'state_code',
            c.name AS 'city_name'
            FROM ads a
            INNER JOIN cities c ON a.city_id = c.id 
            INNER JOIN states s ON a.state_id = s.id
            WHERE a.level_id = 1 AND a.active = 1 " .$the_filters." ";
    }
    
    if(isset($_REQUEST['yeah'])){
        exit($sql);
    }
     return  db_connection()->get_results($sql);
}

function center_detial($id){
    $the_query = "SELECT
                    a.*,
                    c.name,
                    s.full_name AS 'state',
                    s.short_name AS 'state_code',
                    c.name AS 'city_name'
                    FROM ads a
                    INNER JOIN cities c ON a.city_id = c.id 
                    INNER JOIN states s ON a.state_id = s.id
                    WHERE a.id =  {$id}
                    AND a.level_id = 1
                    AND a.active = 1
                    ORDER BY a.facility_name ASC LIMIT 1";
    return  db_connection()->get_row($the_query);
}

function general_searchs($query){
    $the_query = '';
    switch ($query) {
        case 'all_states':
            $the_query = "SELECT * FROM states WHERE active = 1 ORDER BY full_name ASC";
            break;
        case 'all_facilities':
            $the_query = "SELECT * FROM facilities WHERE active = 1 ORDER BY name ASC";
            break;
        case 'all_services':
            $the_query = "SELECT * FROM services WHERE active = 1 ORDER BY name ASC";
            break;
        case 'all_profiles':
            $the_query = "SELECT * FROM profiles WHERE active = 1 ORDER BY name ASC";
            break;
        case 'all_therapies':
            $the_query = "SELECT * FROM therapies WHERE active = 1 ORDER BY name ASC";
            break;
        case 'all_accreditations':
            $the_query = "SELECT * FROM accreditations WHERE active = 1 ORDER BY name ASC";
            break;
        case 'all_insurances':
            $the_query = "SELECT * FROM insurances WHERE active = 1 ORDER BY name ASC";
            break;
        case '3_rand_premium_ads':
            $the_query = "SELECT *, ( SELECT  name FROM cities WHERE id = a.city_id) as 'city_name', (SELECT short_name FROM states WHERE id = a.state_id) as 'state_code' FROM ads a WHERE a.level_id = 1 AND a.active = 1 ORDER BY RAND() LIMIT 3";
            break;
    }
    $query = db_connection()->get_results($the_query);
    return $query;
}



function get_properties($service, $ad){
    $the_query = '';
    switch ($service) {
        case 'accreditations':
            $the_query = "SELECT DISTINCT
                            (ac.id), ac.name
                        FROM
                            accreditations_x_ad acca
                                INNER JOIN
                            accreditations ac ON acca.accreditation_id = ac.id
                        WHERE
                            acca.ad_id = {$ad} AND ac.active = 1;";
            break;
        case 'facilities':
            $the_query = "SELECT DISTINCT
                                (f.id), f.name, f.short_name
                            FROM
                                facilities_x_ad fa
                                    INNER JOIN
                                facilities f ON fa.facility_id = f.id
                            WHERE
                                fa.ad_id = {$ad} AND f.active = 1";
            break;
        case 'insurances':
            $the_query = "SELECT DISTINCT
                                (ins.id), ins.name
                            FROM
                                insurances_x_ad insa
                                    INNER JOIN
                                insurances ins ON insa.insurance_id = ins.id
                            WHERE
                                insa.ad_id = {$ad} AND ins.active = 1";
            break;
        case 'profiles':
            $the_query = "SELECT DISTINCT
                            (p.id), p.name
                        FROM
                            profiles_x_ad pa
                                INNER JOIN
                            profiles p ON pa.profile_id = p.id
                        WHERE
                            pa.ad_id = {$ad} AND p.active = 1";
            break;
        case 'services':
            $the_query = "SELECT DISTINCT
                                (s.id), s.name
                            FROM
                                services_x_ad sa
                                    INNER JOIN
                                services s ON sa.service_id = s.id
                            WHERE
                               sa.ad_id = {$ad} AND s.active = 1";
            break;
        case 'therapies':
            $the_query = "SELECT DISTINCT
                                (t.id), t.name
                            FROM
                                therapies_x_ad ta
                                    INNER JOIN
                                therapies t ON ta.therapy_id = t.id
                            WHERE
                               ta.ad_id = {$ad} AND t.active = 1";
            break;
    }
    $query = db_connection()->get_results($the_query);
    return $query;
}

function page_count($from, $size = 20){
    $from = ($from == 'free' ? 2 : 1);
    return ceil(db_connection()->get_var("SELECT COUNT(id)/{$size} FROM ads WHERE active = 1 and level_id = {$from}"));
}

function db_connection(){
    if(!isset($GLOBALS['rsdb'])){
        $GLOBALS['rsdb'] = new wpdb('recovery_searchu','s34rch_2018.','recovery_search','localhost');
    }
    return $GLOBALS['rsdb'];    
}
// widget_categories
add_action( 'widgets_init', 'centers_sidebar' );









//add CPT "centers" to DIVI 
function my_et_builder_post_types( $post_types ) {
    $post_types[] = 'centers';
    return $post_types;
}

add_filter( 'et_builder_post_types', 'my_et_builder_post_types' );


//return the post id in a shortcode

add_shortcode( 'return_post_id', 'the_dramatist_return_post_id' );

function the_dramatist_return_post_id() {
    return get_the_ID();
}

//return all the centers
add_shortcode( 'get_all_centers', 'display_custom_post_type' );

    function display_custom_post_type(){
        $args = array(
            'post_type' => 'centers',
            'post_status' => 'publish',
            'posts_per_page' => 100
        );

        $string = '';
        $query = new WP_Query( $args );
        if( $query->have_posts() ){
            $string .= '<ul>';
            while( $query->have_posts() ){
                $query->the_post();
                $string .= '<li>' . get_the_title() . '</li>';
            }
            $string .= '</ul>';
        }


        $big = 999999999; // need an unlikely integer

		$pagination = paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $query->max_num_pages
		) );


        wp_reset_postdata();
        return $string."<br/><hr/><br/>".$pagination;
    }