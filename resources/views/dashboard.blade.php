<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
</head>
<body>
  <h2>CryptoInvestment Dashboard</h2>
  <form id="addCoinForm"><input id="symbol" placeholder="BTC"><button>Agregar</button></form>
  <div id="coins"></div>
  <canvas id="priceChart"></canvas>

  <script>
    const apiBase = '/api';
    let chart;

    async function loadCoins() {
      const {data: coins} = await axios.get(`${apiBase}/coins`);
      const container = document.getElementById('coins'); container.innerHTML='';
      coins.forEach(c => {
        const btn = document.createElement('button');
        btn.textContent = `${c.symbol} ${c.name ?? ''}`;
        btn.onclick = ()=> loadChart(c.id);
        container.appendChild(btn);
      });
    }

    async function loadChart(coinId){
      const to = new Date().toISOString();
      const from = new Date(Date.now() - 7*24*3600*1000).toISOString();
      const {data} = await axios.get(`${apiBase}/coins/${coinId}/prices?from=${from}&to=${to}`);
      const labels = data.map(d => new Date(d.timestamp_utc).toLocaleString());
      const prices = data.map(d => parseFloat(d.price_usd));
      const ctx = document.getElementById('priceChart').getContext('2d');
      if(chart) chart.destroy();
      chart = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: [{ label:'Precio USD', data: prices, fill:false }]},
        options: { responsive: true }
      });
    }

    document.getElementById('addCoinForm').addEventListener('submit', async e=>{
      e.preventDefault();
      const sym = document.getElementById('symbol').value;
      if(!sym) return;
      await axios.post(`${apiBase}/coins`, {symbol: sym});
      document.getElementById('symbol').value='';
      loadCoins();
    });

    // Polling simple para actualizar la UI cada 30s (puedes mejorarlo)
    setInterval(() => {
      loadCoins();
    }, 30000);

    loadCoins();
  </script>
</body>
</html>
