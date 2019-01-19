var recovery_speakers = function(){
	let _states = {}, _indexes = [], _max_pages = 10, _api_key = `r3c0v3rySp34k3r5S34rch3ng1n3U53r`, _search_type = 'search_type';	
	registerEventHandlers = function(){

		document.getElementById('states_directory_index--container').addEventListener('click', function(event){
			event.preventDefault();
			event.target.classList.contains('pagination__link') && changeDirectory(event.target.getAttribute('data-letter'))
		});

		$(".search__advancedBtn").on("click", function() {
		  $(".search__advancedFields").toggleClass("hide");
		});

		$(".search__advancedFieldsClose").on("click", function() {
		  $(".search__advancedFields").addClass("hide");
		})

	},
	registerSearchEventHandlers = function(){
		document.getElementById('other_places_pagination--container').addEventListener('click', function(event){
			event.preventDefault();

			if(event.target.classList.contains('pagination__link')){
				if(event.target.classList.contains('bussy') || event.target.classList.contains('pagination__link--active')){
					console.log('is bussy');
					return false;
				}else{
					console.log('working on it');
					makeBussy('.pagination__link');
				}
				

				changeOtherPlacesPage(parseInt(event.target.getAttribute('data-page')), parseInt(this.getAttribute('data-pages')));
			}
		})
	},	
	initLightGallery = function(gallery, props){
		$(gallery).lightGallery != undefined && $(gallery).lightGallery(props);
	},
	startStatesDirectory = function(){
		makeStatesObject(JSON.parse(document.getElementById('states_data').value));
		makeStatesIndex();
	},
	makeStatesObject = function(directory){
		_states = directory.reduce((filtered, unfiltered, index)=>{
			const initial = getInitial(unfiltered.full_name);
			if(findInArray(_indexes, initial) === false )
				_indexes.push(initial)
			if(index === 1)
				filtered = {};
			filtered[initial] = directory.filter(state => initial === getInitial(state.full_name));
			return filtered;
		});
	},
	makeStatesIndex = function(){
		const container = document.getElementById('states_directory_index--container');
		container.innerHTML = '';
		_indexes.forEach((index)=>{
			const index_picker = `<li class="pagination__item">
				            <a href="#" data-letter="${index}" class="pagination__link ${index == 'a' &&  'pagination__link--active'} ">${index.toUpperCase()}</a>
				          </li>`;
			container.insertAdjacentHTML('beforeend', index_picker );
			changeDirectory('a');
		})
	},
	changeDirectory =  function(letter){
		changeIndexSelected(letter);
		const container = document.querySelector('.recoveryDirectoryContainer');
		container.innerHTML = '';
		_states[letter].forEach((state)=>{
			const index_picker = `<a href="/centers-results/?search_type=advanced&state=${state.id}" class="recoveryDirectoryItem">${state.full_name}, ${state.short_name}</a>`;
			container.insertAdjacentHTML('beforeend', index_picker );
		})
	},
	changeIndexSelected = function(letter){
		document.querySelectorAll('#states_directory_index--container .pagination__item .pagination__link').forEach((v)=>{
			v.classList.remove('pagination__link--active');
			v.getAttribute('data-letter') == letter && v.classList.add('pagination__link--active');
		})
	},
	getInitial = function(string){
		return string.split('')[0].toLowerCase();
	},
	findInArray = function(array, value){
		var finded = false;
		array.forEach((v)=>{
			if(value === v){
				finded = true;
			}
		})
		return finded;
	},
	makeBussy = function(selector){		
		document.querySelectorAll(selector).forEach((element)=>{
			element.classList.add('bussy');
		})
	},
	unmakeBussy = function(selector){
		setTimeout(function(selector) {
			document.querySelectorAll(selector).forEach((element)=>{
				element.classList.remove('bussy');
			})
		}, 1500, selector);
	},
	animateScroll = function(elem, style, unit, from, to, time, prop) {
    if (!elem) {
        return;
    }
    var start = new Date().getTime(),
        timer = setInterval(function () {
            var step = Math.min(1, (new Date().getTime() - start) / time);
            if (prop) {
                elem[style] = (from + step * (to - from))+unit;
            } else {
                elem.style[style] = (from + step * (to - from))+unit;
            }
            if (step === 1) {
                clearInterval(timer);
            }
        }, 25);
    if (prop) {
          elem[style] = from+unit;
    } else {
          elem.style[style] = from+unit;
    }
},
	changeOtherPlacesPage = function(page, total_pages){
		const topContainer = document.querySelector('.otherPlacesContainer');
		topContainer.classList.add('opacity_0');

		_max_pages =  total_pages < _max_pages ? total_pages : _max_pages;
    	//animateScroll(document.scrollingElement || document.documentElement, "scrollTop", "", $(window).scrollTop() + $(window).innerHeight(), document.querySelector('.otherPlaces').offsetTop - 100, 1000, true);


		const theContaier = document.getElementById('other_places_pagination--container'),
		      padding = Math.ceil(_max_pages / 2) - 1,
			  initial = total_pages > 10 ? (page  - padding) : 1,
			  start = initial >= ((total_pages - _max_pages) + 2) ? ((total_pages - _max_pages) + 2) : initial <= 1 ? 1 : initial,
			  final = page + padding,
			  end = final >= total_pages ? total_pages : final <= _max_pages ? _max_pages : final ;


		
		theContaier.innerHTML = '';

		for (var i = start; i <= end; i++) {
			const page_item = paginationItem(i,i, (i === page ? 'pagination__link--active' : ''));
			theContaier.insertAdjacentHTML('beforeend',page_item);
		}
		//Make fast forward button
		if(total_pages - end > 4 ){
			const ff = end + _max_pages;
			const page_item = paginationItem('>>',(ff >= total_pages ? total_pages : ff ), 'ff');
			theContaier.insertAdjacentHTML('beforeend',page_item);
		}
		//Make go to end button
		if(total_pages !== end ){
			const page_item = paginationItem('>>>',total_pages, 'end');
			theContaier.insertAdjacentHTML('beforeend',page_item);
		}

		//Make rewind button
		if(start - 1 > 3 ){
			
			const rw = start - _max_pages;
			const page_item = paginationItem('<<',(rw <= 1 ? 1 : rw ), 'rw');
			theContaier.insertAdjacentHTML('afterbegin',page_item);
		}

		//Make go to start button

		if(start !== 1 ){
			const page_item = paginationItem('<<<',1, 'start');
			theContaier.insertAdjacentHTML('afterbegin',page_item);
		}


		// get the content of the new page
		fetchFreeCenters(page);
	},
	paginationItem = function(text, value, className){
		return `<li class="pagination__item">
		          <a href="#" data-page="${value}" class="pagination__link ${className} bussy" >${text}</a>
		        </li>`;
	},
	fetchFreeCenters = function(page){
			const per_page = 20,
				offset = (page - 1) * per_page, 
				endpoint = getEndPoint(getQueryStringValue(_search_type))+`&limit=20&offset=${offset}`;
			fetch(endpoint).then(function(response){
				return response.json();
			}).then(function(centers){
				makeCentersUI(centers.data);
			}).catch(function(error){
				console.error(error);
			})
	},
	getQueryStringValue = function(index){
		const urlParams = new URLSearchParams(window.location.search);
		return urlParams.get(index);
	},
	getEndPoint = function(query){
		let end_point =  `https://api.recoveryspeakers.com/centers/${query || 'free'}/?userkey=${_api_key}`;
		if(query !== '')
			end_point+= setQueryString();
		console.log("Endpoint: ", end_point);
		return end_point;
	},
	setQueryString = function(){
		const urlParams = new URLSearchParams(window.location.search),
		f = document.createElement("form");
		urlParams.forEach((v, i) =>{
			const input = document.createElement("input"); //input element, text
					input.setAttribute('type',"hidden");
					input.setAttribute('name',i);
					input.setAttribute('value',v);
					f.appendChild(input);
		})
		return '&'+$(f).serialize();
	},
	makeCentersUI = function(centers){
		const theContaier = document.querySelector('.otherPlacesContainer');
		theContaier.innerHTML = '';
		centers.forEach((c)=>{
			theContaier.insertAdjacentHTML('beforeend',getFreeCenterTemplate(c.facility_name, c.address, c.phone_1));
		});
		unmakeBussy('.pagination__link');
		theContaier.classList.remove('opacity_0')
	},
	getFreeCenterTemplate = function(facility_name, address, phone_1){
		return `<div class="otherPlacesItem animated fadeIn">
	            	<h3 class="heading-2 margin-none">${facility_name}</h3>
	             	<p class="description">${address}</p>
	             	<span>${phone_1}</span>
	            </div>`;
}
	return {
		init_search : function() {
			registerSearchEventHandlers(), startStatesDirectory(), registerEventHandlers()
		},
		init_detail : function() {
			initLightGallery("#lightgallery", {thumbnail: true}), startStatesDirectory(), registerEventHandlers()
		},
		getIndexes : function(){
			return _indexes;
		}
	}
}()