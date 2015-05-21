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

/**
 * Saves a new message and updates the cache.
 */
function newMessage($conn, $cache, $message) {
  // This is great PHP
  if ($message == "0" || $message) {
    $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (:message);");
    $stmt->execute(array(':message' => $message));
    $cache->set("messages", getMessages($conn, null));
  }
}

/**
 * Gets all messages from the database. If a cache connection
 * object is passed then the cache is checked before querying
 * from the database.
 */
function getMessages($conn, $cache) {
  if ($cache) {
    $messages = $cache->get("messages");
  } else {
    $messages = null;
  }
  if (!$messages) {
    $stmt = $conn->query("SELECT message FROM messages ORDER BY id;");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($cache) {
      $cache->set("messages", $messages);
    }
  }
  return $messages;
}

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $message = json_decode(file_get_contents('php://input'), true)['message'];
  newMessage($conn, $cache, $message);
  print('{"message": "'.$message.'"}');
} else {
  $messages = getMessages($conn, $cache);
  print('{"data": ' . json_encode($messages) . '}');
}
?>
