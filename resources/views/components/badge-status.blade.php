{{-- estado string --}}
<div>
    @if ($status == 'activo' || $status == 1 || $status === true)
        <button type="button" title="{{ $activeLabel ?? 'Activo' }}" data-bs-toggle="tooltip" data-bs-placement="right"
            class="btn rounded-pill btn-sm btn-icon btn-label-success text-success waves-effect">
            <span class="icon-base ti tabler-check icon-22px"></span>
        </button>
    @elseif($status == 'inactivo' || $status == 0 || $status === false)
        <button type="button" title="{{ $inactiveLabel ?? 'Inactivo' }}" data-bs-toggle="tooltip" data-bs-placement="right"
            class="btn rounded-pill btn-sm btn-icon btn-label-warning text-warning waves-effect">
            <span class="icon-base ti tabler-x icon-22px"></span>
        </button>
    @endif
</div>
