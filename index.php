<!DOCTYPE html>

<html>
<head>
  <title>List Products</title>
  <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.40/css/uikit.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.40/js/uikit.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.40/js/uikit-icons.min.js"></script>

</head>



<body>

  <div class="uk-alert-success uk-margin-medium-left uk-margin-medium-right" uk-alert>
  <a class="uk-alert-close" uk-close></a>

  <p>Unfulfilled Items - Last Sync: July6 1:58PM Chinese time</p>
  <table class="uk-table uk-table-small uk-table-striped uk-table-divider uk-margin-small uk-margin-small-left uk-text-small" uk-grid="">

<tr>
    <td>Order</td>
    <td>Created_at</td>
    <td>Product Name</td>
    <td>Variant</td>
    <td>SKU</td>
    <td>Quantity</td>
    <td>Note</td>
</tr>

<?php

class MySqli_DB {

	private $con;
	public $query_id;

	public function db_connect()
	{
		$this->con = mysqli_connect("127.0.0.1","test","nr4oAX_F5e4jpif5d");
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


//https://delladirect.myshopify.com/admin/orders.json?limit=250&selectedView=savedSearch&status=open&fulfillment_status=unfulfilled&order=processed_at%20asc&processed_at_min=2021-01-01&processed_at_max=2021-06-24

//$url = "https://2690fb974069f84abb68de4c26972018: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@she-sho-wholesale.myshopify.com/admin/orders.json";
$db = new MySqli_DB();
$db->db_connect();

$Lineitems = [];
$created_at = "";
$title = "";
$variant = "";
$sku = "";
$quantity = 0;
$skuArray=[];

$query_orders = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'unfulfilled'";
$tables = $db->query($query_orders);
foreach ($tables as $table) {
	$tableName = $table["table_name"];
	//$query_arribute = "SELECT created_at, title, variant, sku, quantity FROM " . $tableName;
	$result = $db->query("SELECT created_at, title, variant, sku, quantity FROM " . $tableName);
	//print_r($orderNumber);
	foreach ($result as $row) {
		echo "<tr>
		<td>". $tableName . "</td>
		<td>". $row["created_at"] . "</td>
		<td>". $row["title"] . "</td>
		<td>". $row["variant"] . "</td>
		<td>". $row["sku"] . "</td>
		<td>". $row["quantity"] . "</td>
		</tr>";
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

$db->db_disconnect();

?>

</table>




</body>
</html>