<?php
//spoji se na bazu, $pdo mi je objekt instance veze s db, ne treba cleanup...
require_once 'config/db.php';

// Ako korisnik nije prijavljen, preusmjeri ga na login
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;
}

$pageTitle = "Upravljanje vozilima - Početna";

try {
	//counteri za top kartice
	$brojVozila = $pdo->query("SELECT COUNT(*) FROM vozila")->fetchColumn();
	$brojVozaca = $pdo->query("SELECT COUNT(*) FROM zaposlenici")->fetchColumn();
	$brojDostupnihVozila = $pdo->query("SELECT COUNT(*) FROM vozila WHERE status = 'Dostupan'")->fetchColumn();
	$brojDostupnihVozaca = $pdo->query("SELECT COUNT(*) FROM zaposlenici WHERE status = 'Dostupan'")->fetchColumn();
	$brojGO = $pdo->query("SELECT COUNT(*) FROM zaposlenici WHERE status = 'Godišnji odmor'")->fetchColumn();
	$brojServisa = $pdo->query("SELECT COUNT(*) FROM vozila WHERE status = 'Na servisu'")->fetchColumn();

	$zadnjiNajmovi = $pdo->query("
		SELECT n.*, v.naziv as vozilo_naziv, v.registracija, z.ime_prezime 
		FROM najmovi n
		JOIN vozila v ON n.vozilo_id = v.id
		JOIN zaposlenici z ON n.zaposlenik_id = z.id
		ORDER BY n.id DESC LIMIT 4
	")->fetchAll();
} catch (PDOException $e) {
	zapisiGresku("Greška na index.php: " . $e->getMessage());
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<body>
<div class="container-fluid">
	<div class="row">
		<?php include 'includes/sidebar.php'; ?>

		<!-- main content -->
		<div class="col-lg-10 ms-lg-auto px-md-4">
				
			<!-- card row -->
			<div class="row mb-4 mt-3">
				<div class="col-md-3">
					<div class="card text-center mb-3 text-bg-primary shadow-sm">
						<div class="card-body">
							<h5 class="card-title">Broj vozila</h5>
							<h3 class="card-text"><?= $brojVozila ?></h3>
							<p class="card-text"><?= $brojDostupnihVozila ?> trenutno dostupnih</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card text-center mb-3 text-bg-warning shadow-sm">
						<div class="card-body">
							<h5 class="card-title">Broj vozača</h5>
							<h3 class="card-text"><?= $brojVozaca ?></h3>
							<p class="card-text"><?= $brojDostupnihVozaca ?> trenutno dostupnih</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card text-center mb-3 text-bg-success shadow-sm">
						<div class="card-body">
							<h5 class="card-title">Godišnji odmori</h5>
							<h3 class="card-text"><?= $brojGO ?></h3>
							<p class="card-text">&nbsp;</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card text-center mb-3 text-bg-danger shadow-sm">
						<div class="card-body">
							<h5 class="card-title">Broj vozila na servisu</h5>
							<h3 class="card-text"><?= $brojServisa ?></h3>
							<p class="card-text">&nbsp;</p>
						</div>
					</div>
				</div>
			</div>
			<!-- end of card row -->

			<!-- Kartica s nedavnim događajima -->
			<div class="card mb-4 shadow-sm">
				<h5 class="card-header">
						<i class="bi bi-bell me-2"></i> Događaji
				</h5>
				<div class="card-body">
						<div class="list-group">
							<?php if (empty($zadnjiNajmovi)): ?>
									<p class="text-muted p-2">Nema zabilježenih događaja.</p>
							<?php else: ?>
								<?php foreach ($zadnjiNajmovi as $najam): ?>
									<div class="list-group-item list-group-item-action">
										<div class="d-flex w-100 justify-content-between">
											<h6 class="mb-1">
												Vozilo: <?= htmlspecialchars($najam['vozilo_naziv']) ?> (<?= htmlspecialchars($najam['registracija']) ?>) - 
												Vozač: <?= htmlspecialchars($najam['ime_prezime']) ?>
											</h6>
											<span class="badge <?= $najam['status'] == 'Aktivno' ? 'bg-warning text-dark' : 'bg-success' ?>">
												<?= $najam['status'] ?>
											</span>
										</div>
										<small class="text-muted">
											Period: <?= date('d.m.Y.', strtotime($najam['datum_pocetka'])) ?> - <?= date('d.m.Y.', strtotime($najam['datum_povratka'])) ?>
										</small>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>