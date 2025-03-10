// Example fetch.js to load data from the API
function loadRoutes() {
    fetch('http://3m.alysia.fr/api/?table=route')
        .then(response => response.json())
        .then(data => {
            const routesList = document.getElementById('routes-list');
            data.forEach(route => {
                const routeCard = `
                <div class="col-md-4">
                    <div class="card">
                        <h5 class="card-title">${route.name}</h5>
                        <p class="card-text">Difficulty: ${route.difficulty}</p>
                    </div>
                </div>`;
                routesList.innerHTML += routeCard;
            });
        })
        .catch(error => console.error('Error fetching routes:', error));
}
