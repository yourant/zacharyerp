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
	    quantity INT
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
$skuArray = [];
$table_array = [];

$query_orders = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'unfulfilled'";
$tables = $db->query($query_orders);
foreach ($tables as $table) {
	$tableName = $table["table_name"];
	//$query_arribute = "SELECT created_at, title, variant, sku, quantity FROM " . $tableName;
	$result = $db->query("SELECT created_at, title, variant, sku, quantity FROM " . $tableName);
	//print_r($orderNumber);
	foreach ($result as $row) {
		array_push($table_array,array('order' => $tableName, 'created_at' => $row["created_at"], 'title' => $row["title"], 'variant' => $row["variant"], 'sku' => $row["sku"], 'quantity' => $row["quantity"], 'note' => $row["note"]));
		/*echo "<tr>
		<td>". $tableName . "</td>
		<td>". $row["created_at"] . "</td>
		<td>". $row["title"] . "</td>
		<td>". $row["variant"] . "</td>
		<td>". $row["sku"] . "</td>
		<td>". $row["quantity"] . "</td>
		</tr>";*/
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
 


<html>
 <head>
  <title>How to use Tabledit plugin with jQuery Datatable in PHP Ajax</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>

 </head>
 <body>
  <div class="container">
   <h3 align="center">How to use Tabledit plugin with jQuery Datatable in PHP Ajax</h3>
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">Sample Data</div>
    <div class="panel-body">
     <div class="table-responsive">
      <table id="sample_data" class="table table-bordered table-striped display nowrap">
       <thead>
        <tr>
         <th>ID</th>
         <th>First Name</th>
         <th>Last Name</th>
        </tr>
       </thead>
       <tbody></tbody>
      </table>
     </div>
    </div>
   </div>
  </div>
  <br />
  <br />
 </body>
</html>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){

 var dataTable = $('#sample_data').DataTable({
  "processing" : true,
  "serverSide" : true,
   scrollY: "200px",
   scrollCollapse: true,
   paging: false,
  "order" : [],
  "ajax" : {
   url:"fetch.php",
   type:"POST"
  }
 });

 $('#sample_data').on('draw.dt', function(){
  $('#sample_data').Tabledit({
   url:'action.php',
   dataType:'json',
   columns:{
    identifier : [0, 'id'],
    editable:[[1, 'first_name'], [2, 'last_name']]
   },
   restoreButton:false,
   onSuccess:function(data, textStatus, jqXHR)
   {
    if(data.action == 'delete')
    {
     $('#' + data.id).remove();
     $('#sample_data').DataTable().ajax.reload();
    }
   }
  });
 });
  
}); 
</script>