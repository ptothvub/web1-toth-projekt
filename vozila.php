<?php
require_once 'config/db.php';

//provjeri je li korisnik prijavljen
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;
}

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
				<h1 class="h2">Vozila</h1>
				<div class="btn-toolbar mb-2 mb-md-0">
					<button type="button" class="btn btn-primary">
						<i class="bi bi-car-front-fill"></i>
						Dodaj vozilo
					</button>
				</div>
			</div>

			<!--Drop down menu i search bar-->
			<div class="row g-3 mb-4">
				<div class="col-md-4">
					<select class="form-select">
						<option selected>Sva vozila</option>
						<option>Dostupna</option>
						<option>Nedostupna</option>
						<option>Na servisu</option>
					</select>
				</div>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Pretraži vozila..." />
						<button class="btn btn-primary" type="button">
							<i class="bi bi-search"></i>
						</button>
					</div>
				</div>
			</div>



			<!--Kartice vozila-->
			<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">

				<!--Prva kartica-->
				<div class="col">
					<div class="card h-100 shadow-sm border-success">
						<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><i class="bi bi-car-front-fill"></i>
								Lancia Delta
							</h6>
							<span class="badge rounded-pill bg-success">Dostupan</span>
						</div>
						<div class="card-body">
							<h5 class="card-title">BJ 124-HF</h5>
							<p class="card-text mb-2">Godina: 1993</p>
							<p class="card-text">Kilometraža: 77829</p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<button class="btn btn-outline-primary action-edit"><i class="bi bi-pencil"></i>
								Uredi
							</button>
							<button class="btn btn-outline-danger btn-delete"><i class="bi bi-trash"></i>
								Obriši
							</button>
						</div>
					</div>
				</div>

				<!--Druga kartica-->
				<div class="col">
					<div class="card h-100 shadow-sm border-warning">
						<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><i class="bi bi-car-front-fill"></i>
								Daewoo Matiz
							</h6>
							<span class="badge rounded-pill bg-warning text-dark">Na servisu</span>
						</div>
						<div class="card-body">
							<h5 class="card-title">BJ 777-SJ</h5>
							<p class="card-text mb-2">Godina: 2000</p>
							<p class="card-text">Kilometraža: 478294</p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<button class="btn btn-outline-primary action-edit"><i class="bi bi-pencil"></i>
								Uredi
							</button>
							<button class="btn btn-outline-danger btn-delete"><i class="bi bi-trash"></i>
								Obriši
							</button>
						</div>
					</div>
				</div>

				<!--Treća kartica-->
				<div class="col">
					<div class="card h-100 shadow-sm border-danger">
						<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><i class="bi bi-car-front-fill"></i>
								Volkswagen Caddy
							</h6>
							<span class="badge rounded-pill bg-danger">Nedostupan</span>
						</div>
						<div class="card-body">
							<h5 class="card-title">BJ 213-RI</h5>
							<p class="card-text mb-2">Godina: 2015</p>
							<p class="card-text">Kilometraža: 247829</p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<button class="btn btn-outline-primary action-edit"><i class="bi bi-pencil"></i>
								Uredi
							</button>
							<button class="btn btn-outline-danger btn-delete"><i class="bi bi-trash"></i>
								Obriši
							</button>
						</div>
					</div>
				</div>

				<!--Četvrta kartica-->
				<div class="col">
					<div class="card h-100 shadow-sm border-danger">
						<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><i class="bi bi-car-front-fill"></i>
								Škoda Favorit
							</h6>
							<span class="badge rounded-pill bg-danger">Nedostupan</span>
						</div>
						<div class="card-body">
							<h5 class="card-title">BJ 123-CZ</h5>
							<p class="card-text mb-2">Godina: 1995</p>
							<p class="card-text">Kilometraža: 347280</p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<button class="btn btn-outline-primary action-edit"><i class="bi bi-pencil"></i>
								Uredi
							</button>
							<button class="btn btn-outline-danger btn-delete"><i class="bi bi-trash"></i>
								Obriši
							</button>
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