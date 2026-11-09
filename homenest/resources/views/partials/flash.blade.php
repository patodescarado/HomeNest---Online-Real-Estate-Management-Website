
@foreach ([
    'success' => 'success',
    'error' => 'danger',
    'warning' => 'warning',
    'info' => 'info',
] as $key => $class)

    @if (session($key))
        <div class="alert alert-{{ $class }} alert-dismissible fade show rounded-3"
             role="alert">
            {{ session($key) }}

            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

@endforeach

@if ($errors->any())
    <div class="alert alert-danger rounded-3" role="alert">
        <strong>Vui lòng kiểm tra lại thông tin:</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
