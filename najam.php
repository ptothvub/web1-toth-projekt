<?php
require_once 'config/db.php';

//provjeri je li korisnik prijavljen
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;
}

//provjeri ulogu
$currentUser = $_SESSION['user'];
$isAdmin = ($currentUser['uloga'] === 'admin');

//provjeri ima li vozač već rezervirano vozilo
//provjeri je li vozilo slobodno
//provjeri je li povratak > početka rezervacije

$imaAktivnoVozilo = false;
if (!$isAdmin) {
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM najmovi WHERE zaposlenik_id = ? AND status = 'Aktivno'");
	$stmt->execute([$currentUser['zaposlenik_id']]);
	$imaAktivnoVozilo = ($stmt->fetchColumn() > 0);
}

$action = $_POST['action'] ?? '';

if ($action === 'rezerviraj') {
	$voziloId = $_POST['vozilo_id'] ?? null;
	//ako sam u ulozi admina mogu rezervirati za bilo koga
	$zaposlenikId = $isAdmin ? $_POST['zaposlenik_id'] : $currentUser['zaposlenik_id'] ;
	$datumOd = $_POST['datum_pocetka'] ?? null;
	$datumDo = $_POST['datum_povratka'] ?? null;

	//osiguraj da su sva polja popunjena
	if (empty($voziloId)  || empty($zaposlenikId) || empty($datumOd) || empty($datumDo)) {
		zapisiGresku("Problem kod rezervacije - jedno od polja je prazno!");
		header("Location: najam.php");
		exit;
	}

	//provjeri datum
	if(strtotime($datumOd) > strtotime($datumDo)) {
		zapisiGresku("Datum povratka mora biti nakon početka");
		header("Location: najam.php");
		exit;
	}

	//provjera ima li vozač rezervirano vozilo
	$provjeraVozaca = $pdo->prepare("SELECT COUNT(*) FROM najmovi WHERE zaposlenik_id = ? AND status = 'Aktivno'");
	$provjeraVozaca->execute([$zaposlenikId]);
	if ($provjeraVozaca->fetchColumn() > 0) {
		zapisiGresku("Vozač ID $zaposlenikId već ima aktivno vozilo.");
		header("Location: najam.php");
		exit;
	}

	//provjera stanja vozila
	$provjeraVozila = $pdo->prepare("SELECT status FROM vozila WHERE id = ?");
	$provjeraVozila->execute([$voziloId]);
	$statusVozila = $provjeraVozila->fetchColumn();

	if ($statusVozila !== 'Dostupan') {
		zapisiGresku("Vozilo nije dostupno!");
		header("Location: najam.php");
		exit;
	}

	try {
		//unesi u najmove, promjeni status vozila i vozača
		$stmt = $pdo->prepare("INSERT INTO najmovi (vozilo_id, zaposlenik_id, datum_pocetka, datum_povratka, status) VALUES (?, ?, ?, ?, 'Aktivno')");
		$stmt->execute([$voziloId, $zaposlenikId, $datumOd, $datumDo]);

		$pdo->prepare("UPDATE vozila SET status = 'Nedostupan' WHERE id = ?")->execute([$voziloId]);

		$pdo->prepare("UPDATE zaposlenici SET status = 'Na putu' WHERE id = ?")->execute([$zaposlenikId]);
	} catch (PDOException $e) {
		zapisiGresku("Greška pri unosu rezervacije: " . $e->getMessage());
	}

	header("Location: najam.php");
	exit;
}

if ($action === 'vrati') {
	$najamId = $_POST['najam_id'] ?? null;
	$voziloId = $_POST['vozilo_id'] ?? null;
	$zaposlenikId = $_POST['zaposlenik_id'] ?? null;

	try {
		$pdo->prepare("UPDATE najmovi SET status = 'Vraćeno' WHERE id = ?")->execute([$najamId]);
		$pdo->prepare("UPDATE vozila SET status = 'Dostupan' WHERE id = ?")->execute([$voziloId]);
		$pdo->prepare("UPDATE zaposlenici SET status = 'Dostupan' WHERE id = ?")->execute([$zaposlenikId]);
	} catch (PDOException $e) {
		zapisiGresku("Greška pri vraćanju vozila: " . $e->getMessage());
	}

	header("Location: najam.php");
	exit;
}

