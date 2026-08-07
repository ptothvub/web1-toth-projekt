document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("click", function (event) {
    const logoutBtn = event.target.closest("#logout-btn");

    if (logoutBtn) {
      event.preventDefault();
      
      Swal.fire({
        icon: "success",
        title: "Uspješno ste odjavljeni!"
      }).then(() => {
        window.location.href = "index.html";
      });
    }
  });
});