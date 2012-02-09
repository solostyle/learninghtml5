this.Learn.Admin = this.Learn.Admin || function() {

    // Elements
    var adminSectionElem = function() {return Ydom.get('admin');},
    blogSectionElem = function() {return Ydom.get('blog');},
    formDivElem = function() {return Ydom.get('add-article-form');},
    formToggleDivElem = function() {return Ydom.get('new-article-button');},
    formTitleElem = function() {return Ydom.get('add-article-form-title');},
    formEntryElem = function() {return Ydom.get('add-article-form-entry');},
    formTimeElem = function() {return Ydom.get('add-article-form-time');},

    formYearElem = function() {return Ydom.get('add-article-form-year');},
    formMonthElem = function() {return Ydom.get('add-article-form-month');},
    formDateElem = function() {return Ydom.get('add-article-form-date');},
    formHourElem = function() {return Ydom.get('add-article-form-hour');},
    formMinuteElem = function() {return Ydom.get('add-article-form-minute');},
    
    formEditElem = function(pre, id) {return Ydom.get(pre+'_'+id);},
    updTitle = function(id) {return formEditElem("entry-title", id).innerHTML;},
    updEntry = function(id) {return formEditElem("entry-entry", id).innerHTML;},
    updCategory = function(id) {return formEditElem("entry-category", id).lastChild.innerHTML;},
	
    inpEntry = function() {return formEntryElem().value;}, // TODO: escape quotes!
    inpTitle = function() {return formTitleElem().value;}, // TODO: escape quotes!
    inpCategory = function() {return findCategory();},
    inpTime = function() {return formTimeElem().textContent;},
    inpYear = function() {return formYearElem().value;},
    inpMonth = function() {return formMonthElem().value;},
    inpDate = function() {return formDateElem().value;},
    inpHour = function() {return formHourElem().value;},
    inpMinute = function() {return formMinuteElem().value;};
    
    // Success and failure functions for different requests
    var handleFailure = function(o){
        if(o.responseText !== undefined){
            adminSectionElem().innerHTML = "request failure: " + o.responseText + adminSectionElem().innerHTML;
        }
    };

    var handleSuccess = function(o) {
        // b/c successful, clear the form
        clearForm();
        // load the entries again into #blog section
        reloadCurrentPageRequest();
    };

    var loadIndex = function(o){
        toggleForm("close");
        if(o.responseText !== undefined){
            blogSectionElem().innerHTML = o.responseText;
        }        
    };
    
    /* Callback/Config objects for transactions */
    var allCallback = {
        method: "GET",
        success: loadIndex,
        failure: handleFailure
    };

    var callback = {
        method:"POST",
        success: handleSuccess,
        failure: handleFailure
    };

    //Handler to make XHR request for showing entries
	// current URL is window.location.href
	// So if you add/update/delete from root/2011/04/ url, it shouldn't load blog/index, but /blog/id/2011/04
	var reloadCurrentPageRequest = function() {
		window.location.reload();
	};
		
	// currently not used
	// blog/index/1 because it's an ajax request
    var indexRequest = function(){
        var request = AjaxR(Learn.RootDir()+Learn.Ds()+'blog/index/1', allCallback);
    };
    
    //Handler to make XHR request for adding an entry
    var addEntryRequest = function(){
        callback.data = 'title='+inpTitle()+'&category='+inpCategory()+'&entry='+Learn.Htmlize(inpEntry())+'&time='+inpTime()+'&year='+inpYear()+'&month='+inpMonth()+'&date='+inpDate();
        var addRequest = AjaxR(Learn.RootDir()+Learn.Ds()+'blog/add', callback);
    };

    var deleteEntryRequest = function(id) {
        callback.data = 'id='+id;
        var deleteRequest = AjaxR(Learn.RootDir()+Learn.Ds()+'blog/delete', callback);
    };
    
    var updateEntryRequest = function(id) {
        callback.data = 'id='+id+'&title='+updTitle(id)+'&category='+updCategory(id)+'&entry='+updEntry(id);
        var updateRequest = AjaxR(Learn.RootDir()+Learn.Ds()+'blog/add', callback);
    };
  
    var findCategory = function() {
        var el = Ydom.getElementBy(findCatName, 'input', adminSectionElem());
        return el.getAttribute('id').split('_', 2)[1];
    };
    
    var findCatName = function(el) {
        if (el.getAttribute('id') && el.getAttribute('id').split('_', 2)[0] == 'add-article-form-category') {
            if (el.checked) return true;
            else return false;
        } else return false;
    };
    
    var toggleForm = function(cmd) {
        // save off the current values of the input boxes
        var currTitleVal = formTitleElem().value || 'article title';
        var currEntryVal = formEntryElem().value || 'article text';
        
        if(cmd==="close") {
            formDivElem().style.display = "";
            formToggleDivElem().innerHTML = "New Article";
        } else {
            formDivElem().style.display = (formDivElem().style.display=='block')?'':'block';
            formToggleDivElem().innerHTML = (formDivElem().style.display=='block')?'Close':'New Article';
        }
        
        if (formDivElem().style.display=='') {
            formTitleElem().value = currTitleVal;
            formEntryElem().value = currEntryVal;
        }
        updateTimeToNow(); //update the time to now anytime the form is toggled
    };
    
    var clearForm = function() {
        formTitleElem().value = 'article title';
        formEntryElem().value = 'article text';
        updateTimeToNow();
        changeTime();
    };
    
    var doubleDigitString = function(digitString) {
        if (digitString.toString().length == 1) {
            return "0" + digitString.toString();
        } else return digitString;
    };
    
    var updateTimeToNow = function() {
        var now = new Date();
        var month = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        formYearElem().value = now.getFullYear();
        formMonthElem().value = month[now.getMonth()];
        formDateElem().value = doubleDigitString(now.getDate());
        formHourElem().value = doubleDigitString(now.getHours());
        formMinuteElem().value = doubleDigitString(now.getMinutes());
    };
    
    var changeTime = function() {
        formTimeElem().textContent = inpYear() + '.' + inpMonth() + '.' + inpDate() + ' ' + inpHour() + ':' + inpMinute();
    };
    
    var makeEditableTitle = function(editButton, id) {
        // change behavior of the entryEditButton for title
        editButton.setAttribute('id', "save-title_" + id);
        editButton.innerHTML = "Save";
        
        // change behavior of the entryTitle h2 element
        var titleEl = formEditElem("entry-title", id);
        titleEl.innerHTML = '<input type="text" value="'+titleEl.innerHTML+'" />';
    };
    
    var saveTitle = function(saveButton, id) {
        // change behavior of the entryTitle h2 element
        var titleEl = formEditElem("entry-title", id);
        var childEl = titleEl.childNodes[0];
        titleEl.innerHTML = childEl.value;
        
        // change behavior of the entryEditButton for title
        saveButton.setAttribute('id', "edit-title_" + id);
        saveButton.innerHTML = "Edit";
        
        var request = updateEntryRequest(id);
    };
    
    var makeEditableEntry = function(editButton, id) {
        // change behavior of the entryEditButton for title
        editButton.setAttribute('id', "save-entry_" + id);
        editButton.innerHTML = "Save";
        
        // change behavior of the entryEntry div element
        var entryEl = formEditElem("entry-entry", id);
        var clean = Learn.Textize(entryEl.innerHTML);
        entryEl.innerHTML = '<textarea>'+clean+'</textarea>';
    };
    
    var saveEntry = function(saveButton, id) {
        // change behavior of the entryEntry div element
        var entryEl = formEditElem("entry-entry", id);
        var childEl = entryEl.childNodes[0];
        entryEl.innerHTML = Learn.Htmlize(childEl.value);
        
        // change behavior of the entryEditButton for title
        saveButton.setAttribute('id', "edit-entry_" + id);
        saveButton.innerHTML = "Edit";
        
        var request = updateEntryRequest(id);
    };
    
    // TODO: make this show all the categories in a <select>
    var makeEditableCategory = function(editButton, id) {
        // change behavior of the entryEditButton for category
        editButton.setAttribute('id', "save-category_" + id);
        editButton.innerHTML = "Save";
        
        // change behavior of the entryCategory span element
        var categoryEl = formEditElem("entry-category", id);
        //categoryEl.innerHTML = '<select>'+clean+'</select>';
		categoryEl.innerHTML = '<input type="text" value="'+categoryEl.lastChild.innerHTML+'" />';
    };
    
    var saveCategory = function(saveButton, id) {
        // change behavior of the entryCategory span element
        var categoryEl = formEditElem("entry-category", id);
        var childEl = categoryEl.childNodes[0];
		var htmlized = '<a href="'+Learn.RootDir()+Learn.Ds()+'category'+Learn.Ds()+childEl.value+'">'+childEl.value+'</a>';
        categoryEl.innerHTML = htmlized; // link
        
        // change behavior of the entryEditButton for category
        saveButton.setAttribute('id', "edit-category_" + id);
        saveButton.innerHTML = "Edit";
        
        var request = updateEntryRequest(id);
    };
    
	var handleClick = function(e) {
        var targetId= e.target.getAttribute('id'),
        // clean the id string, everything before a number
        command = (targetId)?targetId.split('_', 2)[0]:null;
        id = (targetId)?targetId.split('_', 2)[1]:null;
        switch (command) {
        case "add-article-form-submit": 
            addEntryRequest(1);
            break;
        case "delete-entry":
            deleteEntryRequest(id);
            break;
        case "new-article-button":
            toggleForm();
            break;
        case "add-article-form-change-time":
            changeTime();
            break;
        case "edit-title":
            makeEditableTitle(e.target, id);
            break;
        case "save-title":
            saveTitle(e.target, id);
            break;
        case "edit-entry":
            makeEditableEntry(e.target, id);
            break;
        case "save-entry":
            saveEntry(e.target, id);
            break;
        case "edit-category":
            makeEditableCategory(e.target, id);
            break;
        case "save-category":
            saveCategory(e.target, id);
			default:
            break;
        }
    };

    return {

        Load: function(){
            // initial load
            //indexRequest(true);

            // set event handle for clicks in the web part
            Listen("click", handleClick, adminSectionElem());
			Listen("click", handleClick, blogSectionElem());
        }
    };

}();