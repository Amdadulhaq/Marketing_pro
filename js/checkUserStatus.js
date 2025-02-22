document.addEventListener("DOMContentLoaded", function () {
    fetch("./php/check_session.php")
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                document.getElementById("loginNav").classList.add("d-none");
                document.getElementById("logoutNav").classList.remove("d-none");

                if (data.role === "admin") {
                    document.getElementById("adminDashboardNav").classList.remove("d-none");
                } else {
                    document.getElementById("dashboardNav").classList.remove("d-none");
                }
            }
        })
        .catch(error => console.error("Error checking session:", error));
});
