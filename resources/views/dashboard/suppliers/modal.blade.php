@if($suppliers->count() > 0)

    <div class="box box-primary">
        <div class="box-header with-border">
            <input type="text" name="search" class="form-control"  id="dt_search" placeholder="@lang('site.search')">
        </div>
    </div>
    <div class="table-responsive">
        <table id="example" class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th>اسم المورد</th>
            <th>رقم الجوال</th>
        </tr>
    </thead>
    <tbody>
    @foreach($suppliers as $supplier)
        <tr>
            <td class="table_actions">
                @if($source == 'payment')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectSupplier({{ json_encode($supplier) }}, '{{ route('dashboard.suppliers_payments.contracts_by_supplier', $supplier->id) }}')"
                    ><i class="fa fa-check"></i> اختر</button>
                @elseif($source == 'suppliers_balance')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectSupplierForBalance({{ json_encode($supplier) }})"
                    ><i class="fa fa-check"></i> اختر</button>
                @endif
            </td>
            <td>{{ $supplier->supplier_name }}</td>
            <td>{{ $supplier->mobile }}</td>
    @endforeach
    </tbody>
</table>
    </div>
<script>
    var table = $('#example').DataTable({
        "search": false
    });
    $('#dt_search').on( 'keyup', function () {
        table.search( this.value ).draw();
    } );
    setTimeout(function (){
        $('#dt_search').focus();

    }, 500);
</script>
@endif

