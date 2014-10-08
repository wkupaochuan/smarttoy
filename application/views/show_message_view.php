<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
  <title>消息</title>
</head>
<!-- Javascript goes in the document HEAD -->
<script type="text/javascript">
    function altRows(id){
        if(document.getElementsByTagName){

            var table = document.getElementById(id);
            var rows = table.getElementsByTagName("tr");

            for(i = 0; i < rows.length; i++){
                if(i % 2 == 0){
                    rows[i].className = "evenrowcolor";
                }else{
                    rows[i].className = "oddrowcolor";
                }
            }
        }
    }

    window.onload=function(){
        altRows('alternatecolor');
    }
</script>


<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
    table.altrowstable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 1px;
        border-color: #a9c6c9;
        border-collapse: collapse;
    }
    table.altrowstable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #a9c6c9;
    }
    table.altrowstable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #a9c6c9;
    }
    .oddrowcolor{
        background-color:#d4e3e5;
    }
    .evenrowcolor{
        background-color:#c3dde0;
    }
</style>
<body>
    <h1>所有接收到的消息</h1>

<table class="altrowstable" id="alternatecolor">
    <?php foreach ($messages as $v): ?>
        <tr>
            <td><?php echo $v['from_user']?></td>
            <td><?php echo $v['to_user']?></td>
            <td><?php echo $v['content']?></td>
            <td><?php echo $v['created_time']?></td>
        </tr>

    <?php endforeach ?>
</table>

    <strong>&copy; 2011</strong>
</body>
</html>

