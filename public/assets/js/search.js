document.addEventListener("DOMContentLoaded", function () {
    console.log("Search.js chargé ✅");

    const searchInput = document.querySelector(".navbar-form .form-control");
    const resultatDiv = document.querySelector("#resultat");
    const resultatContainer = document.querySelector(".resultat");

    if (!searchInput) return;

    searchInput.addEventListener("keyup", function (event) {
        const query = event.target.value.trim();

        if (query.length > 3) {
            fetch(`/search?query=${encodeURIComponent(query)}`, {
                headers: { "Accept": "application/json" }
            })
                .then(response => response.ok ? response.json() : Promise.reject(response.status))
                .then(data => {
                    let resultatHtml = "";

                    if (data.length > 0) {
                        data.forEach(etudiant => {
                            resultatHtml += `
                                <div class="alert alert-info alert-dismissible fade show search-resultat text-center"
                                     role="alert"
                                     style="width: 80%; background-color:#23b7e51c; cursor:pointer;"
                                     onmouseover="this.style.backgroundColor='#f2f4f4';"
                                     onmouseout="this.style.backgroundColor='#23b7e51c';">
                                    <a href="/search/affiche-etudiant/${etudiant.id}" class="search-url">
                                        ${etudiant.matricule} ${etudiant.nom} ${etudiant.prenoms}
                                    </a>
                                </div>
                            `;
                        });
                    } else {
                        resultatHtml = `
                            <div class="alert alert-warning text-center" style="width: 80%;">
                                Aucun résultat pour <b>${query}</b>
                            </div>
                        `;
                    }

                    resultatDiv.innerHTML = resultatHtml;
                    resultatContainer.style.display = "block";
                })
                .catch(error => {
                    console.error("Erreur lors de la recherche :", error);
                    resultatDiv.innerHTML = `<div class="alert alert-danger text-center" style="width: 80%;">Une erreur est survenue</div>`;
                    resultatContainer.style.display = "block";
                });
        } else {
            resultatDiv.innerHTML = "";
            resultatContainer.style.display = "none";
        }
    });
});
