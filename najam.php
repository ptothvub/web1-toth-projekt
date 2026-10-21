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
							<form id="form-najam">
								<div class="mb-3">
									<label class="form-label small fw-bold">Dostupni vozači</label>
									<select class="form-select" aria-label="Default select example">
										<option selected>Odaberite vozača...</option>
										<option value="1">Ivan Horvat - Bjelovar</option>
										<option value="2">Marija Kovačić - Bjelovar</option>
									</select>
								</div>

								<div class="mb-3">
									<label class="form-label small fw-bold">Slobodna vozila</label>
									<select class="form-select">
										<option selected>Odaberite vozilo...</option>
										<option value="1">Lancia Delta (BJ 124-HF)</option>
									</select>
								</div>

								<div class="mb-3">
									<label class="form-label small fw-bold">Datum početka rezervacije</label>
									<input type="date" class="form-control" />
								</div>

								<div class="mb-4">
									<label class="form-label small fw-bold">Planirani povratak</label>
									<input type="date" class="form-control" />
								</div>

								<button type="button" class="btn btn-primary w-100">
									Rezerviraj vozilo
								</button>

							</form>
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
										<tr>
											<td>
												<strong> Volkswagen Caddy</strong><br>
												<small class="text-muted">BJ 213-RI</small>
											</td>
											<td>Marko Marulić</td>
											<td>28.07.2026.</td>
											<td>05.08.2026.</td>
											<td class="text-end">
												<button class="btn btn-outline-primary action-return-vehicle">
													<i class="bi bi-arrow-return-left"></i> Vrati
												</button>
											</td>
										</tr>
										<tr>
											<td>
												<strong>Renault Master</strong><br>
												<small class="text-muted">ST 9876-EF</small>
											</td>
											<td>Ana Kovačević</td>
											<td>30.07.2026.</td>
											<td>10.08.2026.</td>
											<td class="text-end">
												<button class="btn btn-outline-primary action-return-vehicle">
													<i class="bi bi-arrow-return-left"></i> Vrati
												</button>
											</td>
										</tr>
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