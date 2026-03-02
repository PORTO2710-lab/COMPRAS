// js/util.js — utilidades compartidas

function fmt(n) {
  return '$' + Number(n).toLocaleString('es-MX', { minimumFractionDigits: 2 });
}

function showToast(msg, tipo = 'ok') {
  let t = document.getElementById('toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toast';
    document.body.appendChild(t);
  }
  t.textContent = msg;
  t.className = 'toast show ' + tipo;
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.classList.remove('show'), 2800);
}

function productThumb(p, size = 'lg') {
  if (p.imagen) {
    const s = size === 'lg' ? 'width:100%;height:100%;object-fit:cover;' : 'width:40px;height:40px;object-fit:cover;border-radius:2px;';
    return `<img src="${p.imagen}" alt="${p.nombre}" style="${s}">`;
  }
  const fs = size === 'lg' ? '2.5rem' : '1.5rem';
  return `<span style="font-size:${fs};color:var(--muted)">${p.nombre.charAt(0).toUpperCase()}</span>`;
}

// Leer archivo como base64
function leerImagenBase64(file) {
  return new Promise((resolve, reject) => {
    if (file.size > 5 * 1024 * 1024) { reject('La imagen supera 5 MB'); return; }
    const r = new FileReader();
    r.onload  = e => resolve(e.target.result);
    r.onerror = () => reject('Error al leer el archivo');
    r.readAsDataURL(file);
  });
}

// Helpers de previsualización de imagen en formularios
function configurarUploadImagen(prefijo) {
  const input     = document.getElementById(prefijo + '-img-input');
  const area      = document.getElementById(prefijo + '-upload-area');
  const preview   = document.getElementById(prefijo + '-img-preview');
  const ph        = document.getElementById(prefijo + '-img-placeholder');
  const clearBtn  = document.getElementById(prefijo + '-clear-btn');

  if (!input) return;

  input.addEventListener('change', async () => {
    const file = input.files[0];
    if (!file) return;
    try {
      const data = await leerImagenBase64(file);
      area.dataset.imgData = data;
      preview.src = data;
      preview.style.display = 'block';
      ph.style.display = 'none';
      clearBtn.style.display = 'inline';
    } catch (err) {
      showToast('⚠ ' + err, 'error');
    }
  });

  clearBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    area.dataset.imgData = '';
    input.value = '';
    preview.style.display = 'none';
    ph.style.display = 'flex';
    clearBtn.style.display = 'none';
    preview.src = '';
  });
}

function setImagenEnForm(prefijo, imgData) {
  const area     = document.getElementById(prefijo + '-upload-area');
  const preview  = document.getElementById(prefijo + '-img-preview');
  const ph       = document.getElementById(prefijo + '-img-placeholder');
  const clearBtn = document.getElementById(prefijo + '-clear-btn');

  area.dataset.imgData = imgData || '';
  if (imgData) {
    preview.src = imgData;
    preview.style.display = 'block';
    ph.style.display = 'none';
    clearBtn.style.display = 'inline';
  } else {
    preview.style.display = 'none';
    ph.style.display = 'flex';
    clearBtn.style.display = 'none';
    preview.src = '';
  }
}
