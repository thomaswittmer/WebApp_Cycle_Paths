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

// Récupérer tous les boutons radio
var radioButtons = document.querySelectorAll('input[type="radio"]');
let lumi_select = null;
// Ajouter un écouteur d'événements à chaque bouton radio
radioButtons.forEach(function(radioButton) {
    radioButton.addEventListener('click', function() {
        // envoi du formulaire avec la case cochee
        let donnees = new FormData();
        if (lumi_select != radioButton.value){
            lumi_select = radioButton.value;
            donnees.append('lumi', radioButton.value);
            fetch('/lumino', {
            method: 'post',
            body: donnees
            })
            .then(r => r.json())
            .then(r => {
            console.log(r)
            })
        }
    });
});