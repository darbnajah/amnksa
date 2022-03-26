@if($employees->count() > 0)

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
            <th>الإسم الموظف</th>
            <th>الوظيفة</th>
            <th>موقع العمل</th>
        </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <td class="table_actions">
                @if($source == 'paie_list')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="appendSalary({{ json_encode($employee) }})"
                    ><i class="fa fa-check"></i> اختر</button>
                @elseif($source == 'deductions_advances')
                    <a href="{{ route('dashboard.deductions_advances.index', $employee->id) }}" class="btn btn-sm btn-success"><i class="fa fa-check"></i> اختر</a>
                @elseif($source == 'employees_balance')
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="selectEmployeeForBalance({{ json_encode($employee) }})"
                    ><i class="fa fa-check"></i> اختر</button>
                @endif
            </td>
            <td>{{ $employee->employee_name }}</td>
            <td>{{ $employee->job_name }}</td>
            <td>{{ $employee->work_zone }}</td>
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

