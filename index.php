<?php
    $title= "Accueil";
    $desc = "Projet de développement web - Test APi";
    $keywords = "Projet";
    $update_date= "11/03/2024";
    $update_hour= "14:43";
    require "./include/functions.inc.php";
    require "./include/header.inc.php";
?>
        <main>
            <section>
                <h2>Recherche de gare</h2>
                <form action="" method="post">
                <label for="nom_gare">Nom de la gare :</label>
                <input type="text" id="nom_gare" name="nom_gare" required>
                <input type="submit" value="Rechercher">
                </form>
                <?php
                    if (isset($_POST['nom_gare'])) {
                        $nom_gare = $_POST['nom_gare'];
                        $api_key = 'c7cef527-189f-48cf-be7a-2f101887224d';
                        $url = "https://api.sncf.com/v1/coverage/sncf/places?q=".urlencode($nom_gare)."&type[]=stop_area&key=$api_key";                        
                        $fluxjson = file_get_contents($url);
                        if ($fluxjson !== false) {
                            $donnee = json_decode($fluxjson, true);
                            $suggestions = array();
                            if (isset($donnee["places"]) && is_array($donnee["places"])) {
                                foreach ($donnee['places'] as $place) {
                                    $suggestions[] = $place['name'];
                                }
                            }
                            echo "<h3>Résultats de la recherche pour '$nom_gare'</h3>";
                            if (!empty($suggestions)) {
                                echo "<ul>";
                                foreach ($suggestions as $gare) {
                                    echo "<li><a href='?nom_gare=".urlencode($gare)."'>$gare</a></li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "Aucune gare trouvée pour '$nom_gare'";
                            }
                        } else {
                            echo "Erreur lors de la récupération des suggestions";
                        }
                    }
                    if(isset($_GET['nom_gare'])) {
                        $gare_selectionnee = $_GET['nom_gare'];
                        $gare_info = obtenirInfoGare($gare_selectionnee);
                        if ($gare_info !== null) {
                            echo "<h3>Informations sur la gare : $gare_selectionnee</h3>";
                            echo "<p>Nom : ".$gare_info['name']."</p>";
                            echo "<p>Code UIC : ".$gare_info['stop_area']['codes'][0]['value']."</p>";
                            if (isset($gare_info['stop_area']['stop_area_type'])) {
                                echo "<p>Type : " . $gare_info['stop_area']['stop_area_type'] . "</p>";
                            } else {
                                echo "<p>Type : Information non disponible</p>";
                            }
                            if (isset($gare_info['stop_area']['coord']['lat']) && isset($gare_info['stop_area']['coord']['lon'])) {
                                echo "<p>Coordonnées : Latitude " . $gare_info['stop_area']['coord']['lat'] . ", Longitude " . $gare_info['stop_area']['coord']['lon'] . "</p>";
                            } else {
                                echo "<p>Coordonnées : Information non disponible</p>";
                            }
                            if (isset($gare_info['administrative_regions'][0]['name'])) {
                                echo "<p>Ville : " . $gare_info['administrative_regions'][0]['name'] . "</p>";
                            } else {
                                echo "<p>Ville : Information non disponible</p>";
                            }
                        } else {
                            echo "Aucune information disponible pour la gare '$gare_selectionnee'";
                        }
                    }
                    echo gareProche();                
                ?>
            </section>
        </main>
<?php require "./include/footer.inc.php" ?>