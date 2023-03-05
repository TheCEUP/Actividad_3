<?php
    header('Content-Type: text/html; charset=ISO-8859-15');
    date_default_timezone_set('America/Los_Angeles');
    
    if(isset($_POST['action']))
    {
        $dir = dirname(__FILE__) . '/';
        $filename = isset($_POST['key']) && $_POST['key'] != "" ? $_POST['key'] . ".txt" : "Untitled.txt";
        $file = dirname(__FILE__) . '/' . $filename;
        
        switch ($_POST['action']) {
          case "get":
              $files = glob($dir . "*.txt");
              $file_list = "";
              foreach ($files as $file) {
                  $filename = basename($file, ".txt");
                  $file_list .= "<option value='$filename'>$filename</option>";
              }
              if ($file_list == "") {
                  $notes = "No notes";
              } else {
                  $notes = "<select id='filename'>$file_list</select>";
                  $notes .= "<button id='load_file'>Load File</button>";
              }
              echo $notes;
              break;
  
          case "load":
              $filename = $_POST['filename'] . ".txt";
              $file = $dir . $filename;
              if (file_exists($file)) {
                  $notes = file_get_contents($file);
                  echo utf8_decode($notes);
              } else {
                  echo "File not found.";
              }
              break;
  
          case "save":
              if (isset($_POST['content'])) {
                  $fh = fopen($file, 'w');
                  fwrite($fh, $_POST['content']);
                  fclose($fh);
  
                  echo '» ' . Date('g:i:s A');
              }
              break;
      }
  }
  else
      echo "not allowed";
?>