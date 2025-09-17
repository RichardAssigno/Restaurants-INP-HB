document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector(".navbar-form .form-control");
    if (searchInput) {
        searchInput.addEventListener("keyup", function (event) {
            const query = event.target.value;

            if (query.length > 7) {

                fetch(`/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {


                        // Vérifier s'il y a des résultats
                        if (data.length > 0) {
                            let resultatHtml = ""; // Variable pour accumuler les éléments HTML

                            data.forEach(etudiant => {
                                // Ajout de chaque composant HTML dans la variable
                                resultatHtml += `
                                <div class="m-2 m-sm-1 m-md-2 ml-lg-4 mt-3 alert alert-info alert-dismissible fade show search-resultat"
                                     role="alert"
                                     onmouseover="this.style.backgroundColor='#f2f4f4';"
                                     onmouseout="this.style.backgroundColor='#23b7e51c';">
                                    <button class="close" type="button" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <a href="/search/affiche-etudiant/${etudiant.id}" class="search-url">
                                        ${etudiant.matricule} ${etudiant.nom} ${etudiant.prenoms}
                                    </a>
                                </div>
                            `; });

                            console.log(resultatHtml);
                        // Injecter tous les éléments HTML dans #resultat
                        $("#resultat").html(resultatHtml);

                            // Afficher la div contenant les résultats
                            $(".resultat").show();
                        } else {
                            // Afficher un message si aucun résultat n'est trouvé
                            $("#resultat").html(`<div class="m-2 m-sm-1 m-md-2 ml-lg-4 alert alert-info alert-dismissible fade show search-resultat text-center" role="alert"><button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Aucun resultat pour cette recherche</div>`);

                            $(".resultat").show();
                        }

                    })
                    .catch(error => console.error("Erreur lors de la recherche :", error));
            } else {
                $("#resultat").html("");
                // Masquer le popup si la recherche est vide ou trop courte
                $(".resultat").hide();
            }
        });
    }


});
