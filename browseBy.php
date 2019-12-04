<?php
session_start();
include('mysqli_connect.php');

$query = "select distinct catName from categories";
$rows = mysqli_query($dbc, $query);
echo "<form action='index.php' method='POST'>";
$count = 0;
while ($row=mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
	$catName = $row['catName'];
	if($count % 2 == 0) {
		echo "<tr>";
	}
?>
	<td>
		<input type='checkbox' name='category[]' value='<?php echo "$catName"?>' id='<?php echo "$catName"?>'>
		<label for='<?phpecho "$catName"?>'><?php echo "$catName"?></label>
	</td>
<?php
	if($count%2==1){
		echo "</tr>";
	}
	$count++;
}
if($count%2==1){
		echo "</tr>";
}

?>