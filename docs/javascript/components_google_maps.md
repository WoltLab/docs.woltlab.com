# Google Maps - JavaScript API

The Google Maps component is used to show a map using the Google Maps API.

## Example

The component can be included directly as follows:

```html
<woltlab-core-google-maps
    id="id"
    class="googleMap"
    api-key="your_api_key"
></woltlab-core-google-maps>
```

Alternatively, the component can be included via a template that uses the API key from the configuration and also handles the user content:

```smarty
{include file='googleMapsElement' googleMapsElementID="id"}
```

## Parameters

### `id`

ID of the map instance.

### `api-key`

Google Maps API key.

### `zoom`

Defaults to `13`.

Default zoom factor of the map.

### `lat`

Defaults to `0`.

Latitude of the default map position.

### `lng`

Defaults to `0`.

Longitude of the default map position.

### `access-user-location`

If set, the map will try to center based on the user's current position.

## Map-related Functions

### `addMarker`

Adds a marker to the map.

#### Example

```html
<script data-relocate="true">
	require(['WoltLabSuite/Core/Component/GoogleMaps/Marker'], ({ addMarker }) => {
		void addMarker(document.getElementById('map_id'), 52.4505, 13.7546, 'Title', true);
	});
</script>
```

#### Parameters

##### `element`

`<woltlab-core-google-maps>` element.

##### `latitude`

Marker position (latitude)

##### `longitude`

Marker position (longitude)

##### `title`

Title of the marker.

##### `focus`

Defaults to `false`.

True, to focus the map on the position of the marker.

### `addDraggableMarker`

Adds a draggable marker to the map.

#### Example

```html
<script data-relocate="true">
	require(['WoltLabSuite/Core/Component/GoogleMaps/Marker'], ({ addDraggableMarker }) => {
		void addDraggableMarker(document.getElementById('map_id'), 52.4505, 13.7546);
	});
</script>
```

#### Parameters

##### `element`

`<woltlab-core-google-maps>` element.

##### `latitude`

Marker position (latitude)

##### `longitude`

Marker position (longitude)

### `Geocoding`

Enables the geocoding feature for a map.

#### Example

```html
<input
	type="text"
	data-google-maps-geocoding="map_id"
	data-google-maps-marker
>
```

#### Parameters

##### `data-google-maps-geocoding`

ID of the `<woltlab-core-google-maps>` element.

##### `data-google-maps-marker`

If set, a movable marker is created that is coupled with the input field.

### `MarkerLoader`

Handles a large map with many markers where markers are loaded via AJAX.

#### Example

```html
<script data-relocate="true">
	require(['WoltLabSuite/Core/Component/GoogleMaps/MarkerLoader'], ({ setup }) => {
		setup(document.getElementById('map_id'), 'action_classname', {});
	});
</script>
```

#### Parameters

##### `element`

`<woltlab-core-google-maps>` element.

##### `actionClassName`

Name of the PHP class that is called to retrieve the markers via AJAX.

##### `additionalParameters`

Additional parameters that are transmitted when querying the markers via AJAX.
