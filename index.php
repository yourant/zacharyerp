<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
        }

        .gridArea {
            padding: 10px;
        }
    </style>

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
    $result = $db->query("SELECT created_at, title, variant, sku, quantity, note FROM " . $tableName);
    foreach($result as $row)
    {
     /*$sub_array = array();
     $sub_array[] = $tableName;
     $sub_array[] = $row['created_at'];
     $sub_array[] = $row['title'];
     $sub_array[] = $row['variant'];
     $sub_array[] = $row['sku'];
     $tabledata[] = $sub_array;*/
    array_push($tabledata,array('table_name' => $tableName, 'created_at' => $row["created_at"], 'title' => $row["title"], 'variant' => $row["variant"], 'sku' => $row["sku"], 'quantity' => $row["quantity"], 'note' => $row["note"]));
    }
    //array_push($tabledata,array($tableName, $row["created_at"], $row["title"], $row["variant"], $row["sku"]));
    }
    /*while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>". $orderNumber . "</td>
        <td>". $row["created_at"] . "</td>
        <td>". $row["title"] . "</td>
        <td>". $row["variant"] . "</td>
        <td>". $row["sku"] . "</td>
        <td>". $row["quantity"] . "</td>
        </tr>";
    }*/
//--------------------------------------------------------------------------------


$output = array(
    'data' => $tabledata
);

//echo json_encode($output);

?>

</head>

<body>
    <div class="gridArea">
        <table id="myGrid" class="nowrap table table-striped table-bordered table-hover table-condensed" cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Created_at</th>
                    <th>Name</th>
                    <th>variant</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Note</th>
                </tr>
            </thead>

        </table>
    </div>


    <script src="jquery/jquery-1.12.3.min.js"></script>
    <script src="datatables/js/jquery.dataTables.min.js"></script>
    <script src="datatables/js/dataTables.bootstrap.min.js"></script>
    <!--<script src="index.js"></script>-->

<script type="text/javascript" language="javascript" >
function createCombox(data) {
    var _html = '<select style="width:100%;">';
    data.forEach(function (ele, index) {
        _html += '<option>' + ele + '</option>';
    });
    _html += '</select>';
    return _html;
}

$(function () {
    var editTableObj;
    var comboData = {
        "2": ["???", "???"],
        "3": ["ManyToOne", "OneToMany", "???"],
        "4": ["String", "Long", "Integer", "Boolean", "Date", "????????????"]
    };
    var setting = {
        columns: [
            { "data": "table_name" },
            { "data": "created_at" },
            { "data": "title" },
            { "data": "variant" },
            { "data": "sku" },
            { "data": "quantity" },
            { "data": "note" }
        ],
        columnDefs: [{
            "targets": [6],
            createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                $(cell).click(function () {
                    $(this).html('<input type="text" size="16" style="width: 100%"/>');
                    var aInput = $(this).find(":input");
                    aInput.focus().val(cellData);
                });
                $(cell).on("blur", ":input", function () {
                    var text = $(this).val();
                    $(cell).html(text);
                    editTableObj.cell(cell).data(text);
                })
            }
        }, {
            "targets": [],
            createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                var aInput;
                $(cell).click(function () {
                    $(this).html(createCombox(comboData[colIndex]));
                    var aInput = $(this).find(":input");
                    aInput.focus().val("");
                });
                $(cell).on("click", ":input", function (e) {
                    e.stopPropagation();
                });
                $(cell).on("change", ":input", function () {
                    $(this).blur();
                });
                $(cell).on("blur", ":input", function () {
                    var text = $(this).find("option:selected").text();
                    editTableObj.cell(cell).data(text)
                });
            }
        }],
        data: <?php echo json_encode($tabledata); ?>,
        ordering: false,
        paging: false,
        info: false,
        searching: false,
    };
    editTableObj = $("#myGrid").DataTable(setting);
});
</script>

</body>

</html>