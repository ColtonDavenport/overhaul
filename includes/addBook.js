$(document).ready(function(){
	


	$("#addPublisher").click(function(){
		var newPubDiv = document.getElementById("newPublisher");
		if(newPubDiv.innerHTML.length < 1) {
			var pubDiv = document.getElementById("publishers");
			newPubDiv.innerHTML = '<label>Publisher Name: <input type="text" name ="pubName" maxLength="50" required></label>   <label>Publisher Description: <textarea name ="pubDesc" maxLength ="50" required></textarea>';
			pubDiv.style.display = "none";
		}
		if(false) {
			newPubDiv.innerHTML = '';
			pubDiv.style.display = "table";
		}
	});
	$("#clearPublisher").click(function(){
		var newPubDiv = document.getElementById("newPublisher");
		var pubDiv = document.getElementById("publishers");
		newPubDiv.innerHTML = '';
		pubDiv.style.display = "table";
	});

	
	
	var newAuthorCount = 0;	
	
	$("#addAuthor").click(function(){
		var changeEl = document.getElementById("newAuthors");
		changeEl.innerHTML += "<label> First Name: <input type = 'text' name ='newAuthors["+newAuthorCount+"][first]' maxLength='20' required /> </label>";
		changeEl.innerHTML += "<label> Last Name: <input type = 'text' name ='newAuthors["+newAuthorCount+"][last]' maxLength='20' required /> </label>";
		changeEl.innerHTML += "<label> Bio: <textarea name ='newAuthors["+newAuthorCount+"][bio]' maxLength='200' required ></textarea> </label>";
		changeEl.innerHTML += "<br>";
		
		newAuthorCount += 1;
		
	});
	$("#clearAuthors").click(function(){
		var changeEl = document.getElementById("newAuthors");
		changeEl.innerHTML =  "";
		newAuthorCount = 0;
	});
	
	
	$("#addCategory").click(function(){
		var changeEl = document.getElementById("newCategories");
		if (changeEl.innerHTML.length > 0) {
			changeEl.innerHTML += "<br>";
		}
		changeEl.innerHTML +=  "<label> Category Name: <input type='text' name='newCatNames[]' maxLength='20' required > </label>";
	});
	$("#clearCategories").click(function(){
		var changeEl = document.getElementById("newCategories");
		changeEl.innerHTML =  "";
	});	
});

/*

function addNewAuthor(elId){
	var changeEl = document.getElementById(elId);
	changeEl.innerHTML += "<label> First Name: <input type = 'text' name = 'authorFirstName[]' maxLength='20' required /> </label>";
	changeEl.innerHTML += "<label> Last Name: <input type = 'text' name = 'authorLastName[]' maxLength='20' required /> </label>";
	changeEl.innerHTML += "<label> Bio: <textarea name = 'newAuthFirstName[]' maxLength='20' required ></textarea> </label>";
	changeEl.innerHTML += "<br>";
};

function clearDiv(divId) {
	document.getElementById(divId).innerHTML = "";
	
};*/