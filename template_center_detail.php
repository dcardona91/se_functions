<?php
/*
Template Name: Detail
*/
get_header();
 $directory = get_stylesheet_directory_uri() . '/search/';
 
 wp_enqueue_style( 'css_lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.11/css/lightgallery.min.css', array() );
 wp_enqueue_style( 'search_styles', $directory .'css/styles.css?cache='.uniqid(), array('css_lightgallery') ); 
  wp_enqueue_script( 'jquery_mousewheel', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js', array('jquery') );
  wp_enqueue_script( 'jquery_lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.11/js/lightgallery-all.min.js', array('jquery') );
   wp_enqueue_script( 'search_scripts', $directory .'js/scripts.js?cache='.uniqid(), array() );
   wp_enqueue_script( 'init_detail', $directory .'js/init_detail.js?cache='.uniqid(), array('jquery', 'search_scripts') );
   if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) || $_REQUEST['id'] == '' )
   		header("Location: /centers?state=nothing");
   $c = apply_filters('get_center', $_REQUEST['id']);
   $img_dir = get_stylesheet_directory_uri() . '/search/assets/images/'.$_REQUEST['id'].'/';

/*
  $facilities_list = apply_filters('general_search', 'all_facilities');
  $services_list = apply_filters('general_search', 'all_services');
  $profiles_list = apply_filters('general_search', 'all_profiles');
  $therapies_list = apply_filters('general_search', 'all_therapies');
  $accreditations_list = apply_filters('general_search', 'all_accreditations');
  $insurances_list = apply_filters('general_search', 'all_insurances');
*/
//--Properties

  $states_list = apply_filters('general_search', 'all_states');
  $services_list = apply_filters('properties', 'services', $_REQUEST['id']);
  $therapies_list = apply_filters('properties', 'therapies', $_REQUEST['id']);
  $insurances_list = apply_filters('properties', 'insurances', $_REQUEST['id']);
  $accreditations_list = apply_filters('properties', 'accreditations', $_REQUEST['id']);
  $facilities_list = apply_filters('properties', 'facilities', $_REQUEST['id']);
  $profiles_list = apply_filters('properties', 'profiles', $_REQUEST['id']);
?>

<section class="single__hero" style="background-image: url('<?php  echo $img_dir . $c->main_picture; ?>')">
      <div class="single__heroInfo">
        <h1 class="single__heading"><?php echo $c->facility_name; ?></h1>
        <p class="single__description color-white">
         <?php echo explode('. ', $c->description)[0].'.'; ?>
        </p>
      </div>
    </section>

    <div class="search_container containerFlex">
      <article class="single__mainContent">
      	<figure class="single__mainContent--logo_container">
      		<img src="<?php echo $img_dir . $c->logo; ?>" alt="">
      	</figure>
        <h1 class="heading-2"><?php echo $c->facility_name; ?></h1>
        <?php echo '<p>'.str_replace('. ', '.<p/><p>', $c->description).'</p>'; ?>
<!-- Comment out 'Gallery'
        <div class="single__gallery margin-top-3">
          <h3 class="heading-2">Gallery</h3>
          <div id="lightgallery">
            <a href="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg">
              <img src="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg" />
            </a>
            <a href="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg">
              <img src="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg" />
            </a>
            <a href="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg">
              <img src="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg" />
            </a>
            <a href="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg">
              <img src="https://dev.recoveryspeakers.com/wp-content/uploads/2018/12/center_background.jpg" />
            </a>
          </div>
        </div>
