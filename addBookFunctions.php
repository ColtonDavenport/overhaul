<?php

/**
	displaySelction
	Called to make a query of $table and display results
**/
function displaySelection($dbc, $table, $cols, $type){
// build the query
$query = 'SELECT '.$cols[0].','.$cols[1].' FROM '.$table.' ORDER BY '.$cols[1];
if($r = mysqli_query($dbc, $query)) {
	$checkbox = ($type == 'checkbox');
	$name = $table;
	if($checkbox) {
		// if printing checkboxes make their name an array
		 $name = $name.'[]'; 
	}
	$start = '<td><label><input type="'.$type.'" name="'.$name.'" value="';
	$end = '</label></td>';
	echo '<table id='.$table.'><tr>';
	$count = 0;
	while($row = mysqli_fetch_array($r)){
		echo $start.$row[$cols[0]].'"';
		if (isset($_POST[$table])){
			if( ($checkbox  && in_array($row[$cols[0]], $_POST[$table])) || ($_POST[$table] == $row[$cols[0]])){
					echo ' checked';
			}
		}
		echo '>'.$row[$cols[1]].$end;
		$count++;
		if($count % 5 == 0) {
			echo '</tr><tr>';
		}
	} // end while	
	echo "</tr></table>";
}else{
	echo "error in displaying publishers";
}
}

function searchAuthors($dbc){
$auth_query = "SELECT authorId, authorFirstName, authorLastName, bio FROM authors ORDER BY authorLastName, authorFirstName";

if($r = mysqli_query($dbc, $auth_query)){
	$authCheckStart ='<td><label><input class="author" type="checkbox" name="authors[]"  value="';
	$authCheckEnd ='</label></td>';
	$count = 0;
	echo '<table id="authorsTable" required><tr>';
	while($row = mysqli_fetch_array($r)){
		echo $authCheckStart.$row['authorId'].'"';
		if(isset($_POST['authors']) && in_array($row['authorId'], $_POST['authors'])){
			echo " checked";
		}
		echo '>'.$row['authorLastName'].", ".$row['authorFirstName'].$authCheckEnd;
		echo '<td>'.$row['bio'].'</td>';
		$author_book_query = "SELECT title FROM books INNER JOIN book_authors USING (bookId) INNER JOIN authors USING (authorId) WHERE authorId = ".$row['authorId'];
		if($b = mysqli_query($dbc,$author_book_query)) {
			$book = mysqli_fetch_array($b);
			echo "<td>Wrote: ".$book['title']."</td>";
		}
		echo "</tr>";
	}
	echo '</table>';
}	
	
}

function sticky($field){
	if(isset($_POST[$field])){
		echo htmlspecialchars($_POST[$field]);
	}
}

function babySafe($input){
	return addslashes(trim(htmlspecialchars($input)));
}

function camelBabySafe($input){
	return addslashes(ucwords(strtolower(trim(htmlspecialchars($input)))));
}


function uploadImage($dbc, $imageName) {
	$error_code=$_FILES['user_file']['error'];
	if($error_code){
		$list_error=array(1=>'File size exceeds the maximum allowed',
			2=>'File size exceeds the maximum allowed',
			3=>'File only partially  uploaded',
			4=>'No file was uploaded',
			6=>'Temporary folder not found',
			7=>'Failed to write file to disk'
			);
		echo'ERROR: '.$list_error[$_FILES['user_file']['error']];
	}else{
		if(is_uploaded_file($_FILES['user_file']['tmp_name'])){
		// VALIDATION
		$size=$_FILES['user_file']['size'];
		$type=$_FILES['user_file']['type'];
		$mime=array('image/jpeg','image/jpg');
		$error=false;
		if($size>(1024*1024)){// 1 MB
			$error='ERROR: Maximum size allowed is 1 MB';
		}else if(!in_array($type,$mime)){
			$error='ERROR: File typemust be JPG or JPEG';
		}// UPLOAD
		else{
			$tmp_name=$_FILES['user_file']['tmp_name'];
			$new_file="images/$imageName";
			if(move_uploaded_file($tmp_name,$new_file)){
				echo'<br>File successfully uploaded';
			}else{
				$error='File cannot be uploaded, please try again later';
			}
		}// IFERROR
		if($error){
			echo$error;
		}
	}else{
	echo'No file uploaded';
	}
	}	
}


