<?php
    declare(strict_types=1);
    define("TOKEN", "c7cef527-189f-48cf-be7a-2f101887224d");
    define("URL", "https://".TOKEN."@api.sncf.com/v1/");

    function affichageApi(): String {
        $clefapi = "AugsigvysPt1vhXBzcxNDUzyrz47n2rrMX9YWMOJ";
        $url = "https://api.nasa.gov/planetary/apod?api_key=$clefapi";
        $fluxjson = file_get_contents($url);
        $donnee = json_decode($fluxjson, true);
        if($donnee['media_type'] == 'image') {
            $res = "<img src =\"".$donnee['url']."\" alt=\"".$donnee['title']."\">\n";
        }
        else if ($donnee['media_type'] == 'video') {
            $res = "<video controls>\n\t<source src=\"".$donnee['url']."\">\n\t".$donnee['title']."\n</video>\n";
        }
        return $res;
    }

    function affichageLocalisationXML(): String{
        $ip = $_SERVER["REMOTE_ADDR"];
        $url = "http://www.geoplugin.net/xml.gp?ip=$ip";
        $response = file_get_contents($url);
        if ($response !== false) {
            $xml = simplexml_load_string($response);
            if ($xml !== false) {
                $city = $xml->geoplugin_city;
                $region = $xml->geoplugin_region;
                $country = $xml->geoplugin_countryName;
                $latitude = $xml->geoplugin_latitude;
                $longitude = $xml->geoplugin_longitude;
                $res = "Ville : $city Région : $region Pays : $country Latitude : $latitude Longitude : $longitude";
            } else {
                echo "Erreur lors du chargement des données XML.";
            }
        } else {
            echo "Erreur lors de la requête HTTP GET vers l'API Geoplugin.";
        }
        return $res;
    }

    function latUtilisateur(): float{
        $ip = $_SERVER["REMOTE_ADDR"];
        $url = "http://www.geoplugin.net/xml.gp?ip=$ip";
        $response = file_get_contents($url);
        if ($response !== false) {
            $xml = simplexml_load_string($response);
            if ($xml !== false) {
                $latitude = $xml->geoplugin_latitude;
            }
            else{
                echo "Erreur lors du chargement des données XML.";
            }
        }
        else{
            echo "Erreur lors de la requête HTTP GET vers l'API Geoplugin.";
        }
        return floatval($latitude);
        
    }
    
    function lonUtilisateur(): float{
        $ip = $_SERVER["REMOTE_ADDR"];
        $url = "http://www.geoplugin.net/xml.gp?ip=$ip";
        $response = file_get_contents($url);
        if ($response !== false) {
            $xml = simplexml_load_string($response);
            if ($xml !== false) {
                $longitude = $xml->geoplugin_longitude;
            }
            else{
                echo "Erreur lors du chargement des données XML.";
            }
        }
        else{
            echo "Erreur lors de la requête HTTP GET vers l'API Geoplugin.";
        }
        return floatval($longitude);
        
    }

    function affichageLocalisationJSON(): String{
        $ip = $_SERVER["REMOTE_ADDR"];
        $url = "https://ipinfo.io/$ip/geo";
        $fluxjson = file_get_contents($url);
        $donnee = json_decode($fluxjson, true);
        $ville = $donnee['city'];
        $region = $donnee['region']; 
        $pays = $donnee['country']; 
        $postal = strval($donnee['postal']);  
        $res = "Ville : $ville Région : $region Pays : $pays Code Postal : $postal";
        return $res;
    }

    function obtenirInfoGare($nom_gare) {
        $api_key = 'c7cef527-189f-48cf-be7a-2f101887224d';
        $url = "https://api.sncf.com/v1/coverage/sncf/places?q=".urlencode($nom_gare)."&type[]=stop_area&key=$api_key";
        $fluxjson = file_get_contents($url);
        if ($fluxjson !== false) {
            $donnee = json_decode($fluxjson, true);
            if (!empty($donnee['places'])) {
                $gare_info = $donnee['places'][0];
                return $gare_info;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

/*
    function obtenirCorrespondance($nom_gare){
        $api_key = 'c7cef527-189f-48cf-be7a-2f101887224d';
        $url = "https://api.sncf.com/v1/coverage/sncf/places?q=".urlencode($nom_gare)."&type[]=stop_area&key=$api_key";
        $fluxjson = file_get_contents($url);
        if ($fluxjson !== false) {
            return "Erreur lors de la connexion à l'API"; 
        }else {
            $donnee = json_decode($fluxjson, true);
            if(isset($donnees['places']) && !empty($donnees['places'])) {
                $gare_info = $donnees['places'][0];
                $url_correspondances = "https://api.sncf.com/v1/coverage/sncf/stop_areas/$gare_info['id']}/connections?key=$api_key";
                $fluxjson_correspondances = file_get_contents($url_correspondances);
                if ($fluxjson_correspondances !== false) {
                     $correspondances = json_decode($fluxjson_correspondances, true);

                return $correspondances;
                }
        }
    }
    
    
*/
    
    function distanceEntrePoints($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371; // Rayon moyen de la Terre en kilomètres
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earth_radius * $c; // Distance en kilomètres
        return $distance;
    }

    function gareProche(): string {
        $user_latitude = latUtilisateur();
        $user_longitude = lonUtilisateur();
        $api_key = 'c7cef527-189f-48cf-be7a-2f101887224d';
        $url = "https://api.sncf.com/v1/coverage/sncf/coord/$user_latitude;$user_longitude/places?type[]=stop_area&key=$api_key";
        $fluxjson = file_get_contents($url);
    
        if ($fluxjson !== false) {
            $donnee = json_decode($fluxjson, true);
            $gares = $donnee['places'];
            $distances_gares = array();
            $res = "";
            foreach ($gares as $gare) {
                $gare_latitude = floatval($gare['stop_area']['coord']['lat']);
                $gare_longitude = floatval($gare['stop_area']['coord']['lon']);
                $distance = distanceEntrePoints($user_latitude, $user_longitude, $gare_latitude, $gare_longitude);
                $distances_gares[$gare['name']] = $distance;
            }
            asort($distances_gares);
            $distances_gares = array_slice($distances_gares, 0, 5);
            $res .= "<h3>Les 5 gares les plus proches de l'utilisateur :</h3>";
            $res .= "<ul>";
            foreach ($distances_gares as $gare_name => $distance) {
                $res .= "<li>$gare_name (Distance : $distance km)</li>";
            }
            $res .= "</ul>";
        } else {
            $res = "Erreur lors de la récupération des données des gares";
        }
        return $res;
    }

    function decodeTemps(string $temps): string {
        $datetime = DateTime::createFromFormat('Ymd\THis', $temps);
        if ($datetime instanceof DateTime) {
            return $datetime->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    function afficherProchainsDeparts(string $id): string {
        $url = URL."coverage/sncf/stop_areas/".$id."/departures";
        $fluxjson = file_get_contents($url);
        $res = "<ul>\n";
        if($fluxjson !== false) {
            $donnees = json_decode($fluxjson, true);
            $destinations = array();
            foreach($donnees['departures'] as $departure) {
                $destination = $departure['display_informations']['direction'];
                $heure_depart = decodeTemps($departure['stop_date_time']['departure_date_time']);
                if(!array_key_exists($destination, $destinations)) {
                    $destinations[$destination] = array();
                }
                $destinations[$destination][] = $heure_depart;
            }
            foreach($destinations as $destination => $horaires) {
                $res .= "\t\t\t\t\t\t<li>Prochains départs à destination de : ".$destination.":\n";
                $res .= "\t\t\t\t\t\t\t<ul>\n";
                foreach($horaires as $heure) {
                    $res .= "\t\t\t\t\t\t\t\t<li>".$heure."</li>\n";
                }
                $res .= "\t\t\t\t\t\t\t</ul>\n";
                $res .= "\t\t\t\t\t\t</li>\n";
            }
        }
        $res .= "\t\t\t\t\t</ul>\n";
        $res .= "\t\t\t\t\t<a href=\"index.php\" style=\"display:inline-block;margin-top:20px;padding:10px;background-color:#007bff;color:white;text-decoration:none;border-radius:5px;\">Retour</a>\n";
        return $res;
    }
?>
