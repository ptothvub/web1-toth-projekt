<?php
require_once 'config/db.php';

//provjeri je li korisnik ulogiran, posalji ga na index ako je
//header redirecta, exit=break
if (isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}

$errormsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //spremi podatke iz forme i usporedi ih sa bazom
  //?? '' defaulta na prazan string da nemam undefined
  $username = $_POST['korisnicko_ime'] ?? '';
  $password = $_POST['lozinka'] ?? '';

  //posalji upit gdje je id zaposlenika = zaposlenik_id
  //$pdo je instanca objekta baze
  //left join je lijevi krug i sredina! k je lijevi, z desni
  //Sve iz k ostaje, iz z ispada sto nema match u k
  $stmt = $pdo->prepare("
    SELECT k.*, z.ime_prezime
    FROM korisnici k LEFT JOIN zaposlenici z 
    ON k.zaposlenik_id = z.id
    WHERE k.korisnicno_ime = ? AND k.lozinka = ?
  ");

  $stmt->execute([$username, $password]);
  //dohvati odgovor, ako postoji spremi ga u $_SESSION
  $korisnik = $stmt->fetch();

  if ($korisnik) {
    $_SESSION['user'] = [
      'id' => $korisnik['id'],
      'zaposlenik_id' => $korisnik['zaposlenik_id'],
      'korisnicko_ime' => $korisnik['korisnicko_ime'],
      'uloga' => $korisnik['uloga'],
      'ime_prezime' => $korisnik['ime_prezime'] ?? 'Administrator'
    ];
    header("Location: index.php");
    exit;
  } else {
    $errormsg = "Pogrešno korisničko ime ili lozinka!";
    zapisiGresku("Neuspješna prijava korisnika '$username'");
  }
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
  <meta charset="UTF-8">
  <title>Prijava</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
  <div class="card p-4 shadow" style="width: 350px;">
    <h4 class="text-center mb-3">Prijava</h4>
    
    <?php if ($errormsg): ?>
      <div class="alert alert-danger py-2"><?= $errormsg ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Korisničko ime</label>
        <input type="text" name="korisnicko_ime" class="form-control" required placeholder="Korisničko ime">
      </div>
      <div class="mb-3">
        <label class="form-label">Lozinka</label>
        <input type="password" name="lozinka" class="form-control" required placeholder="Lozinka">
      </div>
      <button type="submit" class="btn btn-primary w-100">Prijavite se</button>
    </form>
  </div>
</body>

</html>