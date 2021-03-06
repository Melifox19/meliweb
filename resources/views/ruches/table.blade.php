<table class="table table-responsive" id="ruches-table">
    <thead>
        <tr>
          <th>ID</th>
            <th>{{ __('tables.in_apiary')}}</th>
            <th>{{__('tables.local_addr')}}</th>
            <th>{{__('tables.hive_typ')}}</th>
            <th>{{ __('tables.sigfox_id')}}</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ruches as $ruche)
        @foreach($ruchers as $rucher)
        @if( $ruche->idRucher == $rucher->id )
        <tr>
          <td>{!! $ruche->id !!}</td>
            <td>{!! $ruche->idRucher !!} - {!! $rucher->nom !!}</td>
            <td>{!! $ruche->addrMelinet !!}</td>
            <td>{!! $ruche->type !!}</td>
            <td>{!! $ruche->idSigfox !!}</td>
            <td>
                {!! Form::open(['route' => ['ruches.destroy', $ruche->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('ruches.show', [$ruche->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('ruches.edit', [$ruche->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
        @endif
        @endforeach
        @endforeach
    </tbody>
</table>
