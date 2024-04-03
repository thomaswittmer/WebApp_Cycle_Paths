const myMap = L.map('map').setView([49.007643, 2.549616], 12);
const mapp = new Vue({
    data() {
        return {
            featureG: L.featureGroup(),
            map: myMap,
            tileLayer1: null,
            tileLayer2: null,
        };
    },
    mounted() {
        this.map = L.map('map').setView([48.866667, 2.333333], 12);

        this.tileLayer1 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        }).addTo(this.map);
        
        this.tileLayer2 = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 28,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        });
        
        this.map.whenReady(() => {
            this.map.on('zoomend', () => {
                if (this.map.getZoom() >= 18) {
                    this.map.removeLayer(this.tileLayer1);
                    this.map.addLayer(this.tileLayer2);
                } else {
                    this.map.removeLayer(this.tileLayer2);
                    this.map.addLayer(this.tileLayer1);
                }
            });
        });


        this.initializeMap();
    },

    methods: {
        initializeMap() {
            
            try {
              this.map.whenReady(() => {
                console.log('Map is ready!');
                
                
                if (this.map) {
                  console.log('Map is ope!');
                  this.map.on('zoomend', async () => {
                    console.log('Zoom level:', this.map.getZoom());
                    const currentZoom = this.map.getZoom();
                    this.featureG.addTo(this.map);
                    this.featureG.clearLayers();  
                  });
                }
              });
            } catch (error) {
              console.error('Error initializing map:', error);
            }

        }        
          
    }

});

mapp.$mount('#map');
window.app = mapp;


