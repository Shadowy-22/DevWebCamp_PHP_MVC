<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Evento</legend>
    
    <div class="formulario__campo">
        <label class="formulario__label" for="nombre">Nombre Evento</label>
        <input 
            type="text"
            class="formulario__input"
            id="nombre"
            name="nombre"
            placeholder="Nombre Evento"
            value="<?php echo $evento->nombre ?? ''; ?>"
        />
    </div>

    <div class="formulario__campo">
        <label class="formulario__label" for="descripcion">Descripción</label>
        <textarea 
            class="formulario__input"
            id="descripcion"
            name="descripcion"
            placeholder="Descripción Evento"
            value="<?php echo $evento->descripcion ?? ''; ?>"
            rows="8"
        /></textarea>
    </div>

    <div class="formulario__campo">
        <label class="formulario__label" for="categoria">Categoría o Tipo de Evento</label>
        <select 
            class="formulario__select"
            id="categoria"
            name="categoria_id"
        />
            <option value="" selected disabled>--Selectionar--</option>
            <?php foreach($categorias as $categoria): ?>
                <option value="<?php echo $categoria->id; ?>"><?php echo $categoria->nombre; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="formulario__campo">
        <label class="formulario__label" for="dia">Selecciona el día</label>

        <div class="formulario__radio">
            <?php foreach($dias as $dia): ?>
                <div>
                    <label for="<?php echo strtolower($dia->nombre); ?>"><?php echo $dia->nombre; ?></label>
                    <input 
                        type="radio"
                        id="<?php echo strtolower($dia->nombre); ?>"
                        name="dia"
                        value="<?php echo $dia->id; ?>"
                    >
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</fieldset>