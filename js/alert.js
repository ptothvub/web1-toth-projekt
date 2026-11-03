document.addEventListener("DOMContentLoaded", function () {

  const logoutBtn = document.getElementById("logout-btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", function (e) {
      e.preventDefault();
      Swal.fire({
        text: "Jeste li sigurni da se želite odjaviti?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "rgb(214, 26, 26)",
        cancelButtonColor: "#707070",
        confirmButtonText: "Odjava",
        cancelButtonText: "Odustani"
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "logout.php";
        }
      });
    });
  }

  document.querySelectorAll(".btn-delete").forEach(function (button) {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const form = this.closest("form");

      Swal.fire({
        text: "Ovaj zapis bit će trajno obrisan iz baze podataka!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "rgb(214, 26, 26)",
        cancelButtonColor: "#707070",
        confirmButtonText: "Obriši!",
        cancelButtonText: "Odustani"
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

});