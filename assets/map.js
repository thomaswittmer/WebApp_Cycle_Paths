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
            let send = new FormData();
            send.append('annee', this.selectedYear);
            fetch('/recup_annee', {
            method: 'post',
            body: send
            })
            .then(r => r.json())
            .then(r => {
            console.log(r)
            })
        }
    }

}).mount('#app');

// LUMINOSITE
var checkboxes = document.querySelectorAll('input[type="checkbox"]');

// Ajouter un écouteur d'événements à chaque bouton radio
checkboxes.forEach(function(check) {
    check.addEventListener('change', function() {
        let lumi_select = [];
        // Parcourir toutes les cases cochées et les ajouter à FormData
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                lumi_select.push(checkbox.value);
            }
        });

        // Créer un objet FormData et ajouter les valeurs des cases cochées comme un tableau
        let donnees = new FormData();
        donnees.append('lumi', lumi_select); // Utilisation de 'lumi[]' pour créer un tableau de valeurs

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


// METEO
var dropdownCheckboxes = document.querySelectorAll('.dropdown-item input[type="checkbox"]');

// Ajouter un écouteur d'événements à chaque case à cocher dans le menu déroulant
dropdownCheckboxes.forEach(function(check) {
    check.addEventListener('change', function() {
        let lumi_select = [];
        // Parcourir toutes les cases cochées dans le menu déroulant et les ajouter à l'array lumi_select
        dropdownCheckboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                lumi_select.push(checkbox.value);
            }
        });

        // Créer un objet FormData et ajouter les valeurs des cases cochées comme un tableau
        let donnees = new FormData();
        donnees.append('meteo', lumi_select); // Utilisation de 'lumi[]' pour créer un tableau de valeurs

        console.log(donnees);
        fetch('/lumino', {
            method: 'post',
            body: donnees
        })
        .then(r => r.json())
        .then(r => {
            console.log(r)
        });
    });
});
