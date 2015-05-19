<?

set_include_path('.:/usr/share/php:/usr/share/pear');

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_GET['cmd']) === true) {
  $cache = new Memcached();
  $cache->addServer("memcached", 11211);

  header('Content-Type: application/json');
  if ($_GET['cmd'] == 'set') {
    $cache->set($_GET['key'], $_GET['value']);
    print('{"message": "Updated"}');
  } else {
    $value = $cache->get($_GET['key']);
    print('{"data": "' . $value . '"}');
  }
} else {
  phpinfo();
} ?>
