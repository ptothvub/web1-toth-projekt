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

} catch (pdoException $e) {
    zapisiGresku("Greška na index.php: " . $e->getMessage());
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<body>
	<div class="container-fluid">
		<div class="row">

			<?php include 'navigation/sidebar.php'; ?>
			<!-- main content -->
			<div class="col-lg-10 ms-lg-auto px-md-4">
				<!-- card row -->
				<div class="row mb-4 ">
					<div class="col-md-3 ">
						<div class="card text-center mb-3 text-bg-primary">
							<div class="card-body">
								<h5 class="card-title">Broj vozila</h5>
								<h3 class="card-text"><?= $brojVozila?></h3>
								<p class="card-text">&nbsp;</p>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card text-center mb-3 text-bg-warning">
							<div class="card-body">
								<h5 class="card-title">Broj vozača</h5>
								<h3 class="card-text"><?= $brojVozaca?></h3>
								<p class="card-text">5 trenutno dostupnih</p>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card text-center mb-3 text-bg-success">
							<div class="card-body">
								<h5 class="card-title">Broj godišnjih odmora</h5>
								<h3 class="card-text"><?= $brojGO?></h3>
								<p class="card-text">&nbsp;</p>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card text-center mb-3 text-bg-danger">
							<div class="card-body">
								<h5 class="card-title">Broj vozila na servisu</h5>
								<h3 class="card-text"><?= $brojServisa?></h3>
								<p class="card-text">&nbsp;</p>
							</div>
						</div>
					</div>
				</div>
				<!-- end of card row -->

				<div class="card mb-4">
					<h5 class="card-header">
						<i class="bi bi-bell"></i>
						Događaji
					</h5>
					<div class="card-body">

					
						<div class="list-group">
							<a href="#" class="list-group-item list-group-item-action">
								<div class="d-flex w-100 justify-content-between">
									<h6 class="mb-1">Vozilo iznajmljeno - Marko Marulić</h6>
									<small>prije 5 min</small>
								</div>
								<small>10.7.2026. - 15.7.2026.</small>
							</a>
							<a href="#" class="list-group-item list-group-item-action">
								<div class="d-flex w-100 justify-content-between">
									<h6 class="mb-1">Servis (Daewoo Matiz - BJ 777-SJ) </h6>
									<small>prije 16 sati</small>
								</div>
								<small>9.7.2026. - 11.7.2026.</small>
							</a>
							<a href="#" class="list-group-item list-group-item-action">
								<div class="d-flex w-100 justify-content-between">
									<h6 class="mb-1">Povratak vozila - Siniša Lovrić</h6>
									<small>10.7.2026.</small>
								</div>
								<small>2.7.2026. - 10.7.2026.</small>
							</a>
							<a href="#" class="list-group-item list-group-item-action">
								<div class="d-flex w-100 justify-content-between">
									<h6 class="mb-1">Povratak vozila - Ivan Horvat</h6>
									<small>7.7.2026.</small>
								</div>
								<small>28.6.2026. - 7.7.2026.</small>
							</a>
						</div>


					</div>
				</div>

			</div>
		</div>
	</div>
	<?php include 'includes/footer.php'; ?>
</body>
</html>