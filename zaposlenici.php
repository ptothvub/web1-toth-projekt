<!DOCTYPE html>
<html lang="hr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Upravljanje vozilima - Zaposlenici</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<!--CSS-->
	<link rel="stylesheet" href="css/content-wrapper.css">
	<link rel="stylesheet" href="css/sidebar.css">
	<link rel="stylesheet" href="css/navbar.css">

	<style>
		body {
			min-height: 100vh;
			overflow-x: hidden;
			padding-top: 50px;
		}
	</style>
</head>

<body>
	<!--Navigation-->
	<div id="navbar-container"></div>

	<div class="container-fluid">
		<div class="row">
			<!--Sidebar-->
			<div id="sidebar-container"></div>

			<!--Main content-->
			<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

				<!--Header-->
				<div
					class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Vozači</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<button type="button" class="btn btn-primary">
							<i class="bi bi-person-fill-add"></i>
							Dodaj osobu
						</button>
					</div>
				</div>

				<!--Search bar-->
				<div class="row mb-4">
					<div class="col">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Pretraži zaposlenike..." />
							<button class="btn btn-primary" type="button">
								<i class="bi bi-search"></i>
							</button>
						</div>
					</div>
				</div>

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

							<!--Prvi redak-->
							<tr>
								<td>1</td>
								<td><strong>Ivan Horvat</strong></td>
								<td>Bjelovar</td>
								<td>A, B, C</td>
								<td><span class="badge bg-success rounded-pill">Dostupan</span></td>
								<td class="text-end">
									<button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
									<button class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></button>
								</td>
							</tr>

							<!--Drugi redak-->
							<tr>
								<td>2</td>
								<td><strong>Marija Kovačić</strong></td>
								<td>Zagreb</td>
								<td>B</td>
								<td><span class="badge bg-success rounded-pill">Dostupan</span></td>
								<td class="text-end">
									<button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
									<button class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></button>
								</td>
							</tr>

							<!--Treći redak-->
							<tr>
								<td>3</td>
								<td><strong>Marko Marulić</strong></td>
								<td>Split</td>
								<td>B, C, CE, D</td>
								<td><span class="badge bg-warning text-dark rounded-pill">Na putu</span></td>
								<td class="text-end">
									<button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
									<button class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></button>
								</td>
							</tr>

							<!--Čevrti redak-->
							<tr>
								<td>4</td>
								<td><strong>Siniša Lovrić</strong></td>
								<td>Bjelovar</td>
								<td>B, C, C1</td>
								<td><span class="badge bg-danger rounded-pill">Godišnji odmor</span></td>
								<td class="text-end">
									<button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
									<button class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></button>
								</td>
							</tr>

							<!--Peti redak-->
							<tr>
								<td>5</td>
								<td><strong>Đuro Pedala</strong></td>
								<td>Osijek</td>
								<td>A, B, C, C1</td>
								<td><span class="badge bg-warning text-dark rounded-pill">Na putu</span></td>
								<td class="text-end">
									<button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
									<button class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></button>
								</td>
							</tr>

						</tbody>
					</table>

					<!--Pagination-->
					<nav class="mt-4">
						<ul class="pagination justify-content-center">
							<li class="page-item disabled">
								<a href="#" class="page-link">Prošla</a>
							</li>

							<li class="page-item active">
								<a class="page-link bg-dark border-dark" href="#" aria-current="page">1</a>
							</li>
							<li class="page-item"><a class="page-link text-dark" href="#">2</a></li>
							<li class="page-item"><a class="page-link text-dark" href="#">3</a></li>

							<li class="page-item">
								<a class="page-link text-dark" href="#">Iduća</a>
							</li>
						</ul>
					</nav>

				</div>

			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="js/components.js"></script>
	<script src="js/alert.js"></script>
</body>

</html>