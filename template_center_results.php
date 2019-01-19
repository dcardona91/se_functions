<?php
/*
Template Name: Results
*/

get_header();
 wp_enqueue_style( 'search_styles', get_stylesheet_directory_uri() . '/search/css/styles.css?cache='.date_timestamp_get(date_create()), array() );

 wp_enqueue_style( 'animate_css', get_stylesheet_directory_uri() . '/search/css/animate.css', array() );
  wp_enqueue_script( 'search_scripts', get_stylesheet_directory_uri() . '/search/js/scripts.js?cache='.date_timestamp_get(date_create()), array('jquery') );
  wp_enqueue_script( 'init_search', get_stylesheet_directory_uri() . '/search/js/init_search.js?cache='.date_timestamp_get(date_create()), array('jquery', 'search_scripts') );




  //searc by zip code
  if (!isset($_REQUEST['search_type']))
  	header("Location: /centers?state=nothing");

  	 //select populators
  $facilities_list = apply_filters('general_search', 'all_facilities');
  $services_list = apply_filters('general_search', 'all_services');
  $profiles_list = apply_filters('general_search', 'all_profiles');
  $therapies_list = apply_filters('general_search', 'all_therapies');
  $accreditations_list = apply_filters('general_search', 'all_accreditations');
  $insurances_list = apply_filters('general_search', 'all_insurances');
   $states_list = apply_filters('general_search', 'all_states');
  //end select populators
   $near = '';
 if ($_REQUEST['search_type'] === 'zip') {
 	$near = ' near '.$_REQUEST['zip_code'];
 	if(!isset($_REQUEST['zip_code']) || !is_numeric($_REQUEST['zip_code']) || $_REQUEST['zip_code'] == '' )
   		header("Location: /centers?state=nothing");
 	 $premium_ads = apply_filters('radius', 'premium',$_REQUEST['zip_code'], 200);  
	 $free_ads_page = apply_filters('radius', 'free', $_REQUEST['zip_code'],200);
	 $page_count = ceil(count($free_ads_page) / 20);
 }else{
 	 $premium_ads = apply_filters('filter', 'premium',$_REQUEST);  
	 $free_ads_page = apply_filters('filter', 'free', $_REQUEST);
	 $page_count = ceil(count($free_ads_page) / 20);
 }

 $max_page_list = 10;
?>
    <section class="premiumPlaces margin-top-3 margin-bottom-5">
      <div class="search_container">
        <h2 class="heading-1" style="text-align: center;"><?php echo count($premium_ads) ?> 'Featured' facilites found <?php echo $near; ?>.  See others below</h2>
        <div class="premiumPlacesContanier">
          <?php foreach ($premium_ads as $i => $ad): ?>
              <div class="premiumPlacesItem" style="background-image: url(<?php echo get_stylesheet_directory_uri().'/search/assets/images/'.$ad->id.'/'.$ad->main_picture; ?>);">
                <div class="premiumPlacesInfo">
                  <h3 class="heading-3 margin-none"><?php echo $ad->facility_name; ?></h3>
                  <p><?php echo $ad->address.', '.$ad->city_name.', '.$ad->state_code.', '.$ad->zip; ?></p>
                  <span><?php echo $ad->phone_1; ?></span>
                  <a href="/center_detail?id=<?php echo $ad->id; ?>" class="premiumPlacesBtn">View full info</a>
                </div>
              </div>
          <?php endforeach ?>
        </div>
      </div>
    </section>
