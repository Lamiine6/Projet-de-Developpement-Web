<?php
    $title= "Projet de développement web - Test Api et Flux XML";
    $desc = "Projet de développement web - Test APi et Flux XML";
    $keywords = "Projet";
    $update_date= "11/03/2024";
    $update_hour= "14:43";
    require "./include/functions.inc.php";
    require "./include/header.inc.php";
?>
        <main>
            <section>
                <h2>Test API de la Nasa</h2>
                    <?php echo affichageApi() ?>
            </section>
            <section>
                <h2>Affichage de la géolocalisation de l'utilisateur via un flux XML</h2>
                    <?php echo affichageLocalisationXML() ?>
            </section>
            <section>
                <h2>Affichage de la géolocalisation de l'utilisateur via un flux JSON</h2>
                    <?php echo affichageLocalisationJSON() ?>
            </section>
        </main>
<?php require "./include/footer.inc.php" ?>