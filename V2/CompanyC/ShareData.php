<!DOCTYPE html>

<?php
include 'ShareDataFunctions.php';
//database credentials
$server_Name = "localhost";
$database_Name = "company_c";
$username = "Kyriakos";
$password = "12345";

?>

<html>
	<head>
		<meta charset="utf-8">
		<title>My Testing Side</title>
		<link rel="stylesheet" type="text/css" href="css/sharedata.css">
	</head>
<body>
	<h1>Data Transfer Center</h1>
	<p>Go to main website: <a href="Index.php">Home</a></p>
	<p>Go to data input website: <a href="DataInput.php">Data Input</a></p>
	<form method="POST" >
	<h3>Select Company to transfer data:    
	<select name="company" >
    <option value="A">Company A</option>
    <option value="B">Company B</option>
    <option value="D">Company D</option>
	<option value="E">Company E</option>
	<option value="F">Company F</option>
  </select></h3>
	<h3>Select Data to be transferred:</h3>
<?php
	try 
	{
		 $conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
		 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 $sql = "SELECT email FROM records";
		 echo "<table>";
		 echo "<caption><strong>Data Retrieved</strong></caption>";
		 echo "<thead>
					<tr>
						<th>No</th>
						<th>Email</th>
						<th>Select</th>
					</tr>
			</thead>";
		echo "<tbody>";
		$row_count=1;
		 foreach($result=$conn->query($sql) as $row)
		 {
			if(!$result)
				die("No data in the database");
			else
			{
				echo "<tr> 
						<td>".$row_count."</td>
						<td>".$row["email"]."</td>
						<td class=\"opt1\"><input type=\"checkbox\" id=\"".$row_count."\" name=\"cbox_selected\" value=\"".$row["email"]."\"></td>
					</tr>";
				$row_count++;
			}
		 }
		 echo "</tbody>";
		 echo "</table>";
	}
		catch(PDOException $e) {
			 echo "Error: " . $e->getMessage();
		}
	$conn = null;
?> 
<br>
<input type="button" onclick="Check_All()" value="Check All">
<input type="button" onclick="Uncheck_All()" value="Uncheck All">
<br><br>
<input type="button" onclick="Submit_Selected()" value="Verify Selected">
<br>
<textarea rows="4" cols="40" name="selected_emails" id="selected_emails" readonly>No emails Selected</textarea>
<input type="submit" name="submit" value="Submit">
</form>
	<script type="text/javascript">
		function Submit_Selected() 
		{
			var cbox_selected = document.forms[0];
			var selected=new Array();
			var i;
			var index=0;
			var txt = "";
			for (i = 0; i < cbox_selected.length; i++) {
				if (cbox_selected[i].checked) {
					txt=txt +cbox_selected[i].value +"|" + "\n";
					selected[index] = cbox_selected[i].value;
					index++;
				}
			}
			if(index==0)
			{
				document.getElementById("selected_emails").value="No emails Selected";
			}
			else
			{
				document.getElementById("selected_emails").value = txt;
			}	
		}
		function Check_All()
		{
			var cbox_selected = document.forms[0];
			var i;
			var index=1;
			for (i = 0; i < cbox_selected.length; i++) 
			{
				document.getElementById(index).checked = true;
				index++;
			}
		}
		function Uncheck_All()
		{
			var cbox_selected = document.forms[0];
			var i;
			var index=1;
			for (i = 0; i < cbox_selected.length; i++) 
			{
				document.getElementById(index).checked = false;
				index++;
			}
		}
	</script>
<?php
	if($_POST['submit'] and $_SERVER['REQUEST_METHOD'] == "POST")
	{
		$mySelf="C";
		$company=$_POST['company'];
		$selected_emails=$_POST['selected_emails'];
		if($selected_emails=="No emails Selected")
		{
			die("You have not select any data to be transferred");
		}
		else
		{
			SelectDataToBeTransfer($server_Name,$username,$password,$database_Name,$selected_emails,$company,$mySelf);
		}
	}
?>
</body>
</html>