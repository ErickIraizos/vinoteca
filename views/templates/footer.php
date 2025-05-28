    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row">
                <!-- Información de contacto -->
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Calle Principal 123, Madrid
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            +34 900 123 456
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            informe@vinoteca.com
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock me-2"></i>
                            Lun - Sáb: 9:00 - 20:00
                        </li>
                    </ul>
                </div>

                <!-- Enlaces rápidos -->
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>sobre-nosotros" class="text-white">Sobre Nosotros</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>blog" class="text-white">Blog</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>contacto" class="text-white">Contacto</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>faq" class="text-white">Preguntas Frecuentes</a>
                        </li>
                    </ul>
                </div>

                <!-- Categorías -->
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">Categorías</h5>
                    <ul class="list-unstyled">
                        <?php if (isset($menuCategorias)): ?>
                            <?php foreach (array_slice($menuCategorias, 0, 5) as $cat): ?>
                                <li class="mb-2">
                                    <a href="<?php echo BASE_URL; ?>categoria/ver/<?php echo $cat['categoria_id']; ?>" class="text-white">
                                        <?php echo $cat['nombre']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">Newsletter</h5>
                    <p class="mb-3">Suscríbete para recibir nuestras últimas novedades y ofertas especiales.</p>
                    <form id="newsletter-form" class="mb-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Tu email" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Bottom Footer -->
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="<?php echo BASE_URL; ?>public/img/payment-methods.png" alt="Métodos de pago" class="img-fluid" style="max-height: 30px;">
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/js/app.js"></script>

    <!-- Scripts específicos de la página -->
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo BASE_URL; ?>public/js/<?php echo $script; ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html> 