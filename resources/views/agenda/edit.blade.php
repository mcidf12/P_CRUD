
<form action="{{ url('/agenda/'.$agenda->id) }}" method="post">
    @csrf
    {{ method_field('PATCH') }}

    @include('agenda.form',['modo'=>'Editar'])


</form>
