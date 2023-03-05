<!DOCTYPE html>
<html>
<head>
    <title>Chiguiritos Notepad</title>
    <script type="text/javascript" src="./assets/js/jquery-1.4.4.min.js"></script>
    <style type="text/css">
        a {
            text-decoration: none;
        }
        body {
            padding: 0 6px 0 0;
            margin: 0 auto;
            margin-top: 6px;
            max-width: 600px;
            max-height: 600px;
            font-family: arial;
            background-attachment: fixed;
            background-image: url(./assets/img/bg.jpg);
            background-size: cover;
            background-repeat: no-repeat;
        }
        textarea {
            width: 100%;
            height: 100%;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px #ccc;
            padding: 20px;
            font-size:20px;
            margin-bottom: 5px;
        }
        #links {
            float: right;
            font-size: 12px;
            color: #ccc;
            background-color: white;
            padding: 20px;
        }
        #links:hover {
            background-color: #ccc;
            color: white;
        }
    </style>
</head>
<body>
    <h1 style="color: white">Chiguiritos Notepad</h1>
    <textarea id="notes"></textarea>
    <div id="links"><a href="#" id="reload">Reload</a> &bull; <a href="#" id="save">Save As</a> &bull; <button id="open">Open</button></div>
    <div id="timestamp"></div>
    <div id="file_list"></div>

    <script>
        var key = "";
        <?php
            if(isset($_GET['key']))
                echo "key='" . $_GET['key'] . "';";
        ?>

        var prevContent = "";
        function getNotes() {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: "action=get&key=" + key,
                contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
                cache: false,
                success: function(message) {
                    $("#notes").empty().append(message);
                    prevContent = message;
                }
            });
        }

        function autosave(force) {
            var t = setTimeout("autosave()", 5000);
            var content = $("#notes").val();
            if (content != prevContent || force) {
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: "action=save&key="+key+"&content=" + encodeURIComponent(content),
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
                    cache: false,
                    success: function(message) {
                        $("#timestamp").empty().append(message);
                    }
                });
            }

            prevContent = content;
        }

        $(document).ready(function() {
            $("#notes").height($(document).height()-50)
            getNotes();
            setTimeout("autosave()", 5000);
            $("#reload").click(function() {
                getNotes();
            });
            $("#save").click(function() {
                var fileName = prompt("Please enter file name:", "Untitled");
                if (fileName != null) {
                    key = fileName;
                    autosave(true);
                }
            });
            $("#open").click(function() {
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: "action=get",
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
                    cache: false,
                    success: function(message) {
                        $("#file_list").empty().append(message);
                    }
                });
            });
            $(document).on("click", "#load_file", function() {
                var selected_file = $("#filename").val();
                if (selected_file != "") {
                    key = selected_file;
                    getNotes();
                }
                $("#file_list").empty();
            });
        });
    </script>
</body>
</html>