<section class="home__hero">
      <div class="home__heroInfo">
        <h1 class="heading-1">Search by zip code</h1>
        <p class="home__heroInfoDescription description margin-bottom-3">
          Alcoholism and Addiction are treatable diseases.  Our nation-wide directory of addiction rehabilitation	and treatment centers can help you find the help you or your loved one deserves.  Use our advanced search to define and find the services and resources you need to recover today.  A new life begins on the day you make a decision to take one positive action towards change!
        </p>
         <form method="GET" action="/centers-results" class="search">
          <input name="zip_code" required type="number" class="search__field" placeholder="Zip code" />
          <input name="search_type" type="hidden" value="zip" />
          <button type="submit" class="btn btn--green margin-lfrg-2">
            Search
          </button>
          <button type="button" class="btn btn--orange search__advancedBtn">
            Advanced search
          </button>
        </form>
        <form method="GET" action="/centers-results" class="search">
          <div class="hide search__advancedFields">
            <span class="search__advancedFieldsClose">&times;</span>
            <div class="containerNested containerFlex">
          <input name="search_type" type="hidden" value="advanced" />
              
              <div class="search__groupFields">
                <label for="facilities">Type of facility</label>
                <select name="facilities" id="facilities">
                  <option value="">Any</option>
                  <?php foreach ($facilities_list as $index => $f): ?>
                    <option value="<?php echo $f->id; ?>"><?php echo $f->name .' / '. $f->short_name; ?></option><?php endforeach ?>
                </select>
              </div>

              <div class="search__groupFields">
                <label for="services">Type of service</label>
                <select name="services" id="services">
                  <option value="">Any</option>
                   <?php foreach ($services_list as $index => $s): ?>
                    <option value="<?php echo $s->id; ?>"><?php echo $s->name; ?></option><?php endforeach ?>
                </select>
              </div>

              <div class="search__groupFields">
                <label for="profiles">Client profile</label>
                <select name="profiles" id="profiles">
                  <option value="">Any</option>
                   <?php foreach ($profiles_list as $index => $p): ?>
                    <option value="<?php echo $p->id; ?>"><?php echo $p->name; ?></option><?php endforeach ?>
                </select>
              </div>

              <div class="search__groupFields">
                <label for="therapies">Type of therapy</label>
                <select name="therapies" id="therapies">
                  <option value="">Any</option>
                   <?php foreach ($therapies_list as $index => $t): ?>
                    <option value="<?php echo $t->id; ?>"><?php echo $t->name; ?></option><?php endforeach ?>
                </select>
              </div>

              <div class="search__groupFields">
                <label for="accreditations">Accreditation</label>
                <select name="accreditations" id="accreditations">
                  <option value="">Any</option>
                   <?php foreach ($accreditations_list as $index => $a): ?>
                    <option value="<?php echo $a->id; ?>"><?php echo $a->name; ?></option><?php endforeach ?>
                </select>
              </div>

              <div class="search__groupFields">
                <label for="insurances">Types of insurance</label>
                <select name="insurances" id="insurances">
                  <option value="">Any</option>
                   <?php foreach ($insurances_list as $index => $ins): ?>
                    <option value="<?php echo $ins->id; ?>"><?php echo $ins->name; ?></option><?php endforeach ?>
                </select>
              </div>
              
              <div class="search__groupFields">
                <label for="state">State</label>
                <select required name="state" id="state">
                  <option value="">Any</option>
                   <?php foreach ($states_list as $index => $s): ?>
                    <option value="<?php echo $s->id; ?>"><?php echo $s->full_name .', '. $s->short_name; ?></option><?php endforeach ?>
                </select>
              </div>

              <button type="submit" id="btn_frm_adv_search" class="btn btn--orange margin-top-3">
                Advanced search
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section class="otherPlaces margin-top-3">
      <div class="search_container">
        <h2 class="heading-1"><?php echo count($free_ads_page); ?> Other results <?php echo  $near ; ?></h2>
        <a href="#freeAds"></a>
        <div class="otherPlacesContainer">
        <?php 
        $the_counter = 0;
        foreach ($free_ads_page as $i => $c){ 
          $the_counter++;
          if($the_counter > 20)
            break;
          ?>
            
           <div class="otherPlacesItem">
            <h3 class="heading-2 margin-none"><?php echo $c->facility_name; ?></h3>
              <p class="description"><?php echo  str_replace(', ,', ', ', $c->address);  ?></p>
              <span><?php echo $c->phone_1; ?></span>
            </div>

        <?php 

      } ?>
        </div>
        <ul id="other_places_pagination--container" data-pages="<?php echo $page_count; ?>" class="pagination">
          <!-- <li class="pagination__item">
            <a href="#" class="pagination__link pagination__link--active">1</a>
          </li>
          <li class="pagination__item">
            <a href="#" class="pagination__link">2</a>
          </li>
          <li class="pagination__item">
            <a href="#" class="pagination__link">3</a>
          </li>-->
          <?php for ($i=1; $i <= ($page_count >= $max_page_list ? $max_page_list : $page_count) ; $i++) { ?>
            <li class="pagination__item">
              <a href="#" data-page="<?php echo $i; ?>" class="pagination__link <?php echo $i == 1 ? "pagination__link--active" : "" ?>"><?php echo $i; ?></a>
            </li>
          <?php } 
          if ( ($page_count - 10) >= $max_page_list) { ?>
            <li class="pagination__item">
               <a href="#" data-page="20" class="pagination__link ff">>></a>
            </li>
            <li class="pagination__item">
              <a href="#" data-page="<?php echo $page_count; ?>" class="pagination__link">>>></a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </section>
<section class="registerCenter">
      <div class="search_container">
        <h2 class="heading-1 color-white">
          Want to register a recovery center?
        </h2>
        <p class="description color-white margin-bottom-3">
          If you are not currently in our directory or if you would like to be listed as a “Featured Facility”, please reach out with your information.  Thanks for visiting Recovery Speakers!
        </p>
        <br/>
        <a href="https://www.recoveryspeakers.com/advertise-with-recovery-speakers/" target="_blank" class="btn btn--green">Submit a center</a>
      </div>
    </section>
    <section class="recoveryDirectory margin-bottom-3">
      <br/>
      <div class="search_container">
        <h2 class="heading-1">Facilities directory</h2>
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