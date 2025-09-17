formulario de creacion
<!-- enctype="multipart/form-data"   es para agregar imagenes o archivos-->
<form action="{{ url('/agenda') }}" method="post">
    @csrf
    @include('agenda.form',['modo'=>'Crear'])

    

</form> 