<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compras Porto — Carrito</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav class="site-nav">
  <a class="brand" href="index.php">Mercado Negro </a>
  <div class="nav-links">
    <a href="index.php">Productos</a>
    <a href="agrega.php">Agregar </a>
    <a href="modifica.php">Editar Producto</a>
  </div>
  <a class="cart-btn active" href="carrito.php">
     Carrito <span class="badge" id="cart-badge">0</span>
  </a>
</nav>

<main>
  <h1 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:1.5rem;">
    Tus productos:
  </h1>

  <div class="carrito-layout">
    <!-- Tabla -->
    <div class="carrito-table" id="carrito-wrap">
      <div class="empty-cart" id="empty-cart">
        <div class="icon">...</div>
        <p>Vacío</p>
        <a class="btn" href="index.php" style="margin-top:1rem">Ver productos</a>
      </div>
      <table id="carrito-table" style="display:none">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio unit.</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="carrito-body"></tbody>
      </table>
    </div>

    <!-- Resumen -->
    <div class="resumen-card">
      <h3>Resumen</h3>
      <div class="resumen-row"><span>Subtotal</span><span id="res-sub">$0.00</span></div>
      <div class="resumen-row"><span>Envío</span><span>$99.00</span></div>
      <div class="resumen-row total"><span>Total</span><span id="res-total">$99.00</span></div>
      <button class="checkout-btn" onclick="checkout()">Finalizar compra</button>
    </div>
  </div>
</main>

<div class="toast" id="toast"></div>
<footer class="site-footer">Compras Sencillas &copy; Practica aplicaciones web orientadas a servicios</footer>

<script src="js/util.js"></script>
<script src="js/api.js"></script>
<script src="js/carrito.js"></script>
<script>
  function render() {
    const items = Carrito.obtener();
    const table = document.getElementById('carrito-table');
    const empty = document.getElementById('empty-cart');

    if (!items.length) {
      table.style.display = 'none';
      empty.style.display = 'block';
      document.getElementById('res-sub').textContent   = '$0.00';
      document.getElementById('res-total').textContent = fmt(99);
      return;
    }

    empty.style.display  = 'none';
    table.style.display  = 'table';

    document.getElementById('carrito-body').innerHTML = items.map(item => `
      <tr>
        <td style="display:flex;align-items:center;gap:0.6rem;">
          ${productThumb({ imagen: item.imagen, nombre: item.nombre }, 'sm')}
          ${item.nombre}
        </td>
        <td>${fmt(item.precio)}</td>
        <td>
          <div class="qty-ctrl">
            <button class="qty-btn" onclick="cambiar(${item.id}, -1)">−</button>
            <span class="qty-val">${item.qty}</span>
            <button class="qty-btn" onclick="cambiar(${item.id}, 1)">+</button>
          </div>
        </td>
        <td>${fmt(item.precio * item.qty)}</td>
        <td><button class="del-btn" onclick="quitar(${item.id})">Quitar</button></td>
      </tr>
    `).join('');

    const sub = Carrito.total();
    document.getElementById('res-sub').textContent   = fmt(sub);
    document.getElementById('res-total').textContent = fmt(sub + 99);
  }

  function cambiar(id, delta) {
    Carrito.cambiarCantidad(id, delta);
    render();
  }

  function quitar(id) {
    Carrito.quitar(id);
    render();
    showToast('Producto eliminado');
  }

  async function checkout() {
    const items = Carrito.obtener();
    if (!items.length) { showToast('El carrito está vacío', 'error'); return; }
    const total = Carrito.total() + 99;
    try {
      const res = await API.guardarPedido(items, total);
      if (!res.ok) throw new Error(res.error);
      Carrito.vaciar();
      render();
      showToast(`✓ Pedido #${res.pedido_id} realizado con éxito`);
    } catch (e) {
      showToast('Error: ' + e.message, 'error');
    }
  }

  render();
</script>
</body>
</html>
