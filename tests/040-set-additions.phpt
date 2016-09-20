--TEST--
Set Additions

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

//create the initial row
$stmt = $db->prepare("INSERT into settest (ip, value) values (:ipval, { :val } ) ;");
        $stmt->bindValue(':ipval', $ipaddr, PDO::PARAM_STR);
        $stmt->bindValue(':val', strval($value), PDO::PARAM_STR);
        $stmt->bindValue(':val', strval($value), PDO::PARAM_STR);
        $stmt->execute();

//append another value to the same row
$value="yahoo.com";
$stmt = $db->prepare("UPDATE settest SET value = value + { :val } WHERE ip = :ipval ;");
        $stmt->bindValue(':ipval', $ipaddr, PDO::PARAM_STR);
        $stmt->bindValue(':val', strval($value), PDO::PARAM_STR);
        $stmt->execute();

  //print out the results
$checkIP= "127.0.0.1";
$stmt = $db->prepare("SELECT value from settest where ip = :ipval");
$stmt->bindValue(':ipval', $checkIP, PDO::PARAM_STR);
$stmt->execute();

$result=$stmt->fetchAll();
print_r($result[0]['value']);
?>
--EXPECTF--
Array
(
    [0] => google.com
    [1] => yahoo.com
)
