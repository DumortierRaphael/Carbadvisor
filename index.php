<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
    <title>Carbadvisor</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
        integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
        integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
        crossorigin=""></script>
        <?php
            if(isset($_POST['valid'])){
                try{
                    require('lib/request.php');
                    if($json_data['status'] === 'ok'){
                        echo "<script>jsonMap = " . $res . "</script>";
                    }else{
                        throw new Exception($json_data['message']);
                    }
                }catch(Exception $e){
                    $errorMessage = $e->getMessage();
                }
            }
        ?> 
    <script src="libjs/scriptCarte.js"></script>
    <script src="libjs/scriptDisplay.js"></script>
</head>

<body>
    <header>
        <ul>
            <li><span class="title">Carbadvisor</span></li>
            <li><a href="javascript:void(0)" onclick="genericDisplay(event, 'recherche')">Accueil</a></li>
            <li><a href="javascript:void(0)" onclick="genericDisplay(event, 'profil')">Profil</a></li>
            <li><a href="javascript:void(0)" onclick="genericDisplay(event, 'post')">Posts</a></li>
            <li><a href="javascript:void(0)" onclick="genericDisplay(event, 'connexion')">Connexion</a></li>
        </ul> 
    </header>

    <?php
        if (isset($errorMessage)){
            echo "<section id='errorMessage'>\n";
            echo "<p> Erreur : $errorMessage</p>\n";
            echo "</section>"; 
        }
    ?>

    <div class="connexion" hidden>
        <form action="services/login.php" method="POST">
            <fieldset>
                <div>
                    <label for="login">Login: </label><input type="text" name="login" required="true" id="login">
                </div>
                <div>
                    <label for="mdp">Mot de passe: </label><input type="text" name="mdp" required="true" id="mdp">
                </div>
                <div>
                    <input type="submit" name="valid" value="Connexion">
                </div>
            </fieldset>
            <a href="javascript:void(0)" onclick="genericDisplay(event, 'inscription')">S'inscrire</a>
        </form>
    </div>

    <div class="inscription" hidden>
        <form action="services/createUtilisateur.php" method="POST">
            <fieldset>
                <div>
                    <label for="login">Login: </label><input type="text" name="login" required="true" id="login">
                </div>
                <div>
                    <label for="mdp">Mot de passe: </label><input type="text" name="mdp" required="true" id="mdp">
                </div>
                <div>
                    <label for="pseudo">Pseudo: </label><input type="text" name="pseudo" required="true" id="pseudo">
                </div>
                <div>
                    <label for="mail">Mail: </label><input type="text" name="mail" required="true" id="mail">
                </div>
                <div>
                    <label for="ville">Ville: </label><input type="text" name="ville" required="true" id="ville">
                </div>
                <div>
                    <label for="description">Description: </label><input type="text" name="description" required="true" id="description">
                </div>
                <div>
                    <input type="submit" name="valid" value="Connexion">
                </div>
            </fieldset>
        </form>
    </div>

    <div class="recherche">
        <section id="map">
            <div id="stationInformations" class='cache'></div><div id="cartecampus"></div>
        </section>
        <form action="index.php" method="POST">
            <fieldset>
                <div>
                    <label for="commune">Nom de la commune: </label> <input type="text" name="commune" required="true" id="commune">
                </div>
                <div>
                    <label for="rayon">Rayon de recherche: </label> <input type="number" name="rayon" min="0">
                </div>
                <p>De quel(s) carburant(s) avez-vous besoin?</p>
                <div>
                    <input type="checkbox" name="carburants[]" value="5" id="E10" /> <label for="E10">E10</label>
                </div>
                <div>
                    <input type="checkbox" name="carburants[]" value="2" id="SP95" /> <label for="SP95">SP95</label>
                </div>
                <div>
                    <input type="checkbox" name="carburants[]" value="6" id="SP98" /> <label for="SP98">SP98</label>
                </div>
                <div>
                    <input type="checkbox" name="carburants[]" value="4" id="GPLc" /> <label for="GPLc">GPLc</label>
                </div>
                <div>
                    <input type="checkbox" name="carburants[]" value="3" id="E85" /> <label for="E85">E85</label>
                </div>
                <div>
                    <input type="checkbox" name="carburants[]" value="1" id="Gazole" /> <label for="Gazole">Gazole</label>
                </div>
                <input type="submit" name="valid" value="Valider">
            </fieldset>
        </form>
    </div>
</body>

</html>