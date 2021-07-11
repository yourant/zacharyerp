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

  <p>Datebase Update - Last Update: July6 1:58PM Chinese time</p>
  <table class="uk-table uk-table-small uk-table-striped uk-table-divider uk-margin-small uk-margin-small-left uk-text-small" uk-grid="">

<?php

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

print_r("123");
//https://delladirect.myshopify.com/admin/orders.json?limit=250&selectedView=savedSearch&status=open&fulfillment_status=unfulfilled&order=processed_at%20asc&processed_at_min=2021-01-01&processed_at_max=2021-06-24

//$url = "https://2690fb974069f84abb68de4c26972018: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@she-sho-wholesale.myshopify.com/admin/orders.json";
$db = new MySqli_DB();
$db->db_connect();

$url = "https://zacharyerp.com/orders.json";

$products_content = @file_get_contents($url);
$orders_json = json_decode($products_content, true);
$orders = $orders_json['orders'];
$Lineitems = [];
$orderNumber = "";
$created_at = "";
$title = "";
$variant = "";
$sku = "";
$quantity = 0;
$skuArray=[];


foreach($orders as $order){
	$Lineitems = $order['line_items'];
	$orderNumber = $order['order_number'];
	$created_at = $order['created_at'];
	$val = $db->query("SELECT 1 FROM order" .$orderNumber. " LIMIT 1");
	if($val == FALSE){
		$db->createTable($orderNumber);
	}
	$skuArray=[];
	foreach ($Lineitems as $item) {
		$quantity = $item['fulfillable_quantity'];
		if ($quantity>0){
		    $title = $item['title'];
		    $variant = $item['variant_title'];
		    $sku=$item['sku'];
		    if (in_array($sku, $skuArray)){
		    	$db->query("UPDATE order". $orderNumber ." SET quantity=quantity+". $quantity);
		    	print_r("duplicate");
		    	//$quantity=$item['quantity']-$item["fulfillable_quantity"];
		    }else{
		    	array_push($skuArray, $sku);
				$result = $db->query("SELECT sku FROM order" . $orderNumber . " WHERE sku = '". $sku ."'");
				if($result->num_rows == 0) {
					//print_r("Insert Data");
				    $db->query("INSERT INTO order". $orderNumber ." (created_at, title, variant, sku, quantity) VALUES ('". $created_at ."', '". $title ."', '". $variant ."', '". $sku ."', ". $quantity .")");
				} else {
					//print_r("Update Data");
				    $db->query("UPDATE order". $orderNumber ." SET quantity=". $quantity ." WHERE sku=". $sku);
				}
		    }
		}
	}
}
print_r("123");

?>

</table>




</body>
</html>