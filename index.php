<?php
// Auto loader

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Classes

// Php code



// Voorbeeld database select, returnt een array:

/**
$result = Database\Core::query("select id, name from test_table where id = ? or name = ?;", [1, 'test']);
 **/

// Voorbeeld database insert / update.
// Returned nummer van aangepaste items, of het id van het gemaakte item. False als de insert of update mislukt.
/**
if(Database\Core::exec("update test_table set name = ? where id = ?;", ['nieuwe naam', 1])) {
	echo 'update gelukt';
} else {
	echo 'update mislukt';
}
 **/


// Extra punten: zet basis dingen zoals het openen van de html en importeren van classes in aparte files die je kunt importeren.
// Zodat als je een nieuwe pagina maakt je niet al die code hoeft te herhalen.
?>

<!-- HTML openen -->
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Herd Project</title>
	<!-- CSS imports etc. (bootstrap) -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
	<!-- html body -->


	<script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>




<!-- Javascript -->