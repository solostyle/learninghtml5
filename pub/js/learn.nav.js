this.Learn.Nav = this.Learn.Nav || function() {

	/* Elements
	* LISTING <ul>
	* housing all years: "archlev1, archmenu_list_years" ID: "archmenu"
	* housing all months in a year: "archlev2, archmenu_list_months" ID: "archmenu_y_2011">
	* housing all titles in a month: "archlev3, archmenu_list_titles" ID: "archmenu_y_2011_m_01">
	* list element for year: ID: "archmenu_li_y_2011"
	* list element for month: ID: "archmenu_li_y_2011_m_01"
	* list element for title: ID: "archmenu_li_id_2011/01/13/a-title"
	* TOGGLING <span>
	* year: "archmenu_ty" ID: "archmenu_ty_2011"
	* month: "archmenu_tm" ID: "archmenu_ty_2011_tm_01"
	*/
	
	/* Objects
	* Learn.Objects.ArchNavMenu is set when first page loads
	* if it is null, call /menu
	*/

	var navElem = function() {return jQuery('archmenuWP');},
		parentElem = function() {return jQuery('archmenuWP').parent();};

	// Success and failure functions for different requests
	var handleFailure = function(data){
		parentElem().html("request failure: " + data + parentElem().html());
	};

	var insertMenu = function(data) {
		if(data){
			parentElem().html(data);
		}
	};

	var storeMenu = function(data) {
		if(data){
			Learn.Objects.Nav = data;
			initMenuState();
		}
	};
	
	var indexCallback ={
		url: Learn.RootDir()+Learn.Ds()+'nav/index/1',
		success: insertMenu,
		error: handleFailure,
		dataType: 'html'
	};
	var saveCallback ={
		url: Learn.RootDir()+Learn.Ds()+'nav/save/1',
		success: storeMenu,
		error: handleFailure,
		dataType: 'json'
	};
	
	//Handler to make XHR request for just showing all entries
	var indexRequest = function(){
      jQuery.ajax(indexCallback);
	};
  
	// Stores the menu in Json in Learn.Objects.Nav
	var saveMenuRequest = function(){
      jQuery.ajax(saveCallback);
	};
	
	// Initializes the menu state with highlights and displays
	var initMenuState = function() {
		// rules:
		// > show the current url's submenu
		//		2011/10
		//			> expand 2011
		//		2011/10/04/entry
		//			> expand 2011 and 10
		//		2011/
		//			> don't expand anything
		// > show anything the user wanted to show
		// > hide everything else
		var menu = Learn.Objects.Nav,
		uriArray = window.location.pathname.split('/'),
		r = false, y = false, m = false, t = false, id;
		uriArray.shift();
		uriArray = uriArray.filter(function(){return true});
		
		//first check for the existence of all of it
		if (!uriArray[0]) r = true;
		if (uriArray[0] in menu) {
			y = true;
			if (uriArray[1] && uriArray[1] in menu[uriArray[0]]) {
				m = true;
				if (uriArray[3] && uriArray.join('/') in menu[uriArray[0]][uriArray[1]]) {
					t = true;
					id = uriArray.join('-');
				}
			}
		}
		
		switch (true) {
		case r : // if we're at the root path
			// expand the latest year submenu
			var latestyear = Object.keys(menu)[0], latestmonth = Object.keys(menu[latestyear])[1]; // latest month is not index 0 which is 'count'
			toggleMenu('#archmenu_y_'+latestyear, 'archmenu_ty_'+latestyear, true);
			// expand the latest month submenu
			toggleMenu('#archmenu_y_'+latestyear+'_m_'+latestmonth, 'archmenu_ty_'+latestyear+'_tm_'+latestmonth, true);
			// no highlighting
			break;
		case t : // year/mo/da/a-title
			// expand month submenu
			menu[uriArray[0]][uriArray[1]]['display'] = 'show';
			toggleMenu('#archmenu_y_'+uriArray[0]+'_m_'+uriArray[1], 'archmenu_ty_'+uriArray[0]+'_tm_'+uriArray[1], true);
			// expand year submenu
			menu[uriArray[0]]['display'] = 'show';
			toggleMenu('#archmenu_y_'+uriArray[0], 'archmenu_ty_'+uriArray[0], true);
			// highlight the title
			menu[uriArray[0]][uriArray[1]][id.replace(/-/gi, "/")]['highlight'] = 'true';
			jQuery('#archmenu_li_id_'+id).toggleClass('highlight', true);
			break;
		case m : // year/mo
			// expand year submenu
			menu[uriArray[0]]['display'] = 'show';
			toggleMenu('#archmenu_y_'+uriArray[0], 'archmenu_ty_'+uriArray[0], true);
			// highlight the month
			menu[uriArray[0]][uriArray[1]]['highlight'] = 'true';
			jQuery('#archmenu_li_y_'+uriArray[0]+'_m_'+uriArray[1]).toggleClass('highlight', true);
			break;
		case y : // year/
			// highlight the year
			menu[uriArray[0]]['highlight'] = 'true';
			jQuery('#archmenu_li_y_'+uriArray[0]).toggleClass('highlight', true);
			break;
		default:
			break;
		}
	};

	// Saves the view of the menu so that it can load it this way next time
	var saveMenuState = function(id, yr, mo) {
		if (!Learn.Objects.Nav) {
			saveMenuRequest();
			saveMenuState(id, yr, mo);
		} else {
			// look up the id in the array
			// if it's not found, do nothing
			// if found, get the class of the element
			// if it has a hidden class, change the display of the id to hide
			var menu = jQuery('#'+id);
			if (mo) {
				Learn.Objects.Nav[yr][mo]['display'] = menu.hasClass('hidden') ? 'hide' : 'show';
			} else {
				Learn.Objects.Nav[yr]['display'] = menu.hasClass('hidden') ? 'hide' : 'show';
			}
		}
	};
	
	// Toggles the view of menus and their buttons
	var toggleMenu = function(menuId, buttonId, show) {
		var button;
		if (typeof(show)=='undefined') {
			button = (jQuery(buttonId).html()=='--')? '+' : '--';
			jQuery(menuId).toggleClass('hidden');
			jQuery(buttonId).html(button);
		} else {
			button = (show)? '--' : '+';
			jQuery(menuId).toggleClass('hidden', !show);
			jQuery(buttonId).html(button);
		}
	};
	
	// Handles Clicks in the web part
	var handleClick = function(e) {
		var targetId = e.target.getAttribute('id'),
		cmd = (targetId)?targetId.split('_')[1]:null,
		year = (targetId)?targetId.split('_')[2]:null,
		cmd = (targetId && targetId.split('_')[3]) ? targetId.split('_')[3] : cmd,
		month = (targetId)?targetId.split('_')[4]:null,
		menuId = "";
		
		switch (cmd) {
		case "ty": // toggle year menu
			menuId = 'archmenu_y_'+year;
			toggleMenu('#'+menuId, targetId);
			saveMenuState(menuId, year, month);
			break;
		case "tm": // toggle month menu
			menuId = 'archmenu_y_'+year+'_m_'+month;
			toggleMenu('#'+menuId, targetId);
			saveMenuState(menuId, year, month);
			break;
		default:
			break;
		}
	};
	
	return {
		
		Load: function(){
			// initial load
			// currently header.php loads this
			//indexRequest(true);
			
			// store menu as js object
			// TODO: Only run this if anything has been added/deleted/modified
			saveMenuRequest();

			// set event handler for clicks in the web part
			Listen("click", handleClick, 'archmenuWP');
		}
	};

}();