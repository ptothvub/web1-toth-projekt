<?php
//otvaram session kako bi vidio koji kolačiće korisnik ima
session_start();

$host = 'localhost';
$db   = 'upravljanje_vozilima';
$user = 'root';
$pass = '';

try {
	$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
		//moram baciti grešku kako ne vratio false bez poruke
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		//vrati samo ime stupca bez broja
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	]);
} catch (PDOException $e) {
	zapisiGresku("Neuspješno spajanje na bazu: " . $e->getMessage());
	die("Greška pri spajanju na bazu podataka.");
}

function zapisiGresku($poruka) {
	$vrijeme = date('Y-m-d H:i:s');
	$korisnik = $_SESSION['user']['korisnicko_ime'] ?? 'Gost';
	$zapis = "[$vrijeme] [Korisnik: $korisnik] $poruka" . PHP_EOL;
	file_put_contents(__DIR__ . '/../error_log.txt', $zapis, FILE_APPEND);
}