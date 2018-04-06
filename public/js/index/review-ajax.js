$(function() {

    console.log('CHARGEMENT     :       REVIEW.JS')

    $('#submit-form-review').click(function(e) {
        e.preventDefault();

        // Récupération des données du form
        $comments = $('#icon_prefix2').val();

        // Ajax
        $("#review-container").load("./assets/php/combat.php", { // accolades !
            nom : nom,
            cible : cible,
            attaque : attaque
        });

        $(".zoneText").html("<p>En attente de connexion...</p>");
    })

})