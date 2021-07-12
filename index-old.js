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
        "processing": true,
        "serverSide": true,
        "ajax" : {
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