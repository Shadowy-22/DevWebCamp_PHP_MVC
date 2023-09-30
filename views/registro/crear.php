<main class="registro">
    <h2 class="registro__heading"><?php echo $titulo; ?></h2>
    <p class="registro__descripcion">Elige tu plan</p>

    <div class="paquetes__grid">
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Gratis</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Virtual a DevWebcamp</li>
            </ul>
            <p class="paquete__precio">$0</p>

            <form method="POST" action="/finalizar-registro/gratis">
                <input type="submit" value="Inscripción Gratis" class="paquetes__submit">
            </form>

        </div>
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Presencial</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Presencial a DevWebcamp</li>
                <li class="paquete__elemento">Pase por 2 días</li>
                <li class="paquete__elemento">Acceso a Talleres y Conferencias</li>
                <li class="paquete__elemento">Acceso a las Grabaciones</li>
                <li class="paquete__elemento">Camisa del Evento</li>
                <li class="paquete__elemento">Comida y Bebida</li>
            </ul>
            <p class="paquete__precio">$199</p>

            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container"></div>
                </div>
            </div>

        </div>
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Virtual</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Virtual a DevWebcamp</li>
                <li class="paquete__elemento">Pase por 2 días</li>
                <li class="paquete__elemento">Enlace a Talleres y Conferencias</li>
                <li class="paquete__elemento">Acceso a las Grabaciones</li>
            </ul>
            <p class="paquete__precio">$49</p>

            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container-virtual"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://www.paypal.com/sdk/js?client-id=AUQml7gR83Y4K-8MH3MW7Y9fUcdax2qq5HTmQl8E65b4YseYswSdG33dvbxomal5gdzmsdJ_XnhYedjK&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>

<script>
    function initPayPalButton() {
      // Pase Presencial
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
        },
 
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"description":"1","amount":{"currency_code":"USD","value":199}}]
          });
        },
 
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
 
            // Detalles Completos de la Compra
            // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
 
            const datos = new FormData();
            datos.append('paquete_id', orderData.purchase_units[0].description);
            datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id);
            
            fetch('/finalizar-registro/pagar', {
                method: 'POST',
                body: datos
            })
            .then( respuesta => respuesta.json())
            .then( resultado => {
              if(resultado.resultado) {
                actions.redirect(resultado.host + '/finalizar-registro/conferencias');
              }
            })

          });
        },
 
        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');

      // Pase Virtual
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
        },
 
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"description":"2","amount":{"currency_code":"USD","value":49}}]
          });
        },
 
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
 
            const datos = new FormData();
            datos.append('paquete_id', orderData.purchase_units[0].description);
            datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id);
            
            fetch('/finalizar-registro/pagar', {
                method: 'POST',
                body: datos
            })
            .then( respuesta => respuesta.json())
            .then( resultado => {
              if(resultado.resultado) {
                actions.redirect(resultado.host + '/finalizar-registro/conferencias');
              }
            })

          });
        },
 
        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container-virtual');
    }
 
  initPayPalButton();
</script>