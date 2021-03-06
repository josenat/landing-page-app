<div class="table-responsive">
    <table class="table" id="titles-table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th colspan="3">Acción</th>
            </tr>
        </thead>
        <tbody>
        @foreach($titles as $title)
            <tr>
                <td>{!! substr($title->description, 0, 160) !!}...</td>
                <td>
                    {!! Form::open(['route' => ['titles.destroy', $title->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('titles.show', [$title->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('titles.edit', [$title->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('¿Está seguro?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
