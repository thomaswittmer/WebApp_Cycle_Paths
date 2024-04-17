let app = Vue.createApp({
    data() {
        return {
            selectedYear: '',
        };
    },
    computed: {
    },
    methods: {
        cherche_annee(){
            var acci_annee = accidents.features.filter(feature => {
                return feature.properties.an === this.selectedYear;
            });
            var piste_annee = pistes.features.filter(feature => {
                return feature.properties.annee <= this.selectedYear;
            });
                
            map.removeLayer(acciLayer);
            map.removeLayer(pistesLayer);

            // affichage des accidents de l'annee
            var geojsonAcci = {
                type: "FeatureCollection",
                features: acci_annee
            };

            // affichage des pistes de l'annee
            var geojsonPistes = {
                type: "FeatureCollection",
                features: piste_annee
            };
            // Créer une couche GeoJSON avec pointToLayer pour personnaliser l'affichage
            pistesLayer = L.geoJSON(geojsonPistes, {
                style: {
                    color: 'blue', // Couleur de la ligne
                    weight: 2, // Épaisseur de la ligne
                    opacity: 1 // Opacité de la ligne
                }
            }).addTo(map);
            
            // Créer une couche GeoJSON avec pointToLayer pour personnaliser l'affichage
            acciLayer = L.geoJSON(geojsonAcci, {
                pointToLayer: function (feature, latlng) {
                    return L.circleMarker(latlng, {
                        radius: 2.5,
                        fillColor: "red",
                        color: "#000",
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    });
                }
            }).addTo(map);


        }
    }

}).mount('#app');

var accidents = null;
var pistes = null;

// PARAMETRES
var checkboxes = document.querySelectorAll('input[type="checkbox"]');

// Ajouter un écouteur d'événements à chaque bouton radio
checkboxes.forEach(function(check) {
    check.addEventListener('change', function() {
        let lumi_select = [];
        let meteo_select = [];
        // Parcourir toutes les cases cochées et les ajouter à FormData
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                if (checkbox.parentNode.classList.contains('lumi')) {
                    // Si la case à cocher appartient à la classe "dropdown-item lumi"
                    lumi_select.push(checkbox.value);
                } else if (checkbox.parentNode.classList.contains('meteo')) {
                    // Si la case à cocher appartient à la classe "dropdown-item meteo"
                    meteo_select.push(checkbox.value);
                }
            }
        });
        // Créer un objet FormData et ajouter les valeurs des cases cochées comme un tableau
        let donnees = new FormData();
        donnees.append('lumi', lumi_select); // Utilisation de 'lumi[]' pour créer un tableau de valeurs
        donnees.append('meteo', meteo_select); // Utilisation de 'meteo[]' pour créer un tableau de valeurs
        console.log(donnees);
        fetch('/lumino', {
        method: 'post',
        body: donnees
        })
        .then(r => r.json())
        .then(r => {
        console.log(r)
        })    

    });
});


// PLAN VELO
let plan = document.getElementById("plan");
var planVisible = false; // Indique si le plan vélo est visible ou non

plan.addEventListener('click', function() {
    var button = this;
    // Vérifie l'état actuel du bouton
    if (planVisible) {
        // Si le plan vélo est visible, le masquer
        button.textContent = 'Plan Vélo 2024';
        button.classList.remove('clique'); // Supprimer la classe de grisage
        console.log(planLayer);
        map.removeLayer(planLayer);
        pistesLayer.addTo(map);
        acciLayer.addTo(map);

    } else {
        // supprimer les autres couches sauf la couche de base
        map.eachLayer(function(layer) {
            if (layer !== fondCarte) {
                map.removeLayer(layer);
            }
        });
        // Si le plan vélo est caché, l'afficher
        button.textContent = 'Masquer le plan vélo';
        button.classList.add('clique'); // Ajouter la classe de grisage
        planLayer.addTo(map);
        
    }
    // Mettre à jour l'état du bouton
    planVisible = !planVisible;

});

// CARTE
var pistesLayer = null;
var acciLayer = null;
var planLayer = null;
var map = L.map('map').setView([48.866667, 2.333333], 12);

var fondCarte = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
}).addTo(this.map);

var fondCarte2 = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 28,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
});


// passage en mode plan si la carte est très zoomée et en mode aérien si on est loin de la zone
map.on('zoomend', () => {
    if (this.map.getZoom() >= 18) {
        this.map.removeLayer(fondCarte);
        this.map.addLayer(fondCarte2);
    } else {
        this.map.removeLayer(fondCarte2);
        this.map.addLayer(fondCarte);
    }
});


// Récupération de toutes les pistes cyclables
fetch('recupere_pistes')
.then(result => result.json())
.then(result => {
    pistes = result;
    pistesLayer = L.geoJSON(result, {
        style: {
            color: 'blue', // Couleur de la ligne
            weight: 2, // Épaisseur de la ligne
            opacity: 1 // Opacité de la ligne
        }
    }).addTo(map);
})

// Récupération de tous les accidents
fetch('recupere_acci')
.then(result => result.json())
.then(result => {
    accidents = result;
    console.log(result);
    acciLayer = L.geoJSON(result, {
        pointToLayer: function (feature, latlng) {
            return L.circleMarker(latlng, {
                radius: 2.5,
                fillColor: "red",
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            });
        }
    }).addTo(map);
})


// Récupération de toutes les pistes cyclables
fetch('recupere_plan')
.then(result => result.json())
.then(result => {

planLayer = L.geoJSON(result, {
    style: {
        color: 'blue', // Couleur de la ligne
        weight: 2, // Épaisseur de la ligne
        opacity: 1 // Opacité de la ligne
    }
});
})