let app = Vue.createApp({
    data() {
        return {
            selectedYear: '',
            caseChecked: true,
            caseDisabled: true
        };
    },
    computed: {
    },
    methods: {
        cherche_annee(){
            this.caseChecked = false;
            this.caseDisabled = false;
            acci_anneeSelect = accidents.features.filter(feature => {
                return feature.properties.an === this.selectedYear;
            });
            if (acci_paramSelect != null) {
                acci_select = acci_paramSelect.filter(element => {
                    return acci_anneeSelect.includes(element);
                });
            }
            else {
                acci_select = acci_anneeSelect;
            }
            
            /*var piste_annee = pistes.features.filter(feature => {
                return feature.properties.annee <= this.selectedYear;
            });*/
                
            map.removeLayer(acciLayer);
            // map.removeLayer(pistesLayer);

            // geojson des accidents de l'annee
            var geojsonAcci = {
                type: "FeatureCollection",
                features: acci_select
            };

            // geojson des pistes de l'annee
            /*var geojsonPistes = {
                type: "FeatureCollection",
                features: piste_annee
            };*/
            // Création de la nouvelle couche des pistes et affichage sur la carte
            // pistesLayer = creeCouchePistes(geojsonPistes).addTo(map);

            // Création de la nouvelle couche des accidents et affichage sur la carte
            acciLayer = creeCoucheAccidents(geojsonAcci).addTo(map);


        },

        annule_annee() {
            this.selectedYear='';
            this.caseDisabled = true;

            acci_anneeSelect = accidents.features;
            if (acci_paramSelect != null) {
                acci_select = acci_paramSelect.filter(element => {
                    return acci_anneeSelect.includes(element);
                });
            }
            else {
                acci_select = acci_anneeSelect;
            }
            map.removeLayer(acciLayer);
            // geojson des accidents de l'annee

            // Création de la nouvelle couche des accidents et affichage sur la carte
            acciLayer = creeCoucheAccidents(acci_select).addTo(map);
        }
    }

}).mount('#app');

// crée la couche contenant les pistes contenues dans "objet"
function creeCouchePistes(objet) {
    return L.geoJSON(objet, {
        style: function (feature) {
            // Récupérer la valeur de la colonne ame_d
            const ame_d = feature.properties.ame_d;

            // Définir la couleur en fonction de la valeur de ame_d
            let couleur = null;
            if (ame_d === 'COULOIR BUS+VELO') {
                couleur = 'yellow';
            } else if (ame_d === 'VOIE VERTE' || ame_d === 'AMENAGEMENT MIXTE PIETON VELO HORS VOIE VERTE') {
                couleur = 'green';
            } else if (ame_d === 'PISTE CYCLABLE') {
                couleur = 'blue'; // Couleur par défaut
            } else if (ame_d === 'BANDE CYCLABLE') {
                couleur = 'purple'; // Couleur par défaut
            }
            else {
                couleur = 'orange';
            }

            // Retourner le style avec la couleur définie
            return {
                color: couleur, // Couleur de la ligne
                weight: 2, // Épaisseur de la ligne
                opacity: 1 // Opacité de la ligne
            };
        }
    });
}


// crée la couche contenant les accidents contenus dans "objet"
function creeCoucheAccidents(objet) {
    return L.geoJSON(objet, {
        pointToLayer: function (feature, latlng) {
            const marker = L.circleMarker(latlng, {
                radius: 4,
                fillColor: "red",
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            });
            // Récupération des informations de l'accident correspondant
            const properties = feature.properties;
            const popupContenu = `
            <b>Date:</b> ${properties.date}<br>
            <b>Type d'intersection :</b> ${properties.int}<br>
            <b>Vitesse max :</b> ${properties.vma}<br>
            <b>Type de collision :</b> ${properties.col}<br>
            <b>Conditions atmosphériques :</b> ${properties.atm}<br>
            <b>Catégorie de route :</b> ${properties.catr}<br>
            <b>Etat de la route :</b> ${properties.surf}<br>
            <b>Infrastructure de la route :</b> ${properties.infra}<br>
            <b>Catégorie du véhicule :</b> ${properties.catv}<br>
            <b>Circulation :</b> ${properties.circ}<br>

            `;
            // Ajout d'une pop-up au marqueur
            marker.bindPopup(popupContenu);
            return marker;
        }
    })
}


var accidents = null;
var pistes = null;
var acci_select = null;
var acci_anneeSelect = null;
var acci_paramSelect = null;

// PARAMETRES
var checkboxes = document.querySelectorAll('.dropdown-menu input[type="checkbox"]');

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

        // accidents selectionnes avec la luminosité
        acci_paramSelect = accidents.features.filter(feature => {
            return lumi_select.includes(feature.properties.lum);
        });
        // accidents en luminosité et en année
        if (acci_anneeSelect != null) {
            acci_select = acci_anneeSelect.filter(element => {
                return acci_paramSelect.includes(element);
            });
        }
        else {
            acci_select = acci_paramSelect;
        }
        map.removeLayer(acciLayer);
        acciLayer = creeCoucheAccidents(acci_select).addTo(map);

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
        map.removeLayer(planLayer);
        pistesLayer.addTo(map);
        acciLayer.bringToFront();

    } else {
        // supprimer les autres couches sauf la couche de base
        map.removeLayer(pistesLayer);
        // Si le plan vélo est caché, l'afficher
        button.textContent = 'Masquer le plan vélo';
        button.classList.add('clique'); // Ajouter la classe de grisage
        planLayer.addTo(map);
        acciLayer.bringToFront();
        
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


// Récupération de tous les accidents
fetch('recupere_acci')
.then(result => result.json())
.then(result => {
    accidents = result;
})

// Récupération de toutes les pistes cyclables
fetch('recupere_pistes')
.then(result => result.json())
.then(result => {
    pistes = result;
    pistesLayer = creeCouchePistes(pistes).addTo(map);
    acciLayer = creeCoucheAccidents(accidents).addTo(map);
})


// Récupération de toutes les pistes cyclables
fetch('recupere_plan')
.then(result => result.json())
.then(result => {

planLayer = creeCouchePistes(result);
})