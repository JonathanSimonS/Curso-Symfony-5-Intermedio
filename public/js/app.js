$('.finalizar').on('click', function (e) {
    e.preventDefault(); // cancelamos el evento por defecto que es una redireccion
    var $this = $(this),
        url = $this.data('url'),
        $descripcion = $this.closest('tr').find('.descripcion')
    textoContrario = $this.data('texto');
    textoActual = $this.text();

    $this.addClass('disabled');
    $descripcion.addClass('tachado');

    $.post(url, {})
        .done(function (respuesta) {
            
            if (respuesta.finalizada) {
                $descripcion.addClass('tachado');
            } else {
                $descripcion.removeClass('tachado');
                $descripcion.html($descripcion.text());
            }
            console.log($descripcion);

            $this.text(textoContrario);
            $this.data('texto', textoActual);
            $this.removeClass('disabled');
        })
        .fail(function () {
            $this.removeClass('disabled');
        });
});