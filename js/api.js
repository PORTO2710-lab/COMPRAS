// js/api.js — funciones de comunicación con el backend PHP

const API = {
  base: 'php/',

  async _post(endpoint, data) {
    const res = await fetch(API.base + endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
    return res.json();
  },

  listarProductos() {
    return fetch(API.base + 'productos_lista.php').then(r => r.json());
  },

  agregarProducto(datos) {
    return API._post('producto_agrega.php', datos);
  },

  modificarProducto(datos) {
    return API._post('producto_modifica.php', datos);
  },

  eliminarProducto(id) {
    return API._post('producto_elimina.php', { id });
  },

  guardarPedido(items, total) {
    return API._post('pedido_guarda.php', { items, total });
  },
};