-->
        <br/>
        <h1 class="heading-2">Center services</h1>
        <div class="center_detail_list--container">
        <div class="center_detail_item">
          <h3 class="heading-3">Type of facilities</h3>
          <ul class="single__sidebarList">
          	<?php foreach ($facilities_list as $i => $val): ?>
          		<li class="single__sidebarListItem"><?php echo  $val->name.' / '.$val->short_name ?></li>
          	<?php endforeach ?>
          </ul>
        </div>
        <div class="center_detail_item">
          <h3 class="heading-3">Types of services</h3>
          <ul class="single__sidebarList">
          	<?php foreach ($services_list as $i => $val): ?>
          		<li class="single__sidebarListItem"><?php echo  $val->name ?></li>
          	<?php endforeach ?>
          </ul>
        </div>
        <div class="center_detail_item">
          <h3 class="heading-3">Client profiles</h3>
          <ul class="single__sidebarList">
          	<?php foreach ($profiles_list as $i => $val): ?>
          		<li class="single__sidebarListItem"><?php echo  $val->name ?></li>
          	<?php endforeach ?>
          </ul>
        </div>
        <div class="center_detail_item">
          <h3 class="heading-3">Types of therapy</h3>
          <ul class="single__sidebarList">
          	<?php foreach ($therapies_list as $i => $val): ?>
          		<li class="single__sidebarListItem"><?php echo  $val->name ?></li>
          	<?php endforeach ?>
          </ul>
        </div>
        <div class="center_detail_item">
          <h3 class="heading-3">Accreditation</h3>
          <ul class="single__sidebarList">
          	<?php foreach ($accreditations_list as $i => $val): ?>
          		<li class="single__sidebarListItem"><?php echo  $val->name ?></li>
          	<?php endforeach ?>
          </ul>
        </div>
        <div class="center_detail_item">
          <h3 class="heading-3">Types of insurance</h3>
          <ul class="single__sidebarList">
          	<?php foreach ($insurances_list as $i => $val): ?>
          		<li class="single__sidebarListItem"><?php echo  $val->name ?></li>
          	<?php endforeach ?>
          </ul>
        </div>
        </div>
      </article>
      <aside class="single__sidebar">
        <div class="margin-bottom-3">
          <h3 class="heading-2">Search by zip</h3>
           <form id="frm_search_zip" method="GET" action="/centers-results" class="search">
          <input name="zip_code" required type="number" class="search__field" placeholder="Zip code" />
          <input name="search_type" type="hidden" value="zip" />
          <button type="submit" class="btn btn--green margin-lfrg-2">
            Search
          </button>
        </form>
        </div>
       
        <div class="margin-bottom-3">
          <h3 class="heading-2">Contact info</h3>
          <ul class="single__sidebarList contact__info">
            <li class="single__sidebarListItem"><?php echo $c->phone_1; ?></li>
            <li class="single__sidebarListItem address__info">
             <?php
             $address =  str_replace('.,', ',', $c->address.', '.$c->city_name.', '.$c->state_code.' '.$c->zip);
             echo "<a target='_blank' href='https://www.google.com/maps/place/".urlencode($address)."'>".$address."</a>"
             ?>
            </li>
            <li class="single__sidebarListItem">
              <a target="_blank" href="<?php echo $c->website ?>"><?php echo $c->website ?></a>
            </li>
          </ul>
        </div>

         <div>
           <a href="https://www.recoveryspeakers.com/treatment-centers-search-directory/" class="btn btn--orange margin-top-3 color-white"> Return to search page</a>
        </div>
      </aside>
    </div>

    <section class="registerCenter margin-top-3">
      <div class="search_container">
        <h2 class="heading-1 color-white">
          Want to register a recovery center?
        </h2>
        <p class="description color-white margin-bottom-3">
          If you are not currently in our directory or if you would like to be listed as a “Featured Facility”, please reach out with your information.  Thanks for visiting Recovery Speakers!
        </p>
        <a href="https://www.recoveryspeakers.com/advertise-with-recovery-speakers/" target="_blank" class="btn btn--green">Submit a center</a>
      </div>
    </section>

    <section class="recoveryDirectory margin-bottom-3">
      <div class="search_container">
        <h2 class="heading-1">Recovery centers directory</h2>
        <input type="hidden" id="states_data" value='<?php echo json_encode($states_list); ?>'>
        <ul id="states_directory_index--container" class="pagination pagination--large margin-tpbt-2">
          <li class="pagination__item loading_content blue">
            <a href="#" class="pagination__link pagination__link--active ">A</a>
          </li>
          <li class="pagination__item loading_content blue">
            <a href="#" class="pagination__link ">B</a>
          </li>
          <li class="pagination__item loading_content blue">
            <a href="#" class="pagination__link ">C</a>
          </li>
        </ul>
        <div class="recoveryDirectoryContainer">
          <a href="#" class="recoveryDirectoryItem loading_content brown">Arizona</a>
          <a href="#" class="recoveryDirectoryItem loading_content brown">California</a>
          <a href="#" class="recoveryDirectoryItem loading_content brown">New York</a>
          <a href="#" class="recoveryDirectoryItem loading_content brown">Florida</a>
        </div>
      </div>
    </section>
<?php
get_footer();