window.addEventListener("load",dessinerCarte);

// fonction de mise en place de la carte.
// Suppose qu'il existe dans le document
// un élément possédant id="cartecampus"
function dessinerCarte(){
    if(typeof jsonMap !== 'undefined' && jsonMap.data.length > 0){
        s = document.getElementById('stationInformations');
        s.classList.remove('cache');
        s.innerHTML = jsonMap.data.length  + " stations trouvées";
        var map = L.map('cartecampus').setView([jsonMap.data[0].latitude, jsonMap.data[0].longitude], 16);
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        //pour chaque stations
        for(var i = 0; i < jsonMap.data.length; i++){
            var latitude = jsonMap.data[i].latitude;
            var longitude = jsonMap.data[i].longitude;
            L.marker([latitude, longitude]).addTo(map).bindPopup('Latitude: ' + latitude + '</br>Longitude: ' + longitude).on('click', afficherInformations);
        }
    }
}

function afficherInformations(e){
    station = stationIdDepuisLatLong(e.latlng.lat, e.latlng.lng)
    var nom = station.nom;
    if(nom === null){
        nom = 'Nom inconnu';
    }
    var marque = station.marque;
    if(marque === null){
        marque = 'Marque inconnue';
    }
    
    var informations = jsonMap.data.length  + " stations trouvées</br></br><strong>Informations</strong>";
    informations += "</br>Nom: " + nom;
    informations += "</br>Marque: " + marque;
    informations += "</br>Adresse: " + station.adresse 
    informations += "</br>Ville: " + station.ville
    informations += "</br>Code Postal: " + station.cp;
    
    informations += "</br></br><strong>Services:</strong> "
    for(var i = 0; i < station.services.length; i++){
        informations += "</br>  " + station.services[i];
    }

    informations += "</br></br><strong>Carburants:</strong> "
    for(var i = 0; i < station.prix.length; i++){
        informations += "</br>  " + station.prix[i].libelleCarburant + " : " + station.prix[i].valeur + " €";
    }

    document.getElementById('stationInformations').innerHTML = informations;
}

function stationIdDepuisLatLong(lat, long){
    for(var i = 0; i < jsonMap.data.length; i++){
        if(jsonMap.data[i].latitude == lat && jsonMap.data[i].longitude == long){
            return jsonMap.data[i];
        }
    }
    return null;
}