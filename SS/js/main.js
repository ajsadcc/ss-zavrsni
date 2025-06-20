document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");

    if (form) {
        form.addEventListener("submit", (e) => {
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();

            if (!email.includes("@")) {
                alert("Unesite ispravan email.");
                e.preventDefault();
                return;
            }

            if (password.length < 5) {
                alert("Lozinka mora imati najmanje 5 karaktera.");
                e.preventDefault();
            }
        });
    }
});
document.addEventListener("DOMContentLoaded", () => {
    const regForm = document.getElementById("registerForm");

    if (regForm) {
        regForm.addEventListener("submit", function (e) {
            const pass = document.getElementById("password").value;
            const confirm = document.getElementById("confirm").value;

            if (pass.length < 6) {
                alert("Lozinka mora imati najmanje 6 karaktera.");
                e.preventDefault();
            }

            if (pass !== confirm) {
                alert("Lozinke se ne poklapaju.");
                e.preventDefault();
            }
        });
    }
});
