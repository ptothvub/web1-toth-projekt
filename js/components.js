async function loadComponent(selector, url) {
  const container = document.querySelector(selector); //potraga za id-em iz parametra
  if (!container) return;

  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Failed to fetch ${url}: ${response.statusText}`);
    }

    //pretvori raw data u readable text
    const html = await response.text();
    //preusmjeri response u memoriju
    container.innerHTML = html;
  } catch (error) {
    console.error(`Error loading component from ${url}:`, error);
    container.innerHTML = `<div class="alert alert-danger">Failed to load component: ${error.message}</div>`;
  }
}

document.addEventListener("DOMContentLoaded", async function () {

  const currentPage = window.location.pathname.split("/").pop() || "index.php";

  await loadComponent("#sidebar-container", "navigation/sidebar.php");

  await loadComponent("#navbar-container", "navigation/navbar.php");

  setActiveMenuItem(currentPage);
});

function setActiveMenuItem(currentPage) {
  document.querySelectorAll(".sidebar .nav-link").forEach((link) => {
    link.classList.remove("active");
  });

  switch (currentPage) {
    case "index.php":
      document.getElementById("sidebar-dashboard")?.classList.add("active");
      break;
    case "vozila.php":
      document.getElementById("sidebar-vehicles")?.classList.add("active");
      break;
    case "zaposlenici.php":
      document.getElementById("sidebar-employees")?.classList.add("active");
      break;
    case "najam.php":
      document.getElementById("sidebar-rent")?.classList.add("active");
      break;
  }
}

