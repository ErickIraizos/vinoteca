// Funcionalidad principal del sitio
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización de tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inicialización de popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Scroll to top
    const scrollToTopButton = document.createElement('div');
    scrollToTopButton.classList.add('scroll-to-top');
    scrollToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    document.body.appendChild(scrollToTopButton);

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopButton.classList.add('visible');
        } else {
            scrollToTopButton.classList.remove('visible');
        }
    });

    scrollToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Sistema de notificaciones
    const toastContainer = document.createElement('div');
    toastContainer.classList.add('toast-container');
    document.body.appendChild(toastContainer);

    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.classList.add('toast');
        toast.classList.add(`toast-${type}`);
        toast.innerHTML = `
            <div class="toast-header">
                <i class="fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-circle text-danger'} me-2"></i>
                <strong class="me-auto">${type === 'success' ? 'Éxito' : 'Error'}</strong>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        toastContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    };

    // Formulario de newsletter
    const newsletterForms = document.querySelectorAll('.newsletter-form');
    newsletterForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            try {
                const response = await fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('¡Gracias por suscribirte a nuestro newsletter!');
                    this.reset();
                } else {
                    showToast(data.message || 'Ha ocurrido un error', 'error');
                }
            } catch (error) {
                showToast('Ha ocurrido un error al procesar tu solicitud', 'error');
            }
        });
    });

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let timeoutId;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            
            timeoutId = setTimeout(async () => {
                const query = this.value.trim();
                if (query.length < 3) return;

                try {
                    const response = await fetch(`/productos/buscar?q=${encodeURIComponent(query)}`);
                    const data = await response.json();
                    
                    // Aquí puedes mostrar los resultados en un dropdown
                    showSearchResults(data);
                } catch (error) {
                    console.error('Error en la búsqueda:', error);
                }
            }, 300);
        });
    }

    // Función para mostrar resultados de búsqueda
    function showSearchResults(results) {
        const searchResults = document.getElementById('search-results');
        if (!searchResults) return;

        searchResults.innerHTML = '';
        
        if (results.length === 0) {
            searchResults.innerHTML = '<div class="p-3">No se encontraron resultados</div>';
            return;
        }

        results.forEach(product => {
            const productElement = document.createElement('a');
            productElement.href = `/producto/${product.producto_id}`;
            productElement.classList.add('search-result-item', 'd-flex', 'align-items-center', 'p-2', 'text-decoration-none');
            productElement.innerHTML = `
                <img src="${product.imagen_url}" alt="${product.nombre}" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                <div>
                    <div class="fw-bold text-dark">${product.nombre}</div>
                    <div class="text-muted small">${product.categoria_nombre}</div>
                    <div class="text-primary">€${product.precio_final}</div>
                </div>
            `;
            searchResults.appendChild(productElement);
        });
    }

    // Carrito de compras
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = document.querySelector(`input[name="quantity"][data-product-id="${productId}"]`)?.value || 1;

            try {
                const response = await fetch('/carrito/agregar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        producto_id: productId,
                        cantidad: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Producto agregado al carrito');
                    updateCartCount(data.cartCount);
                } else {
                    showToast(data.message || 'Error al agregar al carrito', 'error');
                }
            } catch (error) {
                showToast('Error al procesar la solicitud', 'error');
            }
        });
    });

    // Actualizar contador del carrito
    function updateCartCount(count) {
        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = count;
            cartCountElement.style.display = count > 0 ? 'block' : 'none';
        }
    }

    // Favoritos
    const favoriteButtons = document.querySelectorAll('.toggle-favorite');
    favoriteButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const isFavorite = this.classList.contains('active');

            try {
                const response = await fetch('/favoritos/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        producto_id: productId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.classList.toggle('active');
                    this.querySelector('i').classList.toggle('fas');
                    this.querySelector('i').classList.toggle('far');
                    showToast(data.message);
                } else {
                    showToast(data.message || 'Error al procesar la solicitud', 'error');
                }
            } catch (error) {
                showToast('Error al procesar la solicitud', 'error');
            }
        });
    });

    // Validación de formularios
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Lazy loading de imágenes
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
});

// Función para formatear precios
window.formatPrice = function(price) {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
};

// Función para validar edad
window.validateAge = function(birthDate) {
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    
    return age >= 18;
}; 