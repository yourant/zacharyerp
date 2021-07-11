<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Document</title>
    <!--<link href="bootstrap/css/bootstrap.css" rel="stylesheet" />
    <link href="datatables/css/dataTables.bootstrap.min.css" rel="stylesheet" />-->
      <script src="jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>
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
</head>

<body>
    <div class="gridArea">
        <table id="myGrid" class="nowrap table table-striped table-bordered table-hover table-condensed" cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th>显示名称</th>
                    <th>属性名称</th>
                    <th>可为空</th>
                    <th>关联关系</th>
                    <th>属性类型</th>
                </tr>
            </thead>

        </table>
    </div>


    <!--<script src="jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="datatables/js/dataTables.bootstrap.min.js"></script>
    <script src="index.js"></script>-->
</body>

</html>


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
        "2": ["是", "否"],
        "3": ["ManyToOne", "OneToMany", "无"],
        "4": ["String", "Long", "Integer", "Boolean", "Date", "当前实体"]
    };
    var setting = {
        columns: [
            { "data": "display" },
            { "data": "name" },
            { "data": "nullable" },
            { "data": "relation" },
            { "data": "type" }
        ],
        columnDefs: [{
            "targets": [0, 1],
            createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                $(cell).click(function () {
                    $(this).html('<input type="text" size="16" style="width: 100%"/>');
                    var aInput = $(this).find(":input");
                    aInput.focus().val(cellData);
                });
                $(cell).on("blur", ":input", function () {
                    var text = $(this).val();
                    $(cell).html(text);
                    editTableObj.cell(cell).data(text)
                })
            }
        }, {
            "targets": [2, 3, 4],
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
        ordering: false,
        paging: false,
        info: false,
        searching: false,
        processing: true,
        serverSide: true,
        ajax : {
            url:"TableData.php",
            type:"POST",
            async: true,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Error，Try again！");
                console.log(errorThrown);
            },
            success: function (data) {
                var jsonRete = eval(data);
                for (var i in jsonRete) {
                    if (jsonRete[i].ExchangeRate == 100)
                    {
                        //表格4显示
                        refresh_exchangeRate.html("Done!");
                        //表格5为操作删除，完成时变成灰色，不可点击
                        refresh_delete.html("<a><i class='fa fa-trash-o ' style='color:#888;' ></i></a>");
                    }
                    else {
                        refresh_exchangeRate.html(jsonRete[i].ExchangeRate);
                    }
                }
            }
        }
    };
    editTableObj = $("#myGrid").DataTable(setting);
    console.log("12345");
});
</script>