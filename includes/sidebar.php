<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar collapse d-lg-block col-lg-2">
	<div class="position-sticky">
		<ul class="nav flex-column">

			<li class="nav-item">
				<a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">
					<i class="bi bi-house-door"></i>
					Početak
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link <?= $currentPage == 'vozila.php' ? 'active' : '' ?>" href="vozila.php">
					<i class="bi bi-car-front"></i>
					Vozila
				</a>
			</li>

			<?php if ($_SESSION['user']['uloga'] === 'admin'): ?>
			<li class="nav-item">
				<a class="nav-link <?= $currentPage == 'zaposlenici.php' ? 'active' : '' ?>" href="zaposlenici.php">
					<i class="bi bi-people"></i>
					Zaposlenici
				</a>
			</li>
			<?php endif; ?>

			<li class="nav-item">
				<a class="nav-link <?= $currentPage == 'najam.php' ? 'active' : '' ?>" href="najam.php">
					<i class="bi bi-key"></i>
					Najam
				</a>
			</li>

		</ul>
	</div>
</div>