<h2 class="pagina__heading"><?php echo $titulo; ?></h2>
<p class="pagina__descripcion">Elige hasta 5 eventos para asistir de forma presencial.</p>

<div class="eventos-registro">
    <main class="eventos-registro__listado">
        <h3 class="eventos-registro__heading--conferencias">&lt;Conferencias /></h3>
        <p class="eventos-registro__fecha">Viernes 6 de Octubre</p>

        <div class="eventos-registro__grid">
            <?php foreach($eventos['Conferencias']['Viernes'] as $evento): ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php endforeach; ?>
        </div>

        <p class="eventos-registro__fecha">Sábado 7 de Octubre</p>
        <div class="eventos-registro__grid">
            <?php foreach($eventos['Conferencias']['Sábado'] as $evento): ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php endforeach; ?>
        </div>

        <h3 class="eventos-registro__heading--workshops">&lt;Workshops /></h3>
        <p class="eventos-registro__fecha">Viernes 6 de Octubre</p>

        <div class="eventos-registro__grid eventos--workshops">
            <?php foreach($eventos['Workshops']['Viernes'] as $evento): ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php endforeach; ?>
        </div>

        <p class="eventos-registro__fecha">Sábado 7 de Octubre</p>
        <div class="eventos-registro__grid eventos--workshops">
            <?php foreach($eventos['Workshops']['Sábado'] as $evento): ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php endforeach; ?>
        </div>
    </main>

    <aside class="lista-registro">
        <h2 class="lista-registro__heading">Tu Registro</h2>

        <div id="lista-registro-resumen" class="lista-registro__resumen"></div>

        <div class="lista-registro__regalo">
            <label for="regalo" class="lista-registro__label">Seleccionar un Regalo</label>
            <select id="regalo" class="lista-registro__select">
                <option value="" selected disabled>-- Selecciona tu regalo --</option>
                <?php foreach($regalos as $regalo): ?>
                    <option value="<?php echo $regalo->id; ?>"><?php echo $regalo->nombre; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <form id="registro" class="formulario">
            <div class="formulario__campo">
                <input type="submit" class="formulario__submit formulario__submit--full" value="Registrarme">
            </div>
        </form>
    </aside>
</div>