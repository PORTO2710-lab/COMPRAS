<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compras Porto — Modificar producto</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav class="site-nav">
  <a class="brand" href="index.php">Mercado Negro</a>
  <div class="nav-links">
    <a href="index.php">Productos</a>
    <a href="agrega.php">Agregar </a>
    <a href="modifica.php" class="active">Editar Producto</a>
  </div>
  <a class="cart-btn" href="carrito.php">
     Carrito <span class="badge" id="cart-badge">0</span>
  </a>
</nav>

<main>
  <h1 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:1.5rem;">
    Modificar un producto
  </h1>

  <div class="modifica-grid">
    <!-- Lista de productos -->
    <div class="product-select-list">
      <h3>Selecciona un producto</h3>
      <div id="modifica-list">
        <div class="loading">Cargando…</div>
      </div>
    </div>

    <!-- Formulario de edición -->
    <div id="mod-placeholder" class="placeholder-form">
      ← Selecciona un producto para editar
    </div>

    <div class="form-card" id="mod-form" style="display:none">
      <h2>Editar</h2>

      <div class="field">
        <label>Nombre *</label>
        <input type="text" id="mod-nombre">
      </div>
      <div class="field">
        <label>Descripción</label>
        <textarea id="mod-descripcion"></textarea>
      </div>
      <div class="field">
        <label>Precio (MXN)</label>
        <input type="number" id="mod-precio" min="0" step="0.01">
      </div>
      <div class="field">
        <label>Stock</label>
        <input type="number" id="mod-stock" min="0">
      </div>

      <div class="field">
        <label>Imagen del producto</label>
        <div class="img-upload-area" id="mod-upload-area"
             onclick="document.getElementById('mod-img-input').click()">
          <div class="img-upload-placeholder" id="mod-img-placeholder">
            <span class="upload-icon">📂</span>
            <span>Haz clic para cambiar imagen</span>
            <span class="upload-hint">JPG, PNG, WEBP — máx. 5 MB</span>
          </div>
          <img id="mod-img-preview" class="img-preview" style="display:none" alt="Vista previa">
        </div>
        <input type="file" id="mod-img-input" accept="image/*" style="display:none">
        <button type="button" class="clear-img-btn" id="mod-clear-btn" style="display:none">
          ✕ Quitar imagen
        </button>
      </div>

      <div class="form-actions">
        <button class="btn btn-accent" onclick="guardarCambios()">Guardar cambios</button>
        <button class="btn btn-danger" onclick="eliminar()">Eliminar</button>
      </div>
    </div>
  </div>
</main>

<div class="toast" id="toast"></div>
<footer class="site-footer">Compras Sencillas &copy; 2025</footer>

<script src="js/util.js"></script>
<script src="js/api.js"></script>
<script src="js/carrito.js"></script>
<script>
  let productos = [];
  let selId = null;

  configurarUploadImagen('mod');

  async function cargar() {
    try {
      const res = await API.listarProductos();
      if (!res.ok) throw new Error(res.error);
      productos = res.datos;
      renderLista();
    } catch (e) {
      document.getElementById('modifica-list').innerHTML =
        '<p style="padding:1rem;color:var(--accent)">Error: ' + e.message + '</p>';
    }
  }

  function renderLista() {
    const list = document.getElementById('modifica-list');
    if (!productos.length) {
      list.innerHTML = '<p style="padding:1rem;color:var(--muted)">No hay productos.</p>';
      return;
    }
    list.innerHTML = productos.map(p => `
      <div class="plist-item ${selId == p.id ? 'selected' : ''}" onclick="seleccionar(${p.id})">
        <div class="plist-thumb">${productThumb({ imagen: p.imagen, nombre: p.nombre }, 'sm')}</div>
        <div class="plist-info">
          <div class="name">${p.nombre}</div>
          <div class="price">${fmt(p.precio)} · Stock: ${p.stock}</div>
        </div>
      </div>
    `).join('');
  }

  function seleccionar(id) {
    selId = id;
    const p = productos.find(x => x.id == id);
    document.getElementById('mod-nombre').value      = p.nombre;
    document.getElementById('mod-descripcion').value = p.descripcion || '';
    document.getElementById('mod-precio').value      = p.precio;
    document.getElementById('mod-stock').value       = p.stock;
    setImagenEnForm('mod', p.imagen || null);
    document.getElementById('mod-form').style.display        = 'block';
    document.getElementById('mod-placeholder').style.display = 'none';
    renderLista();
  }

  async function guardarCambios() {
    if (!selId) return;
    const nombre      = document.getElementById('mod-nombre').value.trim();
    const descripcion = document.getElementById('mod-descripcion').value.trim();
    const precio      = parseFloat(document.getElementById('mod-precio').value);
    const stock       = parseInt(document.getElementById('mod-stock').value) || 0;
    const imgData     = document.getElementById('mod-upload-area').dataset.imgData;
    // imgData === '' significa "quitar imagen", undefined/null = no tocar
    const payload = { id: selId, nombre, descripcion, precio, stock };
    if (imgData !== undefined) payload.imagen = imgData || null;

    try {
      const res = await API.modificarProducto(payload);
      if (!res.ok) throw new Error(res.error);
      // Actualizar local
      const p = productos.find(x => x.id == selId);
      Object.assign(p, { nombre, descripcion, precio, stock });
      if (imgData !== undefined) p.imagen = imgData || null;
      renderLista();
      showToast('✓ Producto actualizado');
    } catch (e) {
      showToast('Error: ' + e.message, 'error');
    }
  }

  async function eliminar() {
    if (!selId) return;
    if (!confirm('¿Eliminar este producto?')) return;
    try {
      const res = await API.eliminarProducto(selId);
      if (!res.ok) throw new Error(res.error);
      productos = productos.filter(x => x.id != selId);
      selId = null;
      document.getElementById('mod-form').style.display        = 'none';
      document.getElementById('mod-placeholder').style.display = 'flex';
      renderLista();
      showToast('Producto eliminado');
    } catch (e) {
      showToast('Error: ' + e.message, 'error');
    }
  }

  cargar();
</script>
</body>
</html>
