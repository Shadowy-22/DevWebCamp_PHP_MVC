<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo ?></h2>
    <p class="auth__texto">Coloca tu nuevo password</p>

    <?php
        require_once __DIR__ . '/../templates/alertas.php';
    ?>

    <?php if($token_valido): ?>

        <form class="formulario" method="POST">
            <div class="formulario__campo">
                <label for="password" class="formulario__label">Nuevo Password</label>
                <input 
                    type="password"
                    class="formulario__input"
                    placeholder="Tu Nuevo Password"
                    name="password"
                    id="password"
                />
            </div>

            <input type="submit" class="formulario__submit" value="Guardar Password">
        </form>

        <div class="acciones">
            <a href="/registro" class="acciones__enlace">¿Aún no tienes cuenta? Obtén una</a>
            <a href="/olvide" class="acciones__enlace">¿Olvidaste tu Password?</a>
        </div>

    <?php endif; ?>

</main>