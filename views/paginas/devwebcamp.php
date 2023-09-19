<main class="devwebcamp">
    <h2 class="devwebcamp__heading"><?php echo $titulo; ?></h2>
    <p class="devwebcamp__descripcion">Conoce la conferencia mas importante de LatinoAmérica</p>

    <div class="devwebcamp__grid">
        <div <?php aos_animacion(); ?> class="devwebcamp__imagen">
            <picture>
                <source srcset="build/img/sobre_devwebcamp.avif" type="image/avif">
                <source srcset="build/img/sobre_devwebcamp.webp" type="image/webp">
                <img loading="lazy" width="200" height="300" src="build/img/sobre_devwebcamp.jpg" alt="Imagen DevWebcamp">
            </picture>
        </div>

        <div <?php aos_animacion(); ?> class="devwebcamp__contenido">
            <p class="devwebcamp__texto">Cras lobortis libero et risus vulputate fringilla. Nulla scelerisque velit sed enim porttitor, ut aliquet lacus mollis. Duis luctus velit massa, vel volutpat diam gravida at. Quisque pellentesque nisi ex, quis egestas tortor molestie ac. Nulla ut ex ipsum. Nulla pulvinar a risus at sodales. </p>
            
            <p class="devwebcamp__texto">Cras lobortis libero et risus vulputate fringilla. Nulla scelerisque velit sed enim porttitor, ut aliquet lacus mollis. Duis luctus velit massa, vel volutpat diam gravida at. Quisque pellentesque nisi ex, quis egestas tortor molestie ac. Nulla ut ex ipsum. Nulla pulvinar a risus at sodales. </p>
        </div>
    </div>
</main>