// js/carrito.js — manejo del carrito en sessionStorage

const Carrito = {
  _key: 'compras_carrito',

  obtener() {
    return JSON.parse(sessionStorage.getItem(this._key) || '[]');
  },

  guardar(items) {
    sessionStorage.setItem(this._key, JSON.stringify(items));
    this._actualizarBadge();
  },

  agregar(producto) {
    const items = this.obtener();
    const exist = items.find(i => i.id === producto.id);
    if (exist) {
      exist.qty++;
    } else {
      items.push({ ...producto, qty: 1 });
    }
    this.guardar(items);
  },

  cambiarCantidad(id, delta) {
    const items = this.obtener();
    const item  = items.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    this.guardar(items);
  },

  quitar(id) {
    this.guardar(this.obtener().filter(i => i.id !== id));
  },

  vaciar() {
    this.guardar([]);
  },

  total() {
    return this.obtener().reduce((s, i) => s + i.precio * i.qty, 0);
  },

  _actualizarBadge() {
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    const total = this.obtener().reduce((s, i) => s + i.qty, 0);
    badge.textContent = total;
  },
};

// Actualizar badge al cargar cualquier página
document.addEventListener('DOMContentLoaded', () => Carrito._actualizarBadge());
