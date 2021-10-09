import { Component, OnInit } from '@angular/core';
import { icon, latLng, Map, marker, point, polyline, tileLayer } from 'leaflet';

@Component({
  selector: 'app-maps',
  templateUrl: './maps.component.html',
  styleUrls: ['./maps.component.scss']
})
export class MapsComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }
  onMapReady(map: Map) {
    map.fitBounds(this.route.getBounds(), {
      padding: point(24, 24),
      maxZoom: 12,
      animate: true
    });
  }

  // options = {
  //   layers: [
  //     tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  //       attribution: '&copy; OpenStreetMap contributors'
  //     })
  //   ],
  //   zoom: 7,
  //   center: latLng([ 12.31920721126387, -1.543856359590115 ])
  // };
  streetMaps = tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    detectRetina: true,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  });
  wMaps = tileLayer('http://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
    detectRetina: true,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  });

  // Marker for the top of Mt. Ranier
  summit = marker([-10.969860623253618, 26.723480899302437 ], {
    icon: icon({
      iconSize: [ 25, 41 ],
      iconAnchor: [ 13, 41 ],
      iconUrl: 'leaflet/marker-icon.png',
      shadowUrl: 'leaflet/marker-shadow.png'
    })
  });

  // Marker for the parking lot at the base of Mt. Ranier trails
  paradise = marker([ -11.0133313379539, 26.736522196184197 ], {
    icon: icon({
      iconSize: [ 25, 41 ],
      iconAnchor: [ 13, 41 ],
      iconUrl: 'leaflet/marker-icon.png',
      iconRetinaUrl: 'leaflet/marker-icon-2x.png',
      shadowUrl: 'leaflet/marker-shadow.png'
    })
  });

  // Path from paradise to summit - most points omitted from this example for brevity
  route1 = polyline([[ -10.989556886340226, 26.73222286754186 ],
    [ -10.972977611361724, 26.725513402083916 ],
    [ 46.815471360459924, -121.72521826811135 ],
    [ 46.8360239546746, -121.7323131300509 ],
    [ 46.844306448474526, -121.73327445052564 ],
    [ 46.84979408048093, -121.74325201660395 ],
    [ 46.853193528950214, -121.74823296256363 ],
    [ 46.85322881676257, -121.74843915738165 ],
    [ 46.85119913890958, -121.7519719619304 ],
    [ 46.85103829018772, -121.7542376741767 ],
    [ 46.85101557523012, -121.75431755371392 ],
    [ 46.85140013694763, -121.75727385096252 ],
    [ 46.8525277543813, -121.75995212048292 ],
    [ 46.85290292836726, -121.76049157977104 ],
    [ 46.8528160918504, -121.76042997278273 ]]);

  // Layers control object with our two base layers and the three overlay layers
  layersControl = {
    baseLayers: {
      'Street Maps': this.streetMaps,
      'Wikimedia Maps': this.wMaps
    },
    overlays: {
      'Mt. Rainier Summit': this.summit,
      'Mt. Rainier Paradise Start': this.paradise,
      // 'Mt. Rainier Climb Route': this.route
    }
  };
  route = polyline([])


  // Set the initial set of displayed layers (we could also use the leafletLayers input binding for this)
  options = {
    layers: [ this.streetMaps, this.route, this.summit, this.paradise ],
    zoom: 10,
    center: latLng([ -10.987728002819882, 26.736378885407856 ])
  };

}