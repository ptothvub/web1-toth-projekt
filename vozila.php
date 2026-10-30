<?php
require_once 'config/db.php';

//provjeri je li korisnik prijavljen
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;
}

$isAdmin = ($_SESSION['user']['uloga'] === 'admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!$isAdmin) {
		zapisiGresku("Neovlašten pokušaj mijenjanja vozila od strane: " . $_SESSION['user']['korisnicko_ime']);
		die("Nemate ovlasti za ovu radnju!");
	}

	$action = $_POST['action'] ?? '';

	try {
		if ($action === 'create') {
			$stmt = $pdo->prepare("INSERT INTO vozila (naziv, registracija, godina, kilometraza, status) VALUES (?, ?, ?, ?, ?)");
			$stmt->execute([$_POST['naziv'], $_POST['registracija'], $_POST['godina'], $_POST['kilometraza'], $_POST['status']]);
		} 
		elseif ($action === 'update') {
			$stmt = $pdo->prepare("UPDATE vozila SET naziv = ?, registracija = ?, godina = ?, kilometraza = ?, status = ? WHERE id = ?");
			$stmt->execute([$_POST['naziv'], $_POST['registracija'], $_POST['godina'], $_POST['kilometraza'], $_POST['status'], $_POST['id']]);
		} 
		elseif ($action === 'delete') {
			$stmt = $pdo->prepare("DELETE FROM vozila WHERE id = ?");
			$stmt->execute([$_POST['id']]);
		}
	} catch (PDOException $e) {
		zapisiGresku("Greška na vozilima: " . $e->getMessage());
	}

	header("Location: vozila.php");
	exit;
}

$statusFilter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM vozila WHERE 1=1";
$params = [];

if ($statusFilter) {
	$sql .= " AND status = ?";
	$params[] = $statusFilter;
}
if ($search) {
	$sql .= " AND (naziv LIKE ? OR registracija LIKE ?)";
	$params[] = "%$search%";
	$params[] = "%$search%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vozila = $stmt->fetchAll();


include 'includes/header.php';
include 'includes/navbar.php';
?>

<body>
<div class="container-fluid">
	<div class="row">
		<?php include 'includes/sidebar.php'; ?>

		<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
			<div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h2>Vozila</h2>
				<?php if ($isAdmin): ?>
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDodajVozilo">
						<i class="bi bi-plus-lg"></i> Dodaj vozilo
					</button>
				<?php endif; ?>
			</div>

			<form method="GET" class="row g-3 mb-4">
				<div class="col-md-4">
					<select name="status" class="form-select" onchange="this.form.submit()">
						<option value="">Sva vozila</option>
						<option value="Dostupan" <?= $statusFilter == 'Dostupan' ? 'selected' : '' ?>>Dostupna</option>
						<option value="Nedostupan" <?= $statusFilter == 'Nedostupan' ? 'selected' : '' ?>>Nedostupna</option>
						<option value="Na servisu" <?= $statusFilter == 'Na servisu' ? 'selected' : '' ?>>Na servisu</option>
					</select>
				</div>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" name="search" class="form-control" placeholder="Pretraži vozila..." value="<?= htmlspecialchars($search) ?>" />
						<button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
					</div>
				</div>
			</form>

			<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
				<?php foreach ($vozila as $v): ?>
				<div class="col">
					<div class="card h-100 shadow-sm">
						<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
							<span><?= htmlspecialchars($v['naziv']) ?></span>
							<span class="badge bg-secondary"><?= $v['status'] ?></span>
						</div>
						<div class="card-body">
							<h5><?= htmlspecialchars($v['registracija']) ?></h5>
							<p class="mb-1">Godina: <?= $v['godina'] ?></p>
							<p>Kilometraža: <?= $v['kilometraza'] ?> km</p>
						</div>
						
						<?php if ($isAdmin): ?>
						<div class="card-footer d-flex justify-content-between bg-white">
							<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUredi<?= $v['id'] ?>">Uredi</button>
							<form method="POST">
								<input type="hidden" name="action" value="delete">
								<input type="hidden" name="id" value="<?= $v['id'] ?>">
								<button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Sigurno obrisati?')">Obriši</button>
							</form>
						</div>

						<div class="modal fade" id="modalUredi<?= $v['id'] ?>" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="POST">
										<input type="hidden" name="action" value="update">
										<input type="hidden" name="id" value="<?= $v['id'] ?>">
										<div class="modal-header"><h5>Uredi vozilo</h5></div>
										<div class="modal-body">
											<div class="mb-2"><label>Naziv</label><input type="text" name="naziv" class="form-control" value="<?= htmlspecialchars($v['naziv']) ?>" required></div>
											<div class="mb-2"><label>Registracija</label><input type="text" name="registracija" class="form-control" value="<?= htmlspecialchars($v['registracija']) ?>" required></div>
											<div class="mb-2"><label>Godina</label><input type="number" name="godina" class="form-control" value="<?= $v['godina'] ?>" required></div>
											<div class="mb-2"><label>Kilometraža</label><input type="number" name="kilometraza" class="form-control" value="<?= $v['kilometraza'] ?>" required></div>
											<div class="mb-2"><label>Status</label>
												<select name="status" class="form-select">
													<option value="Dostupan" <?= $v['status']=='Dostupan'?'selected':'' ?>>Dostupan</option>
													<option value="Nedostupan" <?= $v['status']=='Nedostupan'?'selected':'' ?>>Nedostupan</option>
													<option value="Na servisu" <?= $v['status']=='Na servisu'?'selected':'' ?>>Na servisu</option>
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
						<?php endif; ?>

					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<?php if ($isAdmin): ?>
<div class="modal fade" id="modalDodajVozilo" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST">
				<input type="hidden" name="action" value="create">
				<div class="modal-header"><h5>Novo vozilo</h5></div>
				<div class="modal-body">
					<div class="mb-2"><label>Naziv</label><input type="text" name="naziv" class="form-control" required></div>
					<div class="mb-2"><label>Registracija</label><input type="text" name="registracija" class="form-control" required></div>
					<div class="mb-2"><label>Godina</label><input type="number" name="godina" class="form-control" value="2020" required></div>
					<div class="mb-2"><label>Kilometraža</label><input type="number" name="kilometraza" class="form-control" value="0" required></div>
					<div class="mb-2"><label>Status</label>
						<select name="status" class="form-select">
							<option value="Dostupan">Dostupan</option>
							<option value="Nedostupan">Nedostupan</option>
							<option value="Na servisu">Na servisu</option>
						</select>
					</div>
				</div>
				<div class="modal-footer"><button type="submit" class="btn btn-primary">Dodaj</button></div>
			</form>
		</div>
	</div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
</body>
</html>