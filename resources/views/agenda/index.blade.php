
@if(Session::has('mensaje'))
    {{ Session::get('mensaje') }}   
@endif

<a href="{{ url('agenda/create') }}">Registrar </a>
<table class="table table-light">

    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Descripcion</th>
            <th>Folio</th>
            <th>User</th>
            <th>Municipio</th>
            <th>Poblacion</th>
            <th>Fecha de Inicio</th>
            <th>Hora de Inicio</th>
            <th>Hora de Fin</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach ( $agendas as $agenda )

        <tr>
            <td>{{ $agenda->id }}</td>
            <td>{{ $agenda->tipo }}</td>
            <td>{{ $agenda->descripcion }}</td>
            <td>{{ $agenda->folio }}</td>
            <td>{{ $agenda->user_id }}</td>
            <td>{{ $agenda->municipio }}</td>
            <td>{{ $agenda->poblacion }}</td>
            <td>{{ $agenda->fechaInicio }}</td>
            <td>{{ $agenda->horaInicio }}</td>
            <td>{{ $agenda->horaFin }}</td>
            <td>
                
                <a href="{{ url('/agenda/'.$agenda->id.'/edit') }}">
                    Editar
                </a>
                
                | 

                <form action="{{ url('/agenda/'.$agenda->id) }}" method="post">
                
                    @csrf
                    {{ method_field('DELETE') }} <!-- metodo para hacer el borrrado -->
                    <input type="submit" onclick="return confirm('¿Quieres borrar?')" value="Borrar">
                
                </form>
            </td>
        </tr>

        @endforeach

    </tbody>
</table>