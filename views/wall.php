<!DOCTYPE html>
<html lang="en">


<head>
    <?php

    include $path . '/views/components/autoload.php';

    #region session and header
    if (isset($_SESSION['user'])) {
        include $path . '/views/components/header-online.php';
    } else {
        include $path . '/views/components/header-offline.php';
    }
    #endregion
    
    #region fetch walls data(sectors and their routes)
    $sectors = getData('sectors');
    $routes = getData('routes');
    #endregion
    

    #region filter sectors and routes by wall_id
    $wall_id = $_GET['id'];
    $listOfSectors = array();

    foreach ($sectors as $sector) {
        if ($sector['wall_id'] == $wall_id) {

            $listOfRoutes = array();

            foreach ($routes as $route) {
                if ($route['sector_id'] == $sector['id'] && $route['status'] == 'active') {
                    $listOfRoutes[] = $route;
                }
            }

            $listOfSectors[] = array(
                'sector' => $sector,
                'routes' => $listOfRoutes
            );
        }
    }
    #endregion
    

    ?>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css">
    <style>
        #map {
            width: 100%;
            height: 400px;
            background-color: #f5f5f5;
            margin-bottom: 20px;
        }

        .route-card {
            border: 2px solid #333;
        }

        .route-badge {
            font-size: 1rem;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        /* Styles for sector name and minimap */
        .sector-name {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 5px;
            font-size: 1.2rem;
        }
    </style>

</head>

<body>

    <h2 class="text-center">Wall Sectors</h2>
    <p class="text-center">Please select a sector to view its routes.</p>

    <?php if (!empty($listOfSectors)): ?>
        <div id="route-list" class="container mt-4">
            <div id="map" style="width: 100%; height: 450px"></div>
            <div id="route-list-message" class="text-center">Please select a sector to view its routes.</div>
            <div class="row" id="route-list-cards"></div>
        </div>
    <?php else: ?>
        <p>No sectors available for this wall.</p>
    <?php endif; ?>





    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <script>
        const styles = [
            new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'blue',
                    width: 2
                }),
                fill: new ol.style.Fill({
                    color: 'rgba(0, 0, 255, 0.3)'
                })
            }),
            new ol.style.Style({

                geometry: function (feature) {
                    const coordinates = feature.getGeometry().getCoordinates()[0];
                    return new ol.geom.MultiPoint(coordinates);
                }
            })
        ];

        const geojsonObject = {
            'type': 'FeatureCollection',
            'features': [

                <?php
                foreach ($listOfSectors as $sector) {
                    if ($sector['sector']['coordinates'] != null && $sector['sector']['coordinates'] != "") {
                        echo "
                    {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Polygon',
                        'coordinates': [" . $sector['sector']['coordinates'] . "]
                        },
                        'properties': {
                            'name': '" . $sector['sector']['name'] . "',
                            'sector_id': '" . $sector['sector']['id'] . "'
                        }
                    },";
                    }
                }
                ?>
            ]
        };

        const vectorSource = new ol.source.Vector({
            features: new ol.format.GeoJSON().readFeatures(geojsonObject, { featureProjection: 'EPSG:4326' })
        });

        const vectorLayer = new ol.layer.Vector({
            source: vectorSource,
            style: styles
        });

        const extent = [-25, 0, 125, 60];  // minX, minY, maxX, maxY

        const map = new ol.Map({
            target: 'map',
            layers: [vectorLayer],
            view: new ol.View({
                center: [50, 50],
                zoom: 2,
                minZoom: 1,
                maxZoom: 5,

                extent: extent,
                projection: 'EPSG:4326'
            })
        });

        map.on('click', function (event) {
            map.forEachFeatureAtPixel(event.pixel, function (feature) {
                const sectorId = feature.get('sector_id');
                const sectorName = feature.get('name');

                document.getElementById('route-list-message').style.display = 'none';

                const routeListDiv = document.getElementById('route-list-cards');
                routeListDiv.innerHTML = '';

                const sectorData = <?php echo json_encode($listOfSectors); ?>;
                const sector = sectorData.find(s => s.sector.id == sectorId);

                if (sector && sector.routes.length > 0) {
                    sector.routes.forEach(function (route) {
                        const routeCard = `
                        <div class="col-md-4">
                            <a href="/?page=route&id=${route.id}" style="text-decoration: none; color: inherit;">
                                <div class="card mb-4 route-card" style="border: solid 5px ${route.color};">
                                    
                                    <div class="card-body">
                                        <h5 class="card-title">
                                        <span class="badge ${getBadgeColor(route.difficulty)} badge-custom">
                                            ${route.difficulty}
                                        </span>

                                        ${route.name}
                                        
                                        </h5>
                                        
                                    </div>
                                    <img src="${route.image_url}" loading="lazy" class="card-img-top" alt="${route.name}">
                                </div>
                            </a>
                        </div>`;
                        routeListDiv.innerHTML += routeCard;
                    });
                } else {
                    routeListDiv.innerHTML = '<p>No routes available for this sector.</p>';
                }
            });
        });


        function getBadgeColor(difficulty) {
            if (/4[abc][+]*/.test(difficulty)) {
                return 'bg-success';  // Green for 4a to 4c+
            } else if (/5[abc][+]*/.test(difficulty)) {
                return 'bg-primary';  // Blue for 5a to 5c+
            } else if (/6[abc][+]*/.test(difficulty)) {
                return 'bg-danger';   // Red for 6a to 6c+
            } else if (/7[abc][+]*/.test(difficulty)) {
                return 'bg-dark';     // Black for 7a to 7c+
            } else if (/8[abc][+]*/.test(difficulty) || parseInt(difficulty) >= 8) {
                return 'bg-purple';   // Purple for 8a and above
            } else {
                return 'bg-secondary';  // Default badge color
            }
        }

    </script>


    <?php include $path . '/views/components/footer.php'; ?>
</body>

</html>