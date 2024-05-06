let app = Vue.createApp({
    data() {
        return {
            selectedYear: '2016-2022',
            selectedMonth: '',
            suggestions: [],
            caseChecked: true,
            moisChecked: false,
            caseDisabled: true,
            isAutoPlaying: false,
            autoPlayInterval: null,
            autoPlayInterval: null,
            isPaused: false
        };
    },
    computed: {
        formattedDate() {
            if (this.selectedMonth === '') {
                return '2016-2022';
            }
            let startDate = new Date(2016, 0); //début du curseur
            let selectedDate = new Date(startDate.getFullYear(), startDate.getMonth() + this.selectedMonth); //date actuelle
            let mois = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            return `${mois[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
        }
    },
    methods: {
        startAutoPlay() {
            if (!this.isAutoPlaying) {
                this.isAutoPlaying = true;
                this.setAutoPlayInterval(1);
            }
        },
        
        setAutoPlaySpeed(speed) {
            if (this.isAutoPlaying) {
                clearInterval(this.autoPlayInterval);
                this.setAutoPlayInterval(speed);
            }
        },
        
        setAutoPlayInterval(speed) {
            let interval = 1000; //interval par défaut pour la vitesse x1
        
            if (speed === 2) {
                interval = 500; //vitesse x2
            } else if (speed === 0.5) {
                interval = 2000; //vitesse x0.5
            }
        
            this.autoPlayInterval = setInterval(() => {
                this.nextDate();
            }, interval);
        },
        
        stopAutoPlay() {
            this.isAutoPlaying = false;
            clearInterval(this.autoPlayInterval);
            this.caseChecked = true;
            this.selectedYear = '';
            this.annule_annee();
        },
        
        pauseAutoPlay() {
            if (this.isAutoPlaying) {
                clearInterval(this.autoPlayInterval);
                this.isAutoPlaying = false;
                this.isPaused = true;
            }
        },

        nextDate() {
            if (this.moisChecked) {
                if (this.selectedMonth === '') {
                    this.selectedMonth = 0;
                    this.cherche_mois_annee();
                } else {
                    let currentMonth = parseInt(this.selectedMonth);
                    if (currentMonth < 83) {
                        this.selectedMonth = (currentMonth + 1).toString();
                        this.cherche_mois_annee();
                    } else {
                        this.stopAutoPlay();
                    }
                }
            }
            else {
                if (this.selectedYear === '2016-2022') {
                    this.selectedYear = '2016';
                    this.cherche_annee();
                }
                else {
                    let currentYear = parseInt(this.selectedYear);
                    if (currentYear < 2022) {
                        this.selectedYear = (currentYear + 1).toString();
                        this.cherche_annee();
                    }
                    else {
                        this.stopAutoPlay();
                    }
                }
            }
        },

        cherche_annee() {
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

        cherche_mois_annee() {
            this.caseChecked = false;
            this.caseDisabled = false;
            let year = Math.trunc(this.selectedMonth / 12) + 2016;
            let month = this.selectedMonth - (year - 2016) * 12 + 1;

            acci_anneeSelect = accidents.features.filter(feature => {
                let annee = feature.properties.an;
                let mois = feature.properties.mois;
                return (annee == year && mois == month);
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

            var geojsonAcciMoisAnnee = {
                type: "FeatureCollection",
                features: acci_select
            };

            acciLayer = creeCoucheAccidents(geojsonAcciMoisAnnee).addTo(map);
        },

        annule_annee() {
            this.selectedYear = '2016-2022';
            this.caseDisabled = true;
            this.selectedMonth = '';
            this.caseChecked = true;

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



        },

    }

}).mount('#app');

//Empêcher que le clic sur lecurseur de la barre temporelle fasse bouger la carte
var dateSlider = document.getElementById('dateSlider');
dateSlider.addEventListener('mousemove', function (event) {
    event.stopPropagation();
});



// crée la couche contenant les pistes contenues dans "objet"
function creeCouchePistes(objet) {
    return L.geoJSON(objet, {
        style: function (feature) {
            //Récupérer la valeur de la colonne ame_d
            const ame_d = feature.properties.ame_d;

            //Définir la couleur en fonction de la valeur de ame_d
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
            return {
                color: couleur, 
                weight: 2, 
                opacity: 1 
            };
        }
    });
}

//Créer couche plan vélo selon le statut des pistes
function creeCouchePlan(objet) {
    return L.geoJSON(objet, {
        style: function (feature) {
            //Récupérer la valeur de la colonne statut
            const statut = feature.properties.statut;
            let couleur = null;
            //Définir la légende associée
            if (statut === 'à réaliser') {
                couleur = 'orange';
            } else if (statut === 'existant') {
                couleur = 'blue';
            }
            else {
                couleur = 'gray'
            }
            return {
                color: couleur,
                weight: 2,
                opacity: 1
            };
        }
    });
}

// Définir la fonction zoomSur dans le contexte global
function zoomSur(latitude, longitude) {
    map.setView([latitude, longitude], 15); // Définir la vue de la carte sur les coordonnées spécifiées avec un zoom de 15
}

var clusterGroup;

// crée la couche contenant les accidents contenus dans "objet"
function creeCoucheAccidents(objet) {
    //Créer des clusters pour les accidents selon le niveau de zoom
    var clusterGroup = L.markerClusterGroup({
        maxClusterRadius: 50,
        disableClusteringAtZoom: 15
    });

    return L.geoJSON(objet, {
        pointToLayer: function (feature, latlng) {
            const properties = feature.properties;
            //Définir l'apparence d'un point seul (hors cluster) sans paramètres séléctionné
            if (type == null) {
                var mark = L.circleMarker(latlng, {
                    radius: 4.5,
                    fillColor: "red",
                    color: "#000",
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                });
            }
            //Si un des paramètres est sélectionné, création d'une icône personnalisée
            else {
                var customIcon = L.icon({
                    iconUrl: 'assets/images/icones/' + type + '/' + properties[type] + '.png', 
                    iconSize: [25, 25], 
                    iconAnchor: [16, 16], 
                    popupAnchor: [0, -16] 
                });
                // Créer le marqueur avec l'icône personnalisée
                var mark = L.marker(latlng, { icon: customIcon });
            }
            //const marker = mark;

            // Récupération des informations de l'accident correspondant
            const popupContenu = `
            <h4><b>${properties.date}</b></h4>
            <b>Type d'intersection :</b> ${properties.int}<br>
            <b>Vitesse max (km/h) :</b> ${properties.vma}<br>
            <b>Type de collision :</b> ${properties.col}<br>
            <b>Conditions atmosphériques :</b> ${properties.atm}<br>
            <b>Catégorie de la route :</b> ${properties.catr}<br>
            <b>Etat de la route :</b> ${properties.surf}<br>
            <b>Infrastructure de la route :</b> ${properties.infra}<br>
            <b>Catégorie du véhicule :</b> ${properties.catv}<br>
            <b>Circulation :</b> ${properties.circ}<br>
            <button type="button" class="btn btn-primary btn-sm" onclick="zoomSur(${latlng.lat}, ${latlng.lng})">Zoomer sur</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='map4?accidentId=${properties.num_acc}'">Voir en 3D</button>
            `;

            // Ajout d'une pop-up au marqueur
            mark.bindPopup(popupContenu);
            clusterGroup.addLayer(mark);
            return clusterGroup;
        }
    });
}

//Mettre à jour la légende selon la couche des pistes séléctionnée
function mettreAJourLegende(etatCouches) {
    const legendElement = document.getElementById('legend');
    //Couche du plan vélo
    if (etatCouches.planVisible) {
        legendElement.innerHTML = `
            <h3>Légende</h3>
            <div><span class="legend-color" style="background-color: blue;"></span> Existant </div>
            <div><span class="legend-color" style="background-color: orange;"></span> A réaliser </div>
        `;
    //Couche des pistes issues de géovélo
    } else {
        legendElement.innerHTML = `
            <h3>Légende</h3>
            <div><span class="legend-color" style="background-color: #1D3FD9;"></span> Piste cyclable</div>
                <div><span class="legend-color" style="background-color: #63DE6E;"></span> Voie verte / aménagement mixte</div>
                <div><span class="legend-color" style="background-color: #EC1DD0;"></span> Couloir bus + vélo</div>
                <div><span class="legend-color" style="background-color: #4DC0EF;"></span> Bande cyclable</div>
                <div><span class="legend-color" style="background-color: #C1A4BD ;"></span> Voie mixte</div>    
        `;
    }
}

// Affichage de la nouvelle légende des accidents
function afficheLegendeAccident(choix) {
    const legendElement = document.getElementById('legendAcci');
    if (choix == "catv") {
        legendElement.innerHTML = `
            <h4>Catégories de véhicule</h4>
            <div><img class="legend-img" src="assets/images/icones/catv/Bicyclette.png"> Bicyclette </div>
            <div><img class="legend-img" src="assets/images/icones/catv/Vélo à Assistance Electrique (VAE).png"> Vélo à Assistance Electrique (VAE) </div>
            <div><img class="legend-img" src="assets/images/icones/catv/Non renseigné.png"> Non renseigné </div>
        `;
    } else if (choix == "int") {
        legendElement.innerHTML = `
            <h4>Types d'intersection</h4>
            <div><img class="legend-img" src="assets/images/icones/int/Passage à niveau.png"> Passage à niveau </div>
            <div><img class="legend-img" src="assets/images/icones/int/Intersection à plus de 4 branches.png"> Intersection à plus de 4 branches </div>
            <div><img class="legend-img" src="assets/images/icones/int/Intersection en Y.png"> Intersection en Y </div>
            <div><img class="legend-img" src="assets/images/icones/int/Intersection en T.png"> Intersection en T </div>
            <div><img class="legend-img" src="assets/images/icones/int/Giratoire.png"> Giratoire </div>
            <div><img class="legend-img" src="assets/images/icones/int/Intersection en X.png"> Intersection en X </div>
            <div><img class="legend-img" src="assets/images/icones/int/Place.png"> Place </div>
            <div><img class="legend-img" src="assets/images/icones/int/Hors intersection.png"> Hors intersection </div>
            <div><img class="legend-img" src="assets/images/icones/int/Autre intersection.png"> Autre intersection </div>
        `;
    } else if (choix == "col") {
        legendElement.innerHTML = `
            <h4>Types de collision</h4>
            <div><img class="legend-img" src="assets/images/icones/col/Deux véhicules - par l'arrière.png"> Deux véhicules - par l'arrière </div>
            <div><img class="legend-img" src="assets/images/icones/col/Deux véhicules - par le côté.png"> Deux véhicules - par le côté </div>
            <div><img class="legend-img" src="assets/images/icones/col/Trois véhicules et plus - en chaîne.png"> Trois véhicules et plus - en chaîne </div>
            <div><img class="legend-img" src="assets/images/icones/col/Deux véhicules - frontale.png"> Deux véhicules - frontale </div>
            <div><img class="legend-img" src="assets/images/icones/col/Trois véhicules et plus - collisions multiples.png"> Trois véhicules et plus - collisions multiples </div>
            <div><img class="legend-img" src="assets/images/icones/col/Sans collision.png"> Sans collision </div>
            <div><img class="legend-img" src="assets/images/icones/col/Autre collision.png"> Autre collision </div>
            <div><img class="legend-img" src="assets/images/icones/col/Non renseigné.png"> Non renseigné </div>
        `;
    } else if (choix == "surf") {
        legendElement.innerHTML = `
            <h4>Etats de la surface du sol</h4>
            <div><img class="legend-img" src="assets/images/icones/surf/Normale.png"> Normale </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Enneigée.png"> Enneigée </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Flaques.png"> Flaques </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Mouillée.png"> Mouillée </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Inondée.png"> Inondée </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Corps gras - huile.png"> Corps gras - huile </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Verglacée.png"> Verglacée </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Autre.png"> Autre </div>
            <div><img class="legend-img" src="assets/images/icones/surf/Non renseigné.png"> Non renseigné </div>
        `;
    } else if (choix == "infra") {
        legendElement.innerHTML = `
            <h4>Types d'infrastructures présentes</h4>
            <div><img class="legend-img" src="assets/images/icones/infra/Aucun.png"> Aucun </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Bretelle d'échangeur ou de raccordement.png"> Bretelle d'échangeur ou de raccordement </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Carrefour aménagé.png"> Carrefour aménagé </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Chantier.png"> Chantier </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Pont - autopont.png"> Pont - autopont </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Souterrain - tunnel.png"> Souterrain - tunnel </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Voie ferrée.png"> Voie ferrée </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Zone piétonne.png"> Zone piétonne </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Autre.png"> Autre </div>
            <div><img class="legend-img" src="assets/images/icones/infra/Non renseigné.png"> Non renseigné </div>
        `;
    }
}

var accidents = null;
var pistes = null;
var acci_select = null;
var acci_anneeSelect = null;
var acci_paramSelect = null;
var type = null;

// PARAMETRES VARIANTS
var checkboxes = document.querySelectorAll('.droite input[type="checkbox"]');

// Ajouter un écouteur d'événements à chaque bouton radio
checkboxes.forEach(function (check) {
    check.addEventListener('change', function () {
        if (check.value == "lum" || check.value == "atm") {
            // si on coche la case
            if (this.checked) {
                document.querySelectorAll('.droite.all input[type="checkbox"]').forEach(function (all) { // on décoche toutes les visualisations
                    all.checked = false;
                })
                this.checked = true; // on recoche l'actuelle
                btnCluster.checked = false;  // on decoche la case du masquage des accidents
                // On desactive toutes les caractéristiques dans le menu correspondant
                caracteres.forEach(function (opt) {
                    opt.classList.remove('active');
                });
                type = check.value;  // nouvelle legende selectionnee
                map.removeLayer(acciLayer);
                acciLayer = creeCoucheAccidents(acci_select).addTo(map);  // on affiche la nouvelle légende
                document.getElementById('legendAcci').innerHTML = ``; // on supprime la legende
            }
            // si on la décoche
            else {
                type = null;  // nouvelle legende selectionnee (aucune)
                map.removeLayer(acciLayer);
                acciLayer = creeCoucheAccidents(acci_select).addTo(map);  // on affiche la nouvelle légende
                btnCluster.checked = false;  // on decoche la case du masquage des accidents
            }
        }
        else {
            let lumi_select = [];
            let meteo_select = [];
            // Parcourir toutes les cases cochées
            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    // icone de la coche
                    if (checkbox.parentNode.parentNode.classList.contains('lum')) {
                        // Si la case à cocher appartient à la classe "dropdown-item lumi"
                        lumi_select.push(checkbox.value);
                    } else if (checkbox.parentNode.parentNode.classList.contains('atm')) {
                        // Si la case à cocher appartient à la classe "dropdown-item meteo"
                        meteo_select.push(checkbox.value);
                    }
                }
            });
            // accidents selectionnes avec la luminosité
            acci_paramSelect = accidents.features.filter(feature => {
                // true ou false selon si l'accident appartient aux param sélectionnés
                return (lumi_select.includes(feature.properties.lum) && meteo_select.includes(feature.properties.atm));
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
            if (check.value == "lum" || check.value == "atm") {
                // si on coche la case
                if (this.checked) {
                    document.querySelectorAll('.droite.all input[type="checkbox"]').forEach(function (all) { // on décoche toutes les visualisations
                        all.checked = false;
                    })
                    this.checked = true; // on recoche l'actuelle
                    type = check.value;  // nouvelle legende selectionnee
                    map.removeLayer(acciLayer);
                    acciLayer = creeCoucheAccidents(acci_select).addTo(map);  // on affiche la nouvelle légende
                    document.getElementById('legendAcci').innerHTML = ``; // on supprime la legende
                }
                // si on la décoche
                else {
                    type = null;  // nouvelle legende selectionnee (aucune)
                    map.removeLayer(acciLayer);
                    acciLayer = creeCoucheAccidents(acci_select).addTo(map);  // on affiche la nouvelle légende
                }
            }
            else {
                let lumi_select = [];
                let meteo_select = [];
                // Parcourir toutes les cases cochées
                checkboxes.forEach(function (checkbox) {
                    if (checkbox.checked) {
                        // icone de la coche
                        if (checkbox.parentNode.parentNode.classList.contains('lum')) {
                            // Si la case à cocher appartient à la classe "dropdown-item lumi"
                            lumi_select.push(checkbox.value);
                        } else if (checkbox.parentNode.parentNode.classList.contains('atm')) {
                            // Si la case à cocher appartient à la classe "dropdown-item meteo"
                            meteo_select.push(checkbox.value);
                        }
                    }
                });
                // accidents selectionnes avec la luminosité
                acci_paramSelect = accidents.features.filter(feature => {
                    // true ou false selon si l'accident appartient aux param sélectionnés
                    return (lumi_select.includes(feature.properties.lum) && meteo_select.includes(feature.properties.atm));
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
            }
            map.removeLayer(acciLayer);
            acciLayer = creeCoucheAccidents(acci_select).addTo(map);
        }
    });
});

// déplacer le contenu de meteo vers le bas lorsque le menu est ouvert
let dropdownLumi = document.querySelector('.btn-group.lumi .dropdown-toggle');

dropdownLumi.addEventListener('click', function () {
    let meteoDecalable = document.querySelector('.btn-group.meteo');
    // décale l'element en dessous (meteo)
    if (!dropdownLumi.classList.contains('show')) {
        meteoDecalable.classList.remove('meteo-decale-vers-le-bas');
    }
    else {
        meteoDecalable.classList.add('meteo-decale-vers-le-bas');
    }

});

// déplacer le contenu de caracteristiques vers le bas lorsque le menu est ouvert
let dropdownMeteo = document.querySelector('.btn-group.meteo .dropdown-toggle');

dropdownMeteo.addEventListener('click', function () {
    let caracDecalable = document.querySelector('.btn-group.carac');
    // décale l'element en dessous (meteo)
    if (!dropdownMeteo.classList.contains('show')) {
        caracDecalable.classList.remove('carac-decale-vers-le-bas');
    }
    else {
        caracDecalable.classList.add('carac-decale-vers-le-bas');
    }
});

dropdownLumi.addEventListener('keyup', function (event) {  // pour fermer proprement le menu déroulant lumi quand on ferme avec escape
    if (event.key === 'Escape') {
        caracDecalable = document.querySelector('.btn-group.meteo');
        caracDecalable.classList.remove('meteo-decale-vers-le-bas');
    }
});

dropdownMeteo.addEventListener('keyup', function (event) {  // pour fermer proprement le menu déroulant meteo quand on ferme avec escape
    if (event.key === 'Escape') {
        caracDecalable = document.querySelector('.btn-group.carac');
        caracDecalable.classList.remove('carac-decale-vers-le-bas');
    }
});

// CARACTERISTIQUES
var caracteres = document.querySelectorAll('.caractere');

// ecouteur d'evenement
caracteres.forEach(function (carac) {
    carac.addEventListener('click', function () {
        // supprimer la classe 'active' de toutes les options
        caracteres.forEach(function (opt) {
            opt.classList.remove('active');
        });
        // Ajouter la classe 'active' à l'option cliquée
        this.classList.add('active');

        // on décoche toutes les visualisations
        document.querySelectorAll('.droite.all input[type="checkbox"]').forEach(function (all) {
            all.checked = false;
        })
        btnCluster.checked = false;  // on decoche la case du masquage des accidents

        // on décoche toutes les visualisations
        document.querySelectorAll('.droite.all input[type="checkbox"]').forEach(function (all) {
            all.checked = false;
        })

        // récupération de tous les types de la variable cochée
        type = carac.value;
        afficheLegendeAccident(type);
        map.removeLayer(acciLayer);
        acciLayer = creeCoucheAccidents(acci_select).addTo(map);
    });
});

// PLAN VELO
let plan = document.getElementById("plan");
var planVisible = false; // Indique si le plan vélo est visible ou non

// gestion couleur des pistes 

plan.addEventListener('click', function () {
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
var map = L.map('map', { zoomControl: false }).setView([48.8566, 2.3522], 12);
new L.Control.Zoom({ position: 'topright' }).addTo(map);

var defaultLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}').addTo(this.map);
var defaultLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}').addTo(this.map);

// Gestion petite et grande carte
const sidebar = document.getElementById('offcanvasScrolling');
const map2 = document.getElementById('map');

function toggleSidebar() {
    sidebar.style.left = '0';
    sidebar.style.width = '30%';
    map2.style.width = '70%';

    sidebar.classList.add('sidebar-transition');
    map2.classList.add('map-transition');
    sidebar.classList.add('showing');  //pour forcer la visualisation de la barre laterale
}

function closeSidebar() {
    sidebar.style.left = '-30%';
    map2.style.width = '100%';
    sidebar.classList.add('showing');
}

// La barre se ferme quand on appuie sur Echap
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeSidebar();
    }
});

// Écouteur d'événement pour le clic sur un bouton par exemple
document.getElementById('btn-lateral').addEventListener('click', toggleSidebar);
document.getElementById('btn-close').addEventListener('click', closeSidebar);



//Construction de la barre de recherche
const api = "AAPK07603a779b2f4f9dab2e28dc9fde0f05IJ2S4Lh8g5-lGWF4WEkWb1aRCDmpSK4NEfHQdWICq1wU-r9GM1MLdWTUL_qxj0xt";
const geocoder = L.esri.Geocoding.geocodeService({
    apikey: api
});
const searchInput = document.getElementById('research_input');
const suggestions = document.getElementById('suggestions');


searchInput.addEventListener('input', function () {
    const query = this.value;

    geocoder.suggest().text(query).run((error, results, response) => {
        if (error) {
            console.error('Error fetching address suggestions:', error);
            return;
        }
        suggestions.innerHTML = '';
        //Construire un menu déroulant affichant les potentielles adresses recherchées
        results.suggestions.forEach(suggestion => {
            const address = suggestion.text;
            const location = suggestion.location;{
                const a = document.createElement('a');
                a.classList.add('dropdown-item');
                a.textContent = address;
                a.addEventListener('click', function () {
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

//Zoomer sur l'endroit recherché 
suggestions.addEventListener('click', function (event) {
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
document.addEventListener('click', function (event) {
    if (!event.target.closest('.input-group')) {
        suggestions.style.display = 'none';
    }
});

// Récupération de tous les accidents
fetch('recupere_acci')
    .then(result => result.json())
    .then(result => {
        accidents = result;
        acci_select = accidents;
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

// Affichage de l'overlay d'images de statistiques
var imageOverlay;

function showImageOverlay(imageUrl) {
    if (!imageOverlay) {
        imageOverlay = L.imageOverlay(imageUrl, [[51.49, -0.08], [51.51, -0.06]]).addTo(map);
        document.getElementById('overlayImage').src = imageUrl;
        document.getElementById('image-overlay').style.display = 'block';
    } else {
        map.removeLayer(imageOverlay);
        imageOverlay = L.imageOverlay(imageUrl, [[51.49, -0.08], [51.51, -0.06]]).addTo(map);
        document.getElementById('overlayImage').src = imageUrl;
    }
}

// Fonction pour fermer l'overlay d'image de stat qui est sur la carte
function closeImageOverlay() {
    if (imageOverlay) {
        map.removeLayer(imageOverlay);
        document.getElementById('image-overlay').style.display = 'none';
        imageOverlay = null;
    }
}

// Masquer l'overlay d'image au chargement de la page
document.getElementById('image-overlay').style.display = 'none';

// Ajouter d'autres couches de tuiles pour différentes vues
var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');
var topographicLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}');
var openStreetMapLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png');

document.addEventListener('DOMContentLoaded', function () {
    // Associer des boutons à des actions pour changer de fond de plan
    document.getElementById('btnSatellite').onclick = function () {
        map.removeLayer(defaultLayer);
        map.addLayer(satelliteLayer);
        map.removeLayer(openStreetMapLayer);
        map.removeLayer(topographicLayer);
    };

    document.getElementById('btnTopographic').onclick = function () {
        map.removeLayer(defaultLayer);
        map.removeLayer(satelliteLayer);
        map.removeLayer(openStreetMapLayer);
        map.addLayer(topographicLayer);
    };

    // Définir un bouton pour revenir au fond de plan par défaut
    document.getElementById('btnOpenStreetMap').onclick = function () {
        map.removeLayer(satelliteLayer);
        map.removeLayer(topographicLayer);
        map.removeLayer(defaultLayer);
        map.addLayer(openStreetMapLayer)
    };

    document.getElementById('btnDefault').onclick = function () {
        map.removeLayer(satelliteLayer);
        map.removeLayer(topographicLayer);
        map.removeLayer(openStreetMapLayer);
        map.addLayer(defaultLayer)
    };
});

// Gestion l'appration/disparition des accidents
var btnCluster = document.getElementById('accidentsCheckbox');
btnCluster.addEventListener('change', function () {
    if (this.checked) {
        map.removeLayer(acciLayer);
    }
    else {
        acciLayer.addTo(map);
    }
})