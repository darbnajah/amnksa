@if($sellers->count() > 0)

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
            <th>اسم المندوب</th>
            <th>رقم الجوال</th>
            <th>الإيميل</th>
        </tr>
    </thead>
    <tbody>
    @foreach($sellers as $seller)
        <tr>
            <td class="table_actions">
                @if($source == 'payment')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectSeller({{ json_encode($seller) }}, '{{ route('dashboard.sellers_payments.contracts_by_seller', $seller->id) }}')"
                    ><i class="fa fa-check"></i> اختر</button>
                @elseif($source == 'deductions_advances')
                    <a href="{{ route('dashboard.sellers_deductions_advances.index', $seller->id) }}" class="btn btn-sm btn-success"><i class="fa fa-check"></i> اختر</a>
                @elseif($source == 'sellers_balance')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectSellerForBalance({{ json_encode($seller) }})"
                    ><i class="fa fa-check"></i> اختر</button>
                @endif
            </td>
            <td>{{ $seller->first_name }}</td>
            <td>{{ $seller->mobile_1 }}</td>
            <td>{{ $seller->email }}</td>
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

