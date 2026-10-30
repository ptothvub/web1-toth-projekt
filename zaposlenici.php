<?php
require_once 'config/db.php';

if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;
}

if ($_SESSION['user']['uloga'] !== 'admin') {
	header("Location: index.php");
	exit;
}

$pageTitle = "Upravljanje vozilima - Zaposlenici";

//unos, uređivanje i brisanje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';

	try {
		if ($action === 'create') {
			$stmt = $pdo->prepare("INSERT INTO zaposlenici (ime_prezime, lokacija, kategorije, status) VALUES (?, ?, ?, ?)");
			$stmt->execute([$_POST['ime_prezime'], $_POST['lokacija'], $_POST['kategorije'], $_POST['status']]);
		} elseif ($action === 'update') {
			$stmt = $pdo->prepare("UPDATE zaposlenici SET ime_prezime = ?, lokacija = ?, kategorije = ?, status = ? WHERE id = ?");
			$stmt->execute([$_POST['ime_prezime'], $_POST['lokacija'], $_POST['kategorije'], $_POST['status'], $_POST['id']]);
		} elseif ($action === 'delete') {
			$stmt = $pdo->prepare("DELETE FROM zaposlenici WHERE id = ?");
			$stmt->execute([$_POST['id']]);
		}
	} catch (PDOException $e) {
		zapisiGresku("Greška u zaposlenici.php: " . $e->getMessage());
	}

	header("Location: zaposlenici.php");
	exit;
}

//pretraga
$search = $_GET['search'] ?? '';
if ($search) {
	$stmt = $pdo->prepare("SELECT * FROM zaposlenici WHERE ime_prezime LIKE ? OR lokacija LIKE ? ORDER BY id ASC");
	$stmt->execute(["%$search%", "%$search%"]);
} else {
	$stmt = $pdo->query("SELECT * FROM zaposlenici ORDER BY id ASC");
}
$zaposlenici = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<body>
<div class="container-fluid">
	<div class="row">
		<?php include 'includes/sidebar.php'; ?>

		<!--Main content-->
		<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

			<!--Header-->
			<div
				class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Vozači</h1>
				<div class="btn-toolbar mb-2 mb-md-0">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDodajZaposlenika">
						<i class="bi bi-person-fill-add"></i>
						Dodaj osobu
					</button>
				</div>
			</div>

			<!--Search bar-->
			<form method="GET" class="row mb-4">
				<div class="col">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Pretraži zaposlenike..." value="<?= htmlspecialchars($search) ?>" />
						<button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
							<?php if ($search): ?>
								<a href="zaposlenici.php" class="btn btn-outline-secondary">Poništi</a>
							<?php endif; ?>
						</button>
					</div>
				</div>
			</form>

			<!--Tablica-->
			<div class="table-responsive">
				<table class="table table-striped table-hover align-middle mb-4">
					<thead class="table-dark">
						<!--Header tablice-->
						<tr>
							<th scope="col">ID</th>
							<th scope="col">Ime i Prezime</th>
							<th scope="col">Lokacija</th>
							<th scope="col">Kategorija vozačke</th>
							<th scope="col">Status</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
				
					<?php foreach ($zaposlenici as $z): 
						$badgeClass = match($z['status']) {
							'Dostupan' => 'bg-success',
							'Na putu' => 'bg-warning text-dark',
							default => 'bg-danger'
						};
					?>
					<tr>
						<td><?= $z['id'] ?></td>
						<td><strong><?= htmlspecialchars($z['ime_prezime']) ?></strong></td>
						<td><?= htmlspecialchars($z['lokacija']) ?></td>
						<td><?= htmlspecialchars($z['kategorije']) ?></td>
						<td><span class="badge rounded-pill <?= $badgeClass ?>"><?= $z['status'] ?></span></td>
						<td class="text-end">
							<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUredi<?= $z['id'] ?>">
								<i class="bi bi-pencil"></i>
							</button>
							<form method="POST" class="d-inline">
								<input type="hidden" name="action" value="delete">
								<input type="hidden" name="id" value="<?= $z['id'] ?>">
								<button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Želite li izbrisati vozača?')">
										<i class="bi bi-trash"></i>
								</button>
							</form>
						</td>
					</tr>

					<div class="modal fade" id="modalUredi<?= $z['id'] ?>" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<form method="POST">
									<input type="hidden" name="action" value="update">
									<input type="hidden" name="id" value="<?= $z['id'] ?>">
									<div class="modal-header"><h5>Uredi</h5></div>
									<div class="modal-body">
										<div class="mb-3">
											<label class="form-label">Ime i Prezime</label>
											<input type="text" name="ime_prezime" class="form-control" value="<?= htmlspecialchars($z['ime_prezime']) ?>" required>
										</div>
										<div class="mb-3">
											<label class="form-label">Lokacija</label>
											<input type="text" name="lokacija" class="form-control" value="<?= htmlspecialchars($z['lokacija']) ?>" required>
										</div>
										<div class="mb-3">
											<label class="form-label">Kategorije</label>
											<input type="text" name="kategorije" class="form-control" value="<?= htmlspecialchars($z['kategorije']) ?>" required>
										</div>
										<div class="mb-3">
											<label class="form-label">Status</label>
											<select name="status" class="form-select">
												<option value="Dostupan" <?= $z['status']=='Dostupan'?'selected':'' ?>>Dostupan</option>
												<option value="Na putu" <?= $z['status']=='Na putu'?'selected':'' ?>>Na putu</option>
												<option value="Godišnji odmor" <?= $z['status']=='Godišnji odmor'?'selected':'' ?>>Godišnji odmor</option>
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-primary">Spremi</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php endforeach; ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalDodajZaposlenika" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST">
				<input type="hidden" name="action" value="create">
				<div class="modal-header"><h5>Dodaj</h5></div>
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Ime i Prezime</label>
						<input type="text" name="ime_prezime" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Lokacija</label>
						<input type="text" name="lokacija" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Kategorije (npr. B, C)</label>
						<input type="text" name="kategorije" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="Dostupan">Dostupan</option>
							<option value="Na putu">Na putu</option>
							<option value="Godišnji odmor">Godišnji odmor</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Dodaj</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>