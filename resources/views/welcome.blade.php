<!DOCTYPE html>
<html>
<head>
  <title>CryptoInvestment Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
  <h1>Seguimiento de Criptomonedas</h1>
  <table border="1" id="cryptoTable">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Símbolo</th>
        <th>Precio USD</th>
        <th>Cambio 24h</th>
        <th>Volumen</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <canvas id="cryptoChart" width="600" height="300"></canvas>

  <script>
    async function loadCryptos() {
      const res = await axios.get('/api/cryptos');
      const data = res.data;
      const tbody = document.querySelector('#cryptoTable tbody');
      tbody.innerHTML = '';

      const labels = [];
      const prices = [];

      data.forEach(c => {
        tbody.innerHTML += `
          <tr>
            <td>${c.name}</td>
            <td>${c.symbol}</td>
            <td>${c.price.toFixed(2)}</td>
            <td>${c.percent_change_24h.toFixed(2)}%</td>
            <td>${c.volume_24h.toFixed(2)}</td>
          </tr>
        `;
        labels.push(c.symbol);
        prices.push(c.price);
      });

      new Chart(document.getElementById('cryptoChart'), {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Precio USD',
            data: prices,
            borderColor: 'blue',
            fill: false
          }]
        }
      });
    }

    async function refreshData() {
      await axios.get('/api/cryptos/update');
      await loadCryptos();
    }

    setInterval(refreshData, 60000); // cada 1 minuto
    loadCryptos();
  </script>
</body>
</html>
