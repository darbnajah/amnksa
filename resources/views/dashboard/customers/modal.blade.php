
@if($customers)
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
            <th>رقم العميل</th>
            <th>اسم العميل</th>
            <th>عنوان العميل</th>
            <th>المدينة</th>
        </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td class="table_actions">
                @if($source == 'home')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectCustomerHome({{ json_encode($customer) }}, '{{ route('dashboard.customer_statistics', $customer->id) }}')"><i class="fa fa-check"></i> اختر</button>
                 @elseif($source == 'cost')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectCustomerCost({{ json_encode($customer) }}, '{{ route('dashboard.cost.customer_cost', $customer->id) }}')"><i class="fa fa-check"></i> اختر</button>
                 @elseif($source == 'payment')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectCustomerForPayment({{ json_encode($customer) }}, '{{ route('dashboard.payments.contracts_by_customer', $customer->id) }}')"><i class="fa fa-check"></i> اختر</button>
                 @elseif($source == 'customer_expense')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectCustomerForExpense({{ json_encode($customer) }}, '{{ route('dashboard.customer_expenses.contracts_by_customer', $customer->id) }}')"><i class="fa fa-check"></i> اختر</button>
                @elseif($source == 'employee')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectCustomerForEmployee({{ json_encode($customer) }}, '{{ route('dashboard.employees.contracts_by_customer', $customer->id) }}')"><i class="fa fa-check"></i> اختر</button>
                @elseif($source == 'paie')
                    <button type="button" class="btn btn-sm btn-success"
                            onclick="selectCustomerForPaie({{ json_encode($customer) }}, '{{ route('dashboard.paies.contracts_by_customer', $customer->id) }}', {{ $row_id }})"><i class="fa fa-check"></i> اختر</button>
                @elseif($source == 'invoice')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectCustomer({{ $customer }}, '{{ route('dashboard.invoices.contracts_by_customer', $customer->id) }}')"><i class="fa fa-check"></i> اختر</button>
                @elseif($source == 'customer_balance')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectCustomerForBalance({{ $customer }})"><i class="fa fa-check"></i> اختر</button>
                    @endif
            </td>
            <td>{{ $customer->code }}</td>
            <td>{{ $customer->name_ar }}</td>
            <td>{{ $customer->address_ar }}</td>
            <td>{{ $customer->city }}</td>
        </tr>
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

