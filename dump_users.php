<?php
$db = new PDO('sqlite:database/database.sqlite');
function dumpTable($db, $table) {
    echo "--- $table ---\n";
    try {
        $stmt = $db->query("SELECT id, email FROM $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
    } catch (Exception $e) { echo $e->getMessage() . "\n"; }
}
dumpTable($db, 'super_admins');
dumpTable($db, 'owners');
dumpTable($db, 'users');

echo "--- hostels ---\n";
$stmt = $db->query("SELECT id, name FROM hostels");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { print_r($row); }

echo "--- hostel_user ---\n";
$stmt = $db->query("SELECT * FROM hostel_user");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { print_r($row); }