function sendToPost($dbc){
	
	// check that one of the two publisher methods was entered
	if(!isset($_POST['publishers']) && !isset($_POST['pubName'])){
		return;
	}
	
	
	echo " Book data posted ";
	
	
	
	// first handle the publisher
	/***
	Publisher
		* 
		*  1. determine wether publisher is selected or defined - 
		*  2. Process
		*  3. set $pubId 
		
		* Queries - 
		* 	INSERT INTO publishers (pubName, pubDesc) VALUES ('$pubName', '$pubDesc')	
	***/
	if(isset($_POST['pubName'])){
		
		
		
		$pubName = camelBabySafe($_POST['pubName']);
		$pubDesc = babySafe($_POST['pubDesc']);
		$insert_publisher = "INSERT INTO publishers (pubName, pubDesc) VALUES ";
		
		if(mysqli_query($dbc, $insert_publisher."('$pubName', '$pubDesc')")){
			$pubId = $dbc->insert_id;
			echo "<br>New Publisher created successfully. Last inserted ID is: " . $pubId;
			
		} else {
			echo "<br>Error: " . $insert_publisher . "('$pubName', '$pubDesc')" . "<br>" . $dbc->error;
		}
	} else {
		// No new publisher has to be created
		$pubId = $_POST['publishers'];
	}
	
	
	// get the value for edition, which can be null
	if(isset($_POST['edition'])) {
		$edition = camelBabySafe($_POST['edition']);
	} else {
		$edition = "";
	}
	
	// get the rest of the book values
	$title = camelBabySafe($_POST['title']);
	$price = htmlspecialchars($_POST['price']);
	$shortDesc =  babySafe($_POST['shortDesc']);
	$longDesc =  babySafe($_POST['longDesc']);
	$releaseDate = $_POST['releaseDate'];

	// build the query to insert the new book 
	$insert = "INSERT INTO books (title, price, shortDesc, longDesc, pubID, edition, releaseDate) VALUES ";
	$insert .= "('$title', '$price', '$shortDesc', '$longDesc', '$pubId', '$edition', STR_TO_DATE('$releaseDate', '%Y-%m-%d'))";
	
	if( mysqli_query($dbc, $insert) ){
		// insert succeeded
		// get the new book's id
		$bookId = $dbc->insert_id;
		echo "<br>New Book created successfully. Last inserted ID is: " . $bookId;
		
		// build the image name, "bookid_title" with any non alphanumeric characters removed
		$imageName = $bookId.'_'.preg_replace('/[^a-zA-Z0-9]/', '', $title).".jpeg";
		// define the query that will set the book's image value
		$updateQuery = "UPDATE books SET image = '$imageName' WHERE bookId = $bookId";
		
		// upload the image
		uploadImage($dbc, $imageName);
		if( mysqli_query($dbc, $updateQuery) ){
			echo "<br>image path uploaded: ".$imageName;
		} else {
			echo "<br>Error: " . $updateQuery . "<br>" . $dbc->error;
		}		
	} else { // the book insert failed
		echo "<br>Error: " . $insert . "<br>" . $dbc->error;
	}
	
	
	echo "<h3> Categories </h3>";	
	
	/**
		Categories
	**/
	
	
	// insert the book_categories
	// connecting books to their categories
	if(isset($_POST['categories'])) {
		$insert_book_cat = 'INSERT INTO book_categories (bookID, catID) VALUES ';
		foreach($_POST['categories'] as $catId){
			if( mysqli_query($dbc, $insert_book_cat."($bookId, $catId)") ){
				echo "<br> Successfully inserted ($bookId, $catId)";
			} else {
				echo "<br>Error: " . $insert_book_cat . "($bookId, $catId)" . "<br>" . $dbc->error;
			}
		}
	}
	
	
	/***
			New Categories
				* added in addition to old categories 
				* 
				*  1. Check if set
				*  2. Get New Category name (with checking)
				*  3. Create New Category
				*  4. Create new Book_category 
				
				* Queries - 
				*	check if already exists : SELECT catId FROM categories WHERE catName = $newCatName;
				* 	INSERT INTO categories (catName) VALUES ($newCatName);
				* 	INSERT INTO book_categories (bookId, catId) VALUES ($bookId, $newCatId);
	
	***/ 	
	if(isset($_POST['newCatNames'])) {
		echo "In New Cat Names";
		$select_cat = "SELECT catId FROM categories WHERE catName = ";
		$insert_cat = "INSERT INTO categories (catName) VALUES ";
		$insert_book_cat = "INSERT INTO book_categories (bookId, catId) VALUES ";
		
		foreach($_POST['newCatNames'] as $newCatName){
			// clean up the name
			$newCatName = camelBabySafe($newCatName);
			$newCatId = null;
			if($r = mysqli_query($dbc, $select_cat."'$newCatName'")){
				// see if a row was returned
				echo "<br> SELECT query Successfully run"; 
				
				if($row = mysqli_fetch_array($r)){
					// a row was returned,  meaning cat name already existed
					// use that returned id to update the database
					$newCatId = $row['catId'];
					echo "<br>Category already existed. It's ID is: " . $newCatId;
					
				} else {
					// no row returned. Must create the category in the data base
					if(mysqli_query($dbc, $insert_cat."('$newCatName')")){
						echo "<br> Successfully inserted ('$newCatName')";
						//now need new category's id
						$newCatId = $dbc->insert_id;
						echo "<br>New Category created successfully. Last inserted ID is: " . $newCatId;
					} else {
						echo "<br>Error: " . $insert_cat . "'$newCatName'" . "<br>" . $dbc->error . "<br>";
					}
				}			
				
				// now update the book_categories table
				if(mysqli_query($dbc, $insert_book_cat."($bookId, $newCatId)")){
					// the final step for updating/creating a category is completed
					echo "<br> Successfully inserted ($bookId, $newCatId)";
				} else {
					echo "<br>Error: " . $insert_book_cat . "($bookId, $newCatId)" . "<br>" . $dbc->error . "<br>";
				}
			} else {
				// the SELECT query failed
				echo "<br>Error: " . $select_cat . "'$newCatName'" . "<br>" . $dbc->error . "<br>";
			}
		}
	}	
	
	/**
		Authors
	**/
	
 	echo "<h5> Authors </h5>";
	if(isset($_POST['authors'])) {
		$insert_book_auth = 'INSERT INTO book_authors (bookID, authorID) VALUES ';
		foreach($_POST['authors'] as $authorId){
			if( mysqli_query($dbc, $insert_book_auth."($bookId, $authorId)") ){
				echo "<br> Successfully inserted ($bookId, $authorId)";
			} else {
				echo "<br>Error: " . $insert_book_auth . "($bookId, $authorId)" . "<br>" . $dbc->error;
			}
		}
	}
	
	/***
	New Authors
		* added in addition to old authors 
		* 
		*  1. Check if set - 
		*  2. Get info
		*  3. Create New Author
		*  4. update book_author 
		
		* Queries - 
		* 	INSERT INTO authors (authorFirstName, authorLastName, bio) VALUES ('$authorFirstName', $'authorLastName', '$bio')
		* 	INSERT INTO book_authors (bookId, authorId) VALUES ($bookId, $authorId);
	
	***/ 	
	
	if(isset($_POST['newAuthors'])){
		
		$insert_authors = "INSERT INTO authors (authorFirstName, authorLastName, bio) VALUES ";
		$insert_book_authors = "INSERT INTO book_authors (bookId, authorId) VALUES ";
		
		echo "<br> WELCOME ";
		//Walk through every new author
		foreach($_POST['newAuthors'] as $newAuthor){
			echo "<br>    ECHO";
			
			// get new author values
			$authorFirstName = camelBabySafe($newAuthor['first']);
			$authorLastName = camelBabySafe($newAuthor['last']);
			$bio = camelBabySafe($newAuthor['bio']);
			
			// insert the new author
			if(mysqli_query($dbc, $insert_authors."('$authorFirstName', '$authorLastName', '$bio')")){
				//insert successfull, now update book_auths using the new id
				$newAuthorId = $dbc->insert_id;
				echo "<br>Successfully inserted ('$authorFirstName', '$authorLastName', '$bio') <br> New Index: $newAuthorId"; 
				if(mysqli_query($dbc, $insert_book_authors."($bookId, $newAuthorId)")){
					// Update succesful, finished with this new Author
					echo "<br>Successfully inserted ($bookId, $newAuthorId)";
				} else {
				echo "<br>Error: ".$insert_book_authors."($bookId, $newAuthorId)"."<br>".$dbc->error;
			}
				
			} else {
				echo "<br>Error: ".$insert_authors."('$authorFirstName', '$authorLastName', '$bio')"."<br>".$dbc->error;
			}
		}
	}
		// print the good statement
	ECHO "END";
	?>
	<br>
	<a href="View_Products.php">VIEW PRODUCTS </a>
	<?php
	
}

?>