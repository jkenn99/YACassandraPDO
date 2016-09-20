--TEST--
Mutiple select in prepare statement

--SKIPIF--
<?php require_once(dirname(__FILE__) . '/skipif.inc'); ?>
--FILE--

<?php
require_once(dirname(__FILE__) . '/config.inc');

$db = new PDO($dsn, $username, $password, $params);

try {
   $db->exec ("DROP KEYSPACE {$keyspace}");
} catch (PDOException $e) {}

$db->exec ("CREATE KEYSPACE $keyspace WITH REPLICATION = {'class' : 'SimpleStrategy', 'replication_factor': 1}");
$db->exec ("USE $keyspace");

$r = $db->exec ("CREATE TABLE settest (
            ip inet primary key,
            value set<text>
          ) WITH compression = {
            'sstable_compression': 'LZ4Compressor',
            'chunk_length_kb' : 64
          }"
        );

$ipaddr = "127.0.0.1";
$value= "google.com";

$stmt = $db->prepare("INSERT into settest (ip, value) values (:ipval, { :val } ) ;");

$stmt->bindValue(':ipval', $ipaddr, PDO::PARAM_STR);
$stmt->bindValue(':val', strval($value), PDO::PARAM_STR);
$stmt->execute();

$ipaddr = "127.0.0.2";
$value= "facebook.com";

$stmt->bindValue(':ipval', $ipaddr, PDO::PARAM_STR);
$stmt->bindValue(':val', strval($value), PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("SELECT value from settest where ip = :ipval");

$checkIP= "127.0.0.1";
$stmt->bindValue(':ipval', $checkIP, PDO::PARAM_STR);
$stmt->execute();

$result=$stmt->fetchAll();
print_r($result[0]['value']);


$checkIP= "127.0.0.2";
$stmt->bindValue(':ipval', $checkIP, PDO::PARAM_STR);
$stmt->execute();

$result=$stmt->fetchAll();
print_r($result[0]['value']);

?>
--EXPECTF--
Array
(
    [0] => google.com
)
Array
(
    [0] => facebook.com
)
