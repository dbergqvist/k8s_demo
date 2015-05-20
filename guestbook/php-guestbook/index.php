<?php

set_include_path('.:/usr/share/php:/usr/share/pear');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to MemCached
// Host/port musto be the same as in the kubernetes pod definition
$cache = new Memcached();
$cache->addServer("memcached", 11211);

// Connect to MySQL
// Host/user/password must be the same as in the kubernetes pod definition
$conn = new PDO("mysql:host=mysql;dbname=guestbook", 'root', 'yourpassword');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function newMessage($conn, $message) {
    $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (:message);");
    $stmt->execute(array(':message' => $message));
    $cache->set("messages", getMessages());
}

function getMessages($conn) {
    $stmt = $conn->query("SELECT message FROM messages ORDER BY id;");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['cmd']) === true) {
  header('Content-Type: application/json');
  if ($_GET['cmd'] == 'set') {
    newMessage($conn, $_GET['value']);
    print('{"message": "Updated"}');
  } else if ($_GET['cmd'] == 'get') {
    $messages = getMessages($conn);
    print('{"data": ' . json_encode($messages) . '}');
  }
}
?>
