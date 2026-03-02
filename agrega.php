<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compras Porto — Agregar</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav class="site-nav">
  <a class="brand" href="index.php">Mercado Negro</a>
  <div class="nav-links">
    <a href="index.php">Productos</a>
    <a href="agrega.php" class="active">Agregar </a>
    <a href="modifica.php">Editar Producto</a>
  </div>
  <a class="cart-btn" href="carrito.php">
     Carrito <span class="badge" id="cart-badge">0</span>
  </a>
</nav>

<main>
  <div class="form-card">
    <h2>Agrega un nuevo producto.</h2>

    <div class="field">
      <label>Nombre del producto *</label>
      <input type="text" id="nombre" placeholder="Ej. Laptop HP 15">
    </div>

    <div class="field">
      <label>Descripción</label>
      <textarea id="descripcion" placeholder="Breve descripción…"></textarea>
    </div>

    <div class="field">
      <label>Precio (MXN) *</label>
      <input type="number" id="precio" placeholder="0.00" min="0" step="0.01">
    </div>

    <div class="field">
      <label>Stock disponible</label>
      <input type="number" id="stock" placeholder="0" min="0">
    </div>

    <div class="field">
      <label>Imagen del producto</label>
      <div class="img-upload-area" id="new-upload-area"
           onclick="document.getElementById('new-img-input').click()">
        <div class="img-upload-placeholder" id="new-img-placeholder">
          <span class="upload-icon">📂</span>
          <span>Haz clic para seleccionar imagen</span>
          <span class="upload-hint">JPG, PNG, WEBP — máx. 5 MB</span>
        </div>
        <img id="new-img-preview" class="img-preview" style="display:none" alt="Vista previa">
      </div>
      <input type="file" id="new-img-input" accept="image/*" style="display:none">
      <button type="button" class="clear-img-btn" id="new-clear-btn" style="display:none">
        ✕ Quitar imagen
      </button>
    </div>

    <div class="form-actions">
      <button class="btn btn-accent" onclick="guardar()">Guardar producto</button>
      <a class="btn btn-outline" href="index.php">Cancelar</a>
    </div>
  </div>
</main>

<div class="toast" id="toast"></div>
<footer class="site-footer">Compras Sencillas &copy; 2025</footer>

<script src="js/util.js"></script>
<script src="js/api.js"></script>
<script src="js/carrito.js"></script>
<script>
  configurarUploadImagen('new');

  async function guardar() {
    const nombre   = document.getElementById('nombre').value.trim();
    const precio   = parseFloat(document.getElementById('precio').value);
    const desc     = document.getElementById('descripcion').value.trim();
    const stock    = parseInt(document.getElementById('stock').value) || 0;
    const imgData  = document.getElementById('new-upload-area').dataset.imgData || null;

    if (!nombre || isNaN(precio) || precio <= 0) {
      showToast('⚠ Nombre y precio son requeridos', 'error');
      return;
    }

    try {
      const res = await API.agregarProducto({ nombre, descripcion: desc, precio, stock, imagen: imgData });
      if (!res.ok) throw new Error(res.error);
      showToast('✓ Producto guardado');
      setTimeout(() => location.href = 'index.php', 1200);
    } catch (e) {
      showToast('Error: ' + e.message, 'error');
    }
  }
</script>
</body>
</html>
