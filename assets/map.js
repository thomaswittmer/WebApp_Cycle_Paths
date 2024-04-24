let app = Vue.createApp({
    data() {
        return {
            selectedYear: '',
            suggestions: [],
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
                
            map.removeLayer(acciLayer);
            //map.removeLayer(pistesLayer);

            // geojson des accidents de l'annee
            var geojsonAcci = {
                type: "FeatureCollection",
                features: acci_select
            };

            // Création de la nouvelle couche des accidents et affichage sur la carte
            acciLayer = creeCoucheAccidents(geojsonAcci).addTo(map);

            // creation de la couche des pistes existant l'annee selectionnee et affichage sur la carte
            /*let send = new FormData();
            send.append('annee', this.selectedYear);
            fetch('recup_annee', {
                method: 'post',
                body: send
              })
              .then(r => r.json())
              .then(r => {
                pistesLayer = creeCouchePistes(r).addTo(map);
              })*/

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
                couleur = '#EC1DD0  ';
            } else if (ame_d === 'VOIE VERTE' || ame_d === 'AMENAGEMENT MIXTE PIETON VELO HORS VOIE VERTE') {
                couleur = '#63DE6E';
            } else if (ame_d === 'PISTE CYCLABLE') {
                couleur = '#1D3FD9'; 
            } else if (ame_d === 'BANDE CYCLABLE') {
                couleur = '#4DC0EF'; 
            }
            else {
                couleur = '#C1A4BD  ';
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

// cree couche plan velo selon statut 
function creeCouchePlan(objet) {
    return L.geoJSON(objet, {
        style: function (feature) {
            // Récupérer la valeur de la colonne statut
            const statut = feature.properties.statut;

            // Définir la couleur en fonction de la valeur de ame_d
            let couleur = null;
            if (statut === 'à réaliser') {
                couleur = 'orange';
            } else if (statut=== 'existant' ) {
                couleur = 'blue';
            }
            else{
                couleur ='gray'
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
    //tentative cluster
    var clusterGroup = L.markerClusterGroup({
        maxClusterRadius: 50, 
        disableClusteringAtZoom: 15 //fin des clusters quand on zoome
    }); 

    return L.geoJSON(objet, {
        pointToLayer: function (feature, latlng) {
            const marker = L.circleMarker(latlng, {
                radius: 4.5,
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
            <button onclick="window.location.href='map4?accidentId=${properties.num_acc}'">Voir en 3D</button>

            `;
            
            // Ajout d'une pop-up au marqueur
            marker.bindPopup(popupContenu);
            clusterGroup.addLayer(marker);
            //return marker;
            return clusterGroup;
        }
    });
}


// Fonction pour mettre à jour la légende
function mettreAJourLegende(etatCouches) {
    const legendElement = document.getElementById('legend');
    if (etatCouches.planVisible) {
        legendElement.innerHTML = `
            <h3>Légende</h3>
            <div><span class="legend-color" style="background-color: blue;"></span> Existant </div>
            <div><span class="legend-color" style="background-color: orange;"></span> A réaliser </div>
        `;
    } else {
        legendElement.innerHTML = `
            <h3>Légende</h3>
            <div><span class="legend-color" style="background-color: #1D3FD9;"></span> piste cyclable</div>
                <div><span class="legend-color" style="background-color: #63DE6E;"></span> voie verte / aménagement mixte</div>
                <div><span class="legend-color" style="background-color: #EC1DD0;"></span> couloir bus + vélo</div>
                <div><span class="legend-color" style="background-color: #4DC0EF;"></span> bande cyclable</div>
                <div><span class="legend-color" style="background-color: #C1A4BD ;"></span> voie mixte</div>
                
        `;
    }
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

// gestion couleur des pistes 

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
    mettreAJourLegende({ planVisible: !planVisible });
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


//la barre de recherche des adresses

// test ESRI 
const api = "AAPK07603a779b2f4f9dab2e28dc9fde0f05IJ2S4Lh8g5-lGWF4WEkWb1aRCDmpSK4NEfHQdWICq1wU-r9GM1MLdWTUL_qxj0xt";
const geocoder = L.esri.Geocoding.geocodeService({
    apikey: api
});
const searchInput = document.getElementById('research_input');
const suggestions = document.getElementById('suggestions');


searchInput.addEventListener('input', function() {
    const query = this.value;

    geocoder.suggest().text(query).run((error, results, response) => {
      if (error) {
        console.error('Error fetching address suggestions:', error);
        return;
      }

      suggestions.innerHTML = ''; // Efface les suggestions précédentes

//propose des suggestions en dessous de la barre
      results.suggestions.forEach(suggestion => {
        const address = suggestion.text;
        const location = suggestion.location;
        // Vérifier si la suggestion se trouve dans la zone géographique de Paris
        /*if (
            address.toLowerCase().includes('paris')
            location.x >= 2.224199 &&
            location.x <= 2.469920 &&
            location.y >= 48.815573  &&
            location.y <= 48.902145
            )*/{         
        const a = document.createElement('a');
        a.classList.add('dropdown-item');
        a.textContent = address;
        a.addEventListener('click', function() {
            searchInput.value = address; 
            suggestions.style.display = 'none'; 
          });
        suggestions.appendChild(a);
            }
      });

      if (results.suggestions.length > 0) {
        suggestions.style.display = 'block';
      } else {
        suggestions.style.display = 'none';
      }
    });
  });

//zoome sur l'endroit selectionne
  suggestions.addEventListener('click', function(event) {
    const target = event.target;
    if (target && target.matches('a.dropdown-item')) {
      const address = target.textContent.trim();
      geocoder.geocode().text(address).run((error, results, response) => {
        if (error) {
          console.error('Error geocoding address:', error);
          return;
        }
        if (results.results.length > 0) {
          const location = results.results[0].latlng;
          map.setView(location, 18); 
        }
      });
      searchInput.value = address; 
      suggestions.style.display = 'none'; 
    }
  });


// Cacher le menu déroulant si on clique en dehors
  document.addEventListener('click', function(event) {
    if (!event.target.closest('.input-group')) {
      suggestions.style.display = 'none';
    }
  });



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
planLayer = creeCouchePlan(result);
})
