
<h1>{{ $modo}} agenda</h1>
    
    <label for="Tipo"> Tipo </label>
    <input type="text" name="Tipo" value="{{ isset($agenda->tipo)? $agenda->tipo:'' }}" id="Tipo">
    <br>

    <label for="Descripcion"> Descripcion </label>
    <input type="text" name="Descripcion" value="{{ isset($agenda->descripcion)? $agenda->descripcion:'' }}" id="Descripcion">
    <br>

    <label for="Folio"> folio </label>
    <input type="number" name="Folio" value="{{ isset($agenda->folio)? $agenda->folio:'' }}" id="Folio">
    <br>

    <label for="User_id"> Usuario </label>
    <input type="number" name="User_id" value="{{ isset($agenda->user_id)? $agenda->user_id:'' }}" id="User_id">
    <br>

    <label for="Municipio"> Municipio </label>
    <input type="text" name="Municipio" value="{{ isset($agenda->municipio)? $agenda->municipio:'' }}" id="Municipio">
    <br>

    <label for="Poblacion"> Poblacion </label>
    <input type="text" name="Poblacion" value="{{ isset($agenda->poblacion)? $agenda->poblacion:'' }}" id="Poblacion">
    <br>
 
    <label for="FechaInicio"> Fecha de inicio </label>
    <input type="date" name="FechaInicio" value="{{ isset($agenda->fechaInicio)? $agenda->fechaInicio:'' }}" id="FechaInicio">
    <br>

    <label for="HoraInicio"> Hora de inicio </label>
    <input type="time" name="HoraInicio" value="{{ isset($agenda->horaInicio)? $agenda->horaInicio:'' }}" id="HoraInicio">
    <br>

    <label for="HoraFin"> Hora de fin </label>
    <input type="time" name="HoraFin" value="{{ isset($agenda->horaFin)? $agenda->horaFin:'' }}"  id="HoraFin">
    <br>

    <input type="submit" value="{{ $modo }} datos">
    <br>
    <a href="{{ url('agenda') }}">Regresar </a>


    
