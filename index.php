<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compras Porto</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav class="site-nav">
  <a class="brand" href="index.php">Mercado Negro</a>
  <div class="nav-links">
    <a href="index.php" class="active">Productos</a>
    <a href="agrega.php">Agregar</a>
    <a href="modifica.php">Editar Producto</a>
  </div>
  <a class="cart-btn" href="carrito.php">
     Carrito <span class="badge" id="cart-badge">0</span>
  </a>
</nav>

<main>
  <div class="catalog-header">
    <h1>Compra Ahora!<br>
      <span style="font-size:1rem;font-weight:300;color:var(--muted);font-family:'DM Sans',sans-serif">
        Selecciona lo que deseas comprar
      </span>
    </h1>
    <div class="search-bar">
      <input type="text" id="search-input" placeholder="Buscar producto…" oninput="filtrar()">
    </div>
  </div>

  <div id="loading" class="loading">Cargando productos…</div>
  <div class="products-grid" id="products-grid"></div>
</main>

<div class="toast" id="toast"></div>
<footer class="site-footer">Compras Sencillas &copy; practica / aplicaciones web orientadas a servicios</footer>

<script src="js/util.js"></script>
<script src="js/api.js"></script>
<script src="js/carrito.js"></script>
<script>
  let productos = [];

  async function cargar() {
    try {
      const res = await API.listarProductos();
      if (!res.ok) throw new Error(res.error);
      productos = res.datos;
      renderizar();
    } catch (e) {
      document.getElementById('loading').textContent = 'Error al cargar: ' + e.message;
    }
  }

  function filtrar() {
    const q = document.getElementById('search-input').value.toLowerCase();
    renderizar(q);
  }

  function renderizar(q = '') {
    document.getElementById('loading').style.display = 'none';
    const grid = document.getElementById('products-grid');
    const lista = productos.filter(p => p.nombre.toLowerCase().includes(q));
    grid.innerHTML = lista.map(p => `
      <div class="product-card">
        <div class="product-img">${productThumb(p)}</div>
        <div class="product-info">
          <div class="product-name">${p.nombre}</div>
          <div class="product-desc">${p.descripcion || ''}</div>
          <div class="product-footer">
            <span class="product-price">${fmt(p.precio)}</span>
            ${p.stock > 0
              ? `<button class="add-btn" onclick="agregar(${p.id})">+ Agregar</button>`
              : `<span class="sin-stock">Sin stock</span>`}
          </div>
        </div>
      </div>
    `).join('') || '<p style="color:var(--muted)">Sin resultados.</p>';
  }

  function agregar(id) {
    const p = productos.find(x => x.id == id);
    if (!p) return;
    Carrito.agregar({ id: p.id, nombre: p.nombre, precio: parseFloat(p.precio), imagen: p.imagen });
    showToast(`✓ ${p.nombre} agregado`);
  }

  cargar();
</script>
</body>
</html>
