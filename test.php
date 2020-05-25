<?php
  $host_name = 'db720659564.db.1and1.com';
  $database = 'db720659564';
  $user_name = 'dbo720659564';
  $password = 'sNSssK@4k4EiR9u';
  $connect = mysqli_connect($host_name, $user_name, $password, $database);

  if (mysqli_connect_errno()) {
    die('<p>Failed to connect to MySQL: '.mysqli_connect_error().'</p>');
  } else {
    echo '<p>Connection to MySQL server successfully established.</p >';
  }

$sql = "CREATE TABLE Brackets (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
json TEXT NOT NULL
)";

if (mysqli_query($connect, $sql)) {
  echo "Table MyGuests created successfully";
} else {
  echo "Error creating table: " . mysqli_error($connect);
}

mysqli_close($connect);
?>