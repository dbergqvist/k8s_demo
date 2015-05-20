<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db_sql = <<<EOT
CREATE DATABASE IF NOT EXISTS guestbook CHARACTER SET utf8;
EOT;

$schema_sql = <<<EOT
CREATE TABLE IF NOT EXISTS mhistory (
  num int NOT NULL,
  PRIMARY KEY(num)
);
EOT;


$migration01 = <<<EOT
CREATE TABLE IF NOT EXISTS messages (
  id INT NOT NULL AUTO_INCREMENT,
  message varchar(256),
  PRIMARY KEY(id)
);
EOT;

// Add any new migrations here.
// $migration02 = ...
// $migration03 = ...

$migrations = array(
  $migration01
// $migration02
// $migration03
);

// Connect to MySQL
// Host/user/password must be the same as in the kubernetes pod definition
$conn = new PDO("mysql:host=mysql", 'root', 'yourpassword');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

# Create the database if it doesn't exist.
$conn->exec($db_sql);

# Use the database;
$conn->query("use guestbook");

# Create the migration history table if it doesn't exist.
$conn->exec($schema_sql);

$maxnum = $conn->query("SELECT MAX(num)+1 AS num FROM mhistory;")->fetchAll();
$schema_num = $maxnum[0]['num'];
if (!$schema_num) {
  $schema_num = 0;
}

while ($schema_num < count($migrations)) {
  echo "$schema_num: $migrations[$schema_num]<br>";
  $conn->exec($migrations[$schema_num]);

  $stmt = $conn->prepare("INSERT INTO mhistory (num) VALUES (:num);");
  $stmt->execute(array(':num' => $schema_num));
  $schema_num++;
  echo "---------------<br>";
}

echo "Done."

?>