//podaci za tablice i forme, aktivni najmovi
$slobodnaVozila = $pdo->query("SELECT * FROM vozila WHERE status = 'Dostupan'")->fetchAll();
$slobodniVozaci = $pdo->query("SELECT * FROM zaposlenici WHERE status = 'Dostupan'")->fetchAll();
$aktivniNajmovi = $pdo->query("
	SELECT n.*, v.naziv as vozilo_naziv, v.registracija, z.ime_prezime
	FROM najmovi n
	JOIN vozila v ON n.vozilo_id = v.id
	JOIN zaposlenici z ON n.zaposlenik_id = z.id
	WHERE n.status = 'Aktivno'
")->fetchAll();


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
			<div class="d-flex justify-content-between flex-wrap pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Rezervacija vozila</h1>
			</div>

			<!--Tablica za stvaranje rezervacija-->
			<div class="row">

				<div class="col-lg-4 mb-4">
					<div class="card border-primary shadow-sm">
						<div class="card-header bg-primary text-white">
							<h5 class="mb-0"><i class="bi bi-key-fill me-2"></i>Nove rezervacije</h5>
						</div>

						<div class="card-body">	
						  <?php if (!$isAdmin && $imaAktivnoVozilo): ?>
								<div class="alert alert-warning>
									<i class="bi bi-exclamation-triangle"></i>
									<strong>Već imate rezervirano vozilo!</strong>
							</div>
							<?php else: ?>
						  <form method="POST">
								<input type="hidden" name="action" value="rezerviraj">

								<?php if ($isAdmin): ?>
										<div class="mb-3">
											<label class="form-label fw-bold">Odaberi vozača</label>
											<select name="zaposlenik_id" class="form-select" required>
												<option value="">Odaberite vozača...</option>
												<?php foreach ($slobodniVozaci as $v): ?>
													<option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['ime_prezime']) ?></option>
												<?php endforeach; ?>
											</select>
										</div>
								<?php else: ?>
										<div class="mb-3">
											<label class="form-label fw-bold">Vozač</label>
											<input type="text" class="form-control" value="<?= htmlspecialchars($currentUser['ime_prezime']) ?>" readonly>
										</div>
								<?php endif; ?>

								<div class="mb-3">
									<label class="form-label fw-bold">Slobodno vozilo</label>
									<select name="vozilo_id" class="form-select" required>
										<option value="">Odaberite vozilo...</option>
										<?php foreach ($slobodnaVozila as $vozilo): ?>
											<option value="<?= $vozilo['id'] ?>"><?= htmlspecialchars($vozilo['naziv']) ?> (<?= htmlspecialchars($vozilo['registracija']) ?>)</option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="mb-3">
									<label class="form-label small fw-bold">Datum početka rezervacije</label>
									<input type="date" class="form-control" name="datum_pocetka" value="<?= date('Y-m-d') ?>" required>
								</div>

								<div class="mb-4">
									<label class="form-label small fw-bold">Planirani povratak</label>
									<input type="date" class="form-control" name="datum_povratka" required>
								</div>

								<button type="submit" class="btn btn-primary w-100" <?= empty($slobodnaVozila) ? 'disabled' : '' ?>>
									Rezerviraj vozilo
								</button>

							</form>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="col-lg-8">
					<div class="card shadow-sm border-primary bg-primary text-white">
						<div class="card-header">
							<h5 class="mb-0"><i class="bi bi-card-list me-2"></i>Trenutne rezervacije</h5>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-hover align-middle mb-0">
									<thead class="table-light">
										<tr>
											<th>Vozilo</th>
											<th>Vozač</th>
											<th>Preuzeto</th>
											<th>Povrat</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($aktivniNajmovi)): ?>
											<tr>
												<td colspan="5" class="text-center py-3 text-muted">Trenutno nema aktivnih rezervacija.</td>
											</tr>
										<?php else: ?>
											<?php foreach ($aktivniNajmovi as $red): 
												//admin može vratiti sva vozila
												$mozeVratiti = $isAdmin || ($currentUser['zaposlenik_id'] == $red['zaposlenik_id']);
											?>
												<tr>
													<td>
														<strong><?= htmlspecialchars($red['vozilo_naziv']) ?></strong><br>
														<small class="text-muted"><?= htmlspecialchars($red['registracija']) ?></small>
													</td>
													<td><?= htmlspecialchars($red['ime_prezime']) ?></td>
													<td><?= date('d.m.Y.', strtotime($red['datum_pocetka'])) ?></td>
													<td><?= date('d.m.Y.', strtotime($red['datum_povratka'])) ?></td>
													<td class="text-end">
														<?php if ($mozeVratiti): ?>
															<form method="POST" class="d-inline">
																<input type="hidden" name="action" value="vrati">
																<input type="hidden" name="najam_id" value="<?= $red['id'] ?>">
																<input type="hidden" name="vozilo_id" value="<?= $red['vozilo_id'] ?>">
																<input type="hidden" name="zaposlenik_id" value="<?= $red['zaposlenik_id'] ?>">
																<button type="submit" class="btn btn-outline-primary btn-sm">
																	<i class="bi bi-arrow-return-left"></i> Vrati
																</button>
															</form>
														<?php else: ?>
															<span class="badge bg-secondary">Tuđe vozilo</span>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php include 'includes/footer.php'; ?>
</body>

</html>