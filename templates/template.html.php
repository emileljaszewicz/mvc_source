<!DOCTYPE html>
<html>
<head>
    <title>Editable</title>
    <meta charset="utf-8">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="vendor/wenzhixin/bootstrap-table/dist/bootstrap-table.css"></script>
    <link rel="stylesheet" href="library/bootstrap3-editable/css/bootstrap-editable.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.js"></script>
    <script src="library/bootstrap3-editable/js/bootstrap-editable.js"></script>
    <script src="vendor/wenzhixin/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.js"></script>
</head>
<body>
<table id="table"></table>
</div>
<div class="container">
    <h1>Editable</h1>
    <table id="user_id"
           data-toggle="table"
           data-pagination="true"
           data-show-export="true"
           data-url="index.php?task=test&action=getUsers">
        <thead>
        <tr>
            <th data-field="user_id">ID</th>
            <th data-field="name" data-editable="true">Item Name</th>
        </tr>
        </thead>
    </table>
</div>
<script>

    var $table = $('#table');
    $(function () {
        $table.bootstrapTable({
            idField: 'name',
            url: 'index.php?task=test&action=getUsers',
            type: "POST",
            columns: [ {
                field: 'name',
                title: 'names',
                sortable: true,
                editable: {
                    type: 'select',
                    source: [
                        {value: 'Adam', text: 'Active'},
                        {value: 'Emil', text: 'Blocked'},
                    ]
                }
            }],

        });
    });
    console.log(data)
</script>
</body>
</html>