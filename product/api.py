from flask import Flask, jsonify, request
import requests

app = Flask(__name__)

@app.route('/search_places', methods=['GET'])
def search_places():
    place_type = request.args.get('place_type', 'lugares turísticos').lower()
    latitude = request.args.get('latitude', 7.900412097203202)
    longitude = request.args.get('longitude', -72.50295964581814)

    if place_type == 'lugares turísticos':
        query_tag = 'tourism="attraction"'
    elif place_type == 'hoteles':
        query_tag = 'tourism="hotel"'
    elif place_type == 'restaurantes':
        query_tag = 'amenity="restaurant"'
    else:
        query_tag = 'tourism="attraction"'

    query = f"""
    [out:json];
    node
    [{query_tag}]
    (around:1000,{latitude},{longitude});
    out body;
    """
    url = "http://overpass-api.de/api/interpreter"

    try:
        response = requests.get(url, params={'data': query})
        response.raise_for_status()
        data = response.json()

        places = []
        for element in data.get('elements', []):
            place = {
                'name': element.get('tags', {}).get('name', 'Sin nombre'),
                'lat': element['lat'],
                'lon': element['lon']
            }
            places.append(place)

        return jsonify({'status': 'success', 'places': places})

    except requests.RequestException as e:
        return jsonify({'status': 'error', 'message': str(e)})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
