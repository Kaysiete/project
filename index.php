<?php


// Auto loader

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use app\Database\Core;

// Classes

// Php code



$result = Core::query(
	"SELECT menu_items.naam, menu_items.beschrijving, menu_items.prijs, menu_items.menu_id , menus.naam AS menu_naam
	FROM menu_items
	JOIN menus ON menu_items.menu_id  =menus.id;",
	[]
);

// haal menus op
$menus = Core::query(
	"SELECT id, naam FROM menus ORDER BY naam",
	[]
);

// verwerk formulier indiening
$form_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu_item'])) {
	$naam = $_POST['naam'] ?? '';
	$beschrijving = $_POST['beschrijving'] ?? '';
	$prijs = $_POST['prijs'] ?? '';
	$menu_id = $_POST['menu_id'] ?? '';

	if ($naam && $beschrijving && $prijs && $menu_id) {
		$success = Core::exec(
			"INSERT INTO menu_items (naam, beschrijving, prijs, menu_id) VALUES (?, ?, ?, ?);",
			[$naam, $beschrijving, $prijs, $menu_id]
		);

		if ($success) {
			$form_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Menu item succesvol toegevoegd!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
			// resultaten herhalen
			$result = Core::query(
				"SELECT menu_items.naam, menu_items.beschrijving, menu_items.prijs, menu_items.menu_id , menus.naam AS menu_naam
                FROM menu_items
                JOIN menus ON menu_items.menu_id = menus.id;",
				[]
			);
		} else {
			$form_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Fout bij toevoegen. Probeer opnieuw.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
		}
	} else {
		$form_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Vul alle velden in.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
	}
}

if (isset($_POST['add_menu'])) {
	$menuNaam = $_POST['menu_naam'] ?? '';
	if ($menuNaam) {
		try {
			$ok = Core::exec(
				"INSERT INTO menus (naam) VALUES (?);",
				[$menuNaam]
			);
			if ($ok) {
				$form_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
					. 'Menu succesvol toegevoegd!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
				// lijst met menus herladen
				$menus = Core::query(
					"SELECT id, naam FROM menus ORDER BY naam",
					[]
				);
			} else {
				$form_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
					. 'Fout bij toevoegen van menu.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
			}
		} catch (PDOException $e) {
			$form_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
				. '<strong>Database fout:</strong> ' . htmlspecialchars($e->getMessage())
				. '<br><small>Code: ' . $e->getCode() . '</small>'
				. '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
		}
	} else {
		$form_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
			. 'Geef een naam op.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
	}
}
?>

<!-- HTML openen -->
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Stage Project</title>
	<!-- CSS imports etc. (bootstrap) -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
	<!-- html body -->
	<div class="container mt-5">
		<?php echo $form_message; ?>

		<div class="mb-3">
			<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
				+ Nieuw Menu Item Toevoegen
			</button>
			<button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addMenuModal">
				+ Nieuw Menu
			</button>
		</div>

		<table class="table table-striped">
			<thead>
				<tr>
					<th>naam</th>
					<th>beschrijving</th>
					<th>prijs</th>
					<th>menu</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (is_array($result) && count($result) > 0) {
					foreach ($result as $row) {
						echo '<tr>';
						echo '<td>' . htmlspecialchars($row['naam']) . '</td>';
						echo '<td>' . htmlspecialchars($row['beschrijving']) . '</td>';
						echo '<td>€' . htmlspecialchars($row['prijs']) . '</td>';
						echo '<td>' . htmlspecialchars($row['menu_naam']) . '</td>';
						echo '</tr>';
					}
				} else {
					echo '<tr><td colspan="4"> geen resultaten gevonden</td></tr>';
				}
				?>
			</tbody>

		</table>
	</div>

	<!-- modal -->
	<div class="modal fade" id="addItemModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Nieuw Menu Item Toevoegen</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form method="POST">
					<div class="modal-body">
						<div class="mb-3">
							<label for="naam" class="form-label">Naam</label>
							<input type="text" class="form-control" id="naam" name="naam" required>
						</div>
						<div class="mb-3">
							<label for="beschrijving" class="form-label">beschrijving</label>
							<textarea class="form-control" id="beschrijving" name="beschrijving" rows="3" required></textarea>
						</div>
						<div class="mb-3">
							<label for="prijs" class="form-label">Prijs (€)</label>
							<input type="number" class="form-control" id="prijs" name="prijs" step="0.01" required>
						</div>
						<div class="mb-3">
							<label for="menu_id" class="form-label">Menu</label>
							<select class="form-select" id="menu-id" name="menu_id" required>
								<option value="">-- Selecteer een menu --</option>
								<?php
								if (is_array($menus) && count($menus) > 0) {
									foreach ($menus as $menu) {
										echo '<option value="' . htmlspecialchars($menu['id']) . '">' .
											htmlspecialchars($menu['naam']) . '</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
						<button type="submit" name="add_menu_item" class="btn btn-secondary">Toevoegen</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addMenuModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Nieuw Menu Toevoegen</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form method="POST">
					<div class="modal-body">
						<div class="mb-3">
							<label for="menu_naam" class="form-label">Naam</label>
							<input type="text" class="form-control" id="menu_naam" name="menu_naam" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
						<button type="submit" name="add_menu" class="btn btn-primary">Toevoegen</button>
					</div>
				</form>
			</div>
		</div>
	</div>




	<!-- <p><?php echo $result ?></p> -->
	<script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>




<!-- Javascript -->