function locate(map) {
  map.locate({ setView: true, enableHighAccuracy: true, maxZoom: 16 })
}

const initialPosition = { lat: -34.64681920441931, lng: -54.16993618011475 }

const geoInput = document.getElementById('geo')

const map = L.map('mapid').setView(initialPosition, 15)

let attribution = '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a>'
attribution = ' © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
attribution = ' <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>'

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
{
  accessToken: 'pk.eyJ1IjoiZmVyYXJlcyIsImEiOiJja3Vla244Nm4xbDh6MnZuejRyc2gzMXc4In0.aAGELNPOnaZihF5IpyXNZA',
  attribution: attribution,
  id: 'mapbox/streets-v11',
  maxZoom: 21,
  tileSize: 512,
  zoomOffset: -1,
},
).addTo(map)

const marker = L.marker(initialPosition).addTo(map)

map.on('click', (event) => map.panTo(event.latlng))
map.on('locationfound', (latlng) =>   map.panTo(latlng))
map.on('move', () => {
  const latlng = map.getCenter()
  marker.setLatLng(latlng)
  geoInput.value = `${latlng.lat},${latlng.lng}`
})

locate(map)

document.querySelector('[js-locate]').addEventListener('click', () => locate(map))
