<?php

print_r("TableData.php");

class MySqli_DB {

	private $con;
	public $query_id;

	public function db_connect()
	{
		$this->con = mysqli_connect("127.0.0.1","erp","wdufdix39cks_od83kld");
		if(!$this->con)
		{
			die(" Database connection failed:". mysqli_connect_error());
		} else {
			$select_db = $this->con->select_db('unfulfilled');
	 		if(!$select_db){
				die("Failed to Select Database". mysqli_connect_error());
			}
		}
	}

	public function query($sql)
   {

		if (trim($sql != "")) {
			$this->query_id = $this->con->query($sql);
		}
        // only for Develope mode
       // For production mode
        //  die("Error on Query");
       return $this->query_id;

   }

	public function createTable($DBorder)
	{
		$sql = "CREATE TABLE order". $DBorder ." (
	    created_at VARCHAR(30),
	    title VARCHAR(200),
	    variant VARCHAR(30),
	    sku VARCHAR(40),
	    quantity INT,
	    note VARCHAR(10000)
		)";
		if(mysqli_query($this->con, $sql)){
		    echo "order". $DBorder ."Table created successfully.";
		} else{
		    echo "ERROR: Could not able to execute $sql. ";
		}
	}

	public function updateTable($DBorder,$DBcreated_at,$DBtitle,$DBvariant,$DBsku,$DBquantity)
	{

	}

	public function db_disconnect()
	{
		if(isset($this->con))
		{
		mysqli_close($this->con);
		unset($this->con);
		}
	}
}
print_r("0");

$db = new MySqli_DB();
$db->db_connect();

$Lineitems = [];
$created_at = "";
$title = "";
$variant = "";
$sku = "";
$quantity = 0;
$skuArray=[];
$tabledata = array();

$query_orders = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'unfulfilled'";
$tables = $db->query($query_orders);
foreach ($tables as $table) {
	$tableName = $table["table_name"];
	//$query_arribute = "SELECT created_at, title, variant, sku, quantity FROM " . $tableName;
	$result = $db->query("SELECT created_at, title, variant, sku, quantity FROM " . $tableName);
	//print_r($orderNumber);
	foreach($result as $row)
	{
	 $sub_array = array();
	 $sub_array[] = $tableName;
	 $sub_array[] = $row['created_at'];
	 $sub_array[] = $row['title'];
	 $sub_array[] = $row['variant'];
	 $sub_array[] = $row['sku'];
	 $tabledata[] = $sub_array;
	//array_push($tabledata,array('display' => $tableName, 'name' => $row["created_at"], 'nullable' => $row["title"], 'relation' => $row["variant"], 'type' => $row["sku"]));
	}
	//array_push($tabledata,array($tableName, $row["created_at"], $row["title"], $row["variant"], $row["sku"]));
	}
	/*while($row = $result->fetch_assoc()) {
		print_r("3");
		echo "<tr>
		<td>". $orderNumber . "</td>
		<td>". $row["created_at"] . "</td>
		<td>". $row["title"] . "</td>
		<td>". $row["variant"] . "</td>
		<td>". $row["sku"] . "</td>
		<td>". $row["quantity"] . "</td>
		</tr>";
	}*/
}
print_r("1");
//--------------------------------------------------------------------------------


$output = array(
	'data' => $sub_array
);

echo json_encode($output);

?>
