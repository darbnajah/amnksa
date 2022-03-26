var app_domain = 'http://pos_laravel.test/',
    app_index =  app_domain;

/*const monthNames_en = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];*/
const monthNames_en = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
];
const monthNames_ar = ["يناير", "فبراير", "مارس", "ابريل", "مايو", "يونيو",
    "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"
];

const monthDays_count = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];


$(document).ready(function() {

    NProgress.start();
    setTimeout(function(){
        NProgress.done();
        $('.content').css('opacity', 1);

        //selectizeSELECT('#selectize_customer_id');
        /*$('select#selectize_customer_id').selectize({
            onDropdownClose: function(dropdown) {

            },
            onChange: function(value) {
                //showCustomerInfo(value);
                loadContracts(value);
            }
        });*/

    }, 200);
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })


    //$('#example').DataTable();

    $('.paies_trasfered_table thead th').each( function () {
        var title = $(this).text();
        $(this).append( '<input type="text" placeholder="بحث" />' );
    } );
    var table = $('#example').DataTable({
        //"pageLength": -1,
        //"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All" ] ],
        "lengthMenu": [[-1, 25, 50, 100], ["All", 25, 50, 100]],
        "search": false,
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;

                $( 'input[type=text]', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                            setSearchSession();
                    }
                } );
            } );
        }

    });

    table.on( 'search.dt', function () {
        if($('#example').hasClass('paie_table')) {
            //console.log('search stated');
            setTimeout(function () {
                var total_deductions = 0,
                    total_advances = 0,
                    total_net = 0;
                var rows = $('#example tbody tr');
                for (var i = 0; i < rows.length; i++) {
                    total_deductions += val($(rows[i]).children('.td_deduction').text());
                    total_advances += val($(rows[i]).children('.td_advance').text());
                    total_net += val($(rows[i]).children('.td_net').text());
                }
                //console.log(total_deductions);

                $('#example tfoot tr:first-child .foot_total_deductions').text(nFormat(total_deductions));
                $('#example tfoot tr:first-child .foot_total_advances').text(nFormat(total_advances));
                $('#example tfoot tr:first-child .foot_total_net').text(nFormat(total_net));
                $('#example tfoot tr:first-child .foot_total_global').text(nFormat(total_net + total_advances));

            }, 2000);
        }
    });

    $('#dt_search').on( 'keyup', function () {
        table.search( this.value ).draw();
    } );
    /*
    $('.dt_search_sync_session').on( 'keyup', function () {
        setDtSearchSession(this.value);
    } );
*/
    var deductions_table = $('#deductions_table').DataTable({
        "search": false
    });
    $('#deductions_search').on( 'keyup', function () {
        deductions_table.search( this.value ).draw();
    } );

    var advances_table = $('#advances_table').DataTable({
        "search": false
    });
    $('#advances_search').on( 'keyup', function () {
            advances_table.search( this.value ).draw();
        } );



    } );

    function selectizeSELECT(select){
        var selectized = $(select).selectize();
        var control = selectized[0].selectize;
        control.clear();
    }

    function setDefault(route){
        var id = $(this).data("id");
        //var token = $(this).data("token");
        var token = $('meta[name="csrf-token"]').attr('content');

        startLoader();

        $.ajax({
            url: route,
            type: 'POST',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'POST',
                "_token": token,
            },
            success: function (data)
            {
                window.location.reload();
            },
            error: function (){
                window.location.reload();
            }

        });

    }

    function startLoader(){
        $('#overlay').show();
        NProgress.start();
        setTimeout(function(){
            NProgress.set(0.3);
        }, 200);

    }
    function stopLoader(){
        setTimeout(function(){
            $('#overlay').hide();
            NProgress.done();
        }, 200);
    }



    function triggerInputFile(input){
        $('#' + input).click();
    }

    function readUrl(input){
        if (input.files && input.files[0]){
            var file = input.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (e) {
                $(input).next('.thumb_preview').attr('src', e.target.result);
            }
        }
    }


    function ajaxPost(obj, callback){
        startLoader();
        $.post(
            obj.request,
            obj,
            function(data){
                callback(data, obj);
                stopLoader();
            },
            'html'
        );
    }


    function letters(str){
        return str.replace(/[0-9]/g, '');
    }
    function setDbNameText(input){
        var str = letters($(input).val());
        str = str.replace(/ /g, '_');
        str = str.toLowerCase();
        $('#company_db_name').val(str);
    }

    function appendRow(){

        var row = `<tr row_id="">
            <td class="table_actions">
                <button type="button" class="btn_remove btn btn-danger" onclick="deleteRow(this)"><i class="fa fa-times"></i></button>
            </td>
            <td class="td_label">
                <input type="text" class="form-control" placeholder="البيان">
            </td>
            <td class="td_amount">
                <input type="text" class="form-control currency" onkeyup="checkNumber(this);calcRowTotal(this)" placeholder="القيمة">
            </td>

        </tr>`;

        $('#bulletins tbody').append(row);
        $('#bulletins tbody tr:last-child td:eq(1) .form-control').focus();
    }
    function appendRowCollection(){
        var row = `<tr row_id="">
            <td class="table_actions">
                <button type="button" class="btn_remove btn btn-danger" onclick="deleteRow(this)"><i class="fa fa-times"></i></button>
            </td>
            <td class="td_label">
                <input type="text" class="form-control" placeholder="البيان">
            </td>
            <td class="td_amount">
                <input type="text" class="form-control currency" onkeyup="checkNumber(this);calcRowTotal(this)" placeholder="القيمة">
            </td>

        </tr>`;

        $('#bulletins tbody').append(row);
        $('#bulletins tbody tr:last-child td:eq(1) .form-control').focus();
    }
    function deleteRow(btn){
        $(btn).parents('tr').remove();
        calcRowTotal();
    }
    function calcRowTotal(){
        var rows = $('#bulletins tbody tr'),
            total = 0;
        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {
                total += val($(rows[i]).children('.td_amount').children('.form-control').val());
            }
        }
        $('#total_amount').text(nFormat(total));
    }
    function getRows(){
        var rows = $('#bulletins tbody tr'),
            obj = '';


        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {
                var label = $(rows[i]).children('.td_label').children('.form-control').val(),
                    amount = val($(rows[i]).children('.td_amount').children('.form-control').val());

                if(label == ''){
                    alert("يرجى تحديد البيان !");
                    $(rows[i]).children('.td_label').children('.form-control').focus();
                    return false;
                }
                if(amount == 0){
                    alert("يرجى تحديد القيمة !");
                    $(rows[i]).children('.td_amount').children('.form-control').focus();
                    return false;
                }

                obj += label + ';' + amount + '::';
            }
        }
        return obj;
    }
    function getRowsCollections(){
        var rows = $('#bulletins tbody tr'),
            obj = '';

        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {
                var label = $(rows[i]).children('.td_label').children('.form-control').val(),
                    amount = val($(rows[i]).children('.td_amount').children('.form-control').val());

                if(label == ''){
                    alert("يرجى تحديد البيان !");
                    $(rows[i]).children('.td_label').children('.form-control').focus();
                    return false;
                }
                if(amount == 0){
                    alert("يرجى تحديد القيمة !");
                    $(rows[i]).children('.td_amount').children('.form-control').focus();
                    return false;
                }

                obj += label + ';' + amount + '::';
            }
        }
        return obj;
    }
    function saveRows(route, method){
        var token = $('meta[name="csrf-token"]').attr('content'),
            dt = $('#dt').val(),
            total_amount = val($('#total_amount').text());

        if(dt == ''){
            alert("يرجى تحديد التاريخ !");
            $(dt).focus();
            return false;
        }
        if(total_amount == 0){
            alert("يرجى إضافة بيان !");
            return false;
        }
        console.log(route);

        var rows = getRows();
        console.log(rows);
        if(!rows){
            return false;
        }

        var obj = {
            dt: dt,
            total: total_amount,
            rows: rows,
            _token : token,
            _method : method
        };
        console.log(obj);

        startLoader();

        $.ajax({
            url: route,
            type: 'POST',
            dataType: "JSON",
            data: obj,
            success: function (data)
            {
                console.log(data);
                if(data.valid == 1){
                    window.location.href = data.route;
                }
                else {
                    alert(data);
                }
            },
            error: function (data){
                $('body').html(data);
            }

        });

    }
    function saveRowsCollections(route, method){
        var token = $('meta[name="csrf-token"]').attr('content'),
            dt = $('#dt').val(),
            total_amount = val($('#total_amount').text()),
            supplier_id = val($('#supplier_id option:selected').val());

        if(dt == ''){
            alert("يرجى تحديد التاريخ !");
            $(dt).focus();
            return false;
        }

        if(supplier_id == 0){
            alert("يرجى تحديد المورد !");
            $('#supplier_id').focus();
            return false;
        }

        if(total_amount == 0){
            alert("يرجى إضافة بيان !");
            return false;
        }
        console.log(route);

        var rows = getRowsCollections();
        console.log(rows);
        if(!rows){
            return false;
        }

        var obj = {
            dt: dt,
            total: total_amount,
            supplier_id: supplier_id,
            rows: rows,
            _token : token,
            _method : method
        };
        console.log(obj);

        startLoader();

        $.ajax({
            url: route,
            type: 'POST',
            dataType: "JSON",
            data: obj,
            success: function (data)
            {
                console.log(data);
                if(data.valid == 1){
                    window.location.href = data.route;
                }
                else {
                    alert(data);
                }
            },
            error: function (data){
                $('body').html(data);
            }

        });

    }


    function appendBulletin(){

        var row = `<tr row_id="">
            <td class="table_actions">
                <button type="button" class="btn_remove btn btn-danger" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
            </td>
            <td class="td_label">
                <input type="text" class="form-control">
            </td>
            <td class="td_nb">
                <input type="text" class="form-control currency" onkeyup="checkNumber(this);calcCost(this)">
            </td>
            <td class="td_cost">
                <input type="text" class="form-control currency" onkeyup="checkNumber(this);calcCost(this)">
            </td>
            <td class="td_total">
                <input type="text" class="form-control currency" readonly value="0.00">
            </td>
        </tr>`;

        $('#bulletins tbody').append(row);
        $('#bulletins tbody tr:last-child td:eq(1) .form-control').focus();
    }

    function appendBulletinToInvoice(){
        var nb_days = val($('#nb_days').val());

        var row = `<tr row_id="" extra="1" row_nb_days="` + nb_days + `">
            <td class="table_actions">
                <button type="button" class="btn_remove btn btn-danger" onclick="removeInvoiceRow(this)"><i class="fa fa-times"></i></button>
            </td>
            <td class="td_label">
                <input type="text" class="form-control" value="" onkeyup="calcInvoiceRow(this)">
            </td>
            <td class="td_cost">
                <input type="text" class="form-control currency" value="" onkeyup="checkNumber(this);calcInvoiceRow(this)">
            </td>
            <td class="td_nb">
                <input type="text" class="form-control text-center" value="" onkeyup="checkNumber(this);calcInvoiceRow(this)">
            </td>
            <td class="td_nb_days">
                <input type="text" class="form-control text-center" value="` + nb_days + `" onkeyup="checkNumber(this);calcInvoiceRow(this)">
            </td>
            <td class="td_total">
                <input type="text" class="form-control currency" value="0.00" readonly>
            </td>
        </tr>`;

        $('#invoice_bulletins_table tbody').append(row);
        $('#invoice_bulletins_table tbody tr:last-child td:eq(1) .form-control').focus();
    }

    function appendDeduction(){
        var row = `<tr row_id="" type="deduction">
            <td class="table_actions">
                <button type="button" class="btn_remove btn btn-danger" onclick="removeDeductionRow(this)"><i class="fa fa-times"></i></button>
            </td>
            <td class="td_dt">
                <input type="date" class="form-control" value="">
            </td>
            <td class="td_label">
                <input type="text" class="form-control" value="">
            </td>
            <td class="td_debit">
                <input type="text" class="form-control currency" value="" onkeyup="checkNumber(this);calcDeductionsTotal()">
            <td class="td_credit">
                <input type="text" class="form-control currency" value="" onkeyup="checkNumber(this);calcDeductionsTotal()" readonly>
            </td>
            </tr>`;

        $('#deductions_table tbody').append(row);
        $('#deductions_table tbody tr:last-child td:eq(1) .form-control').focus();
    }
    function appendAdvance(){
        var row = `<tr row_id="" type="advance">
            <td class="table_actions">
                <button type="button" class="btn_remove btn btn-danger" onclick="removeAdvanceRow(this)"><i class="fa fa-times"></i></button>
            </td>
            <td class="td_dt">
                <input type="date" class="form-control" value="">
            </td>
            <td class="td_label">
                <input type="text" class="form-control" value="">
            </td>
            <td class="td_debit">
                <input type="text" class="form-control currency" value="" onkeyup="checkNumber(this);calcAdvancesTotal()">
            <td class="td_credit">
                <input type="text" class="form-control currency" value="" onkeyup="checkNumber(this);calcAdvancesTotal()" readonly>
            </td>
            </tr>`;

        $('#advances_table tbody').append(row);
        $('#advances_table tbody tr:last-child td:eq(1) .form-control').focus();
    }

    function appendBulletinToPrice_offer(){
        var nb_days = 30;
        var row = `<tr row_id="">
            <td class="table_actions">
                <button type="button" class="btn_remove btn btn-danger" onclick="removePrice_offerRow(this)"><i class="fa fa-times"></i></button>
            </td>
            <td class="td_label">
                <input type="text" class="form-control">
            </td>
            <td class="td_nb_hours">
                <input type="text" class="form-control">
            </td>
            <td class="td_cost">
                <input type="text" class="form-control currency" value="" onkeyup="checkNumber(this);calcPrice_offerRow(this)">
            </td>
            <td class="td_nb">
                <input type="text" class="form-control text-center" value="" onkeyup="checkNumber(this);calcPrice_offerRow(this)">
            </td>
            <td class="td_total">
                <input type="text" class="form-control currency" value="0.00" readonly>
            </td>
        </tr>`;

        $('#price_offer_bulletins_table tbody').append(row);
        $('#price_offer_bulletins_table tbody tr:last-child td:eq(1) .form-control').focus();
    }


    function calcCost(input) {
        var tr = $(input).parents('tr');
        var nb = $(tr).children('.td_nb').children('.form-control').val(),
            cost = $(tr).children('.td_cost').children('.form-control').val(),
            amount = 0;

        var reg_nb = /^([0-9.-]+)$/;
        var reg_cost = /^([0-9.-]+)$/;

        if (nb != '' && !reg_nb.test(nb)) {
            alert("يرجى كتابة أرقام عربية (0-9) !");
            $(tr).children('.td_nb').children('.form-control').focus().val('');
            return false;
        }
        if (cost != '' && !reg_cost.test(cost)) {
            alert("يرجى كتابة أرقام عربية (0-9) !");
            $(tr).children('.td_cost').children('.form-control').val('');
            return false;
        }
        nb = val(nb);
        cost = val(cost);

        if(nb > 0 && cost > 0) {
            amount = nb * cost;
            setBulletinsRows();
        }

        $(tr).children('.td_total').children('.form-control').val(nFormat(amount));
    }

    function validateNumber(input){
        var reg_number = /^([0-9.-]+)$/;

        if ($(input).val() != '' && !reg_number.test($(input).val())) {
            alert("يرجى كتابة أرقام عربية (0-9) !");
            $(input).focus().val('');
            return false;
        }
    }
    function nFormat(number) {
        number = parseFloat(number).toFixed(2);
        return (""+number).replace(/\B(?=(?:\d{3})+(?!\d))/g," ");
    }
    function val(number) {
        return number.replace(/ /g,'') * 1;
    }


    function setBulletinsRows(){
        var rows = $('#bulletins tbody tr'),
        obj = '',
        total_amount = 0;

        if(rows.length > 0) {

            for(var i = 0; i < rows.length; i++) {
                var label = $(rows[i]).children('.td_label').children('.form-control').val(),
                    nb = $(rows[i]).children('.td_nb').children('.form-control').val(),
                    cost = $(rows[i]).children('.td_cost').children('.form-control').val();

                obj += label + ';' + nb + ';' + cost + '::';
                total_amount += nb * cost;
            }
        }
        $('#total_amount').text(nFormat(total_amount));
        $('#contract_total').val(total_amount);

        $('#bulletins_area').html(obj);
    }

    function getInvoiceBulletinsRows(){
        var rows = $('#invoice_bulletins_table tbody tr'),
        obj = '';

        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {
                var label = $(rows[i]).children('.td_label').children('.form-control').val(),
                    nb = $(rows[i]).children('.td_nb').children('.form-control').val(),
                    cost = $(rows[i]).children('.td_cost').children('.form-control').val(),
                    nb_days = $(rows[i]).children('.td_nb_days').children('.form-control').val(),
                    row_nb_days = $(rows[i]).attr('row_nb_days'),
                    extra = $(rows[i]).attr('extra');

                if(label == ''){
                    alert("يرجى تحديد اسم البيان !");
                    $(rows[i]).children('.td_label').children('.form-control').focus();
                    return false;
                }
                if(val(nb) == 0){
                    alert("يرجى تحديد العدد !");
                    $(rows[i]).children('.td_nb').children('.form-control').focus();
                    return false;
                }
                if(val(cost) == 0){
                    alert("يرجى تحديد القيمة الشهرية !");
                    $(rows[i]).children('.td_cost').children('.form-control').focus();
                    return false;
                }
                if(val(nb_days) == 0){
                    alert("يرجى تحديد عدد الأيام !");
                    $(rows[i]).children('.td_nb_days').children('.form-control').focus();
                    return false;
                }
                obj += label + ';' + nb + ';' + cost+ ';' + nb_days + ';' + row_nb_days+ ';' + extra + '::';
            }
        }
        return obj;
    }

    function getPrice_offerBulletinsRows(){
        var rows = $('#price_offer_bulletins_table tbody tr'),
        obj = '';


        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {

                var label = $(rows[i]).children('.td_label').children('.form-control').val(),
                    nb_hours = $(rows[i]).children('.td_nb_hours').children('.form-control').val(),
                    nb = $(rows[i]).children('.td_nb').children('.form-control').val(),
                    cost = $(rows[i]).children('.td_cost').children('.form-control').val();

                if(label == ''){
                    alert("يرجى تحديد اسم البيان !");
                    $(rows[i]).children('.td_label').children('.form-control').focus();
                    return false;
                }
                if(nb_hours == ''){
                    alert("يرجى تحديد ساعات العمل !");
                    $(rows[i]).children('.td_nb_hours').children('.form-control').focus();
                    return false;
                }
                if(val(nb) == 0){
                    alert("يرجى تحديد العدد !");
                    $(rows[i]).children('.td_nb').children('.form-control').focus();
                    return false;
                }
                if(val(cost) == 0){
                    alert("يرجى تحديد القيمة الشهرية !");
                    $(rows[i]).children('.td_cost').children('.form-control').focus();
                    return false;
                }
                obj += label + ';' + nb_hours + ';' + nb + ';' + cost + '::';
            }
        }
        return obj;
    }


    function getDeductionsAdvancesRows(type){
        var rows = $('#' + type + 's_table tbody tr'),
        obj = '';

        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {

                var dt = $(rows[i]).children('.td_dt').children('.form-control').val(),
                    label = $(rows[i]).children('.td_label').children('.form-control').val(),
                    debit = $(rows[i]).children('.td_debit').children('.form-control').val(),
                    credit = $(rows[i]).children('.td_credit').children('.form-control').val();

                if(dt == ''){
                    alert("يرجى تحديد التاريخ !");
                    $(rows[i]).children('.td_dt').children('.form-control').focus();
                    return false;
                }
                if(label == ''){
                    alert("يرجى تحديد اسم البيان !");
                    $(rows[i]).children('.td_label').children('.form-control').focus();
                    return false;
                }
                if(val(debit) == 0){
                    alert("يرجى تحديد الرصيد المدين !");
                    $(rows[i]).children('.td_debit').children('.form-control').focus();
                    return false;
                }
                if(val(debit) == 0){
                    alert("يرجى تحديد الرصيد الدائن !");
                    $(rows[i]).children('.td_credit').children('.form-control').focus();
                    return false;
                }

                obj += dt + ';' + label + ';' + debit + ';' + credit + '::';
            }
        }
        return obj;
    }

    function calcInvoiceRow(input){
        var row = $(input).parents('tr'),
            nb = val($(row).children('.td_nb').children('.form-control').val()),
            cost = val($(row).children('.td_cost').children('.form-control').val()),
            nb_days = val($(row).children('.td_nb_days').children('.form-control').val()),
            realMonthDays = val($('#nb_days').val()),
            monthDays = val($('#month_days').val()),
            total = 0;

            if($(row).attr('extra') == 1){
                if(nb_days < monthDays || nb_days > monthDays) {
                    realMonthDays = 30;
                } else {
                    realMonthDays = monthDays;
                }
                $(input).parent('td').parent('tr').attr('row_nb_days', realMonthDays);
            }

            if(realMonthDays > 0 && nb > 0 && cost > 0 && nb_days > 0) {
                total = (cost * nb / realMonthDays) * nb_days;
            }

        $(row).children('.td_total').children('.form-control').val(nFormat(total));

        calcInvoiceTotal();
    }

    function calcPrice_offerRow(input){
        var row = $(input).parents('tr'),
            nb = val($(row).children('.td_nb').children('.form-control').val()),
            cost = val($(row).children('.td_cost').children('.form-control').val()),
            total = cost * nb;


        $(row).children('.td_total').children('.form-control').val(nFormat(total));

        calcPrice_offerTotal();
    }

    function calcInvoiceTotal(){
        var rows = $('#invoice_bulletins_table tbody tr'),
        discount_val = val($('#discount_value').val()),
        total_ht = 0,
        vat_percent = val($('#vat').val()),
        total_vat = 0,
        total_ttc = 0;

        if(rows.length > 0) {

            for(var i = 0; i < rows.length; i++) {
                total_ht += val($(rows[i]).children('.td_total').children('.form-control').val());
            }
        }
        total_ht -= discount_val;
        total_vat = total_ht * (vat_percent / 100);
        total_ttc = total_ht + total_vat;

        $('#th_total_ht .form-control').val(nFormat(total_ht));
        $('#th_total_vat .form-control').val(nFormat(total_vat));
        $('#th_total_ttc .form-control').val(nFormat(total_ttc));

    }


    function calcDeductionsTotal(){
        var rows = $('#deductions_table tbody tr'),
        total_debit = 0,
        total_credit = 0,
        total_rest = 0;
        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {
                total_debit += val($(rows[i]).children('.td_debit').children('.form-control').val());
                total_credit += val($(rows[i]).children('.td_credit').children('.form-control').val());
            }
        }
        total_rest = total_debit - total_credit;
        $('#deduction_total_debit').text(nFormat(total_debit));
        $('#deduction_total_credit').text(nFormat(total_credit));
        $('#deduction_total_rest').text(nFormat(total_rest));
    }
    function calcAdvancesTotal(){
        var rows = $('#advances_table tbody tr'),
        total_debit = 0,
        total_credit = 0,
        total_rest = 0;
        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {
                total_debit += val($(rows[i]).children('.td_debit').children('.form-control').val());
                total_credit += val($(rows[i]).children('.td_credit').children('.form-control').val());
            }
        }
        total_rest = total_debit - total_credit;
        $('#advance_total_debit').text(nFormat(total_debit));
        $('#advance_total_credit').text(nFormat(total_credit));
        $('#advance_total_rest').text(nFormat(total_rest));
    }

    function calcPrice_offerTotal(){
        var rows = $('#price_offer_bulletins_table tbody tr'),
        total = 0;

        if(rows.length > 0) {
            for(var i = 0; i < rows.length; i++) {
                total += val($(rows[i]).children('.td_total').children('.form-control').val());
            }
        }
        $('#price_offer_total').val(nFormat(total));
    }

    function syncVat(vat){
        if(val($(vat).val()) > 0){
            $('#vat_due_dt').attr('disabled', null);
        } else {
            $('#vat_due_dt').val('').attr('disabled', 'disabled');
        }
        $('#vat_percent').text(val($(vat).val()));
        calcInvoiceTotal();
    }
    function syncDiscountValue(discount){
        $('#th_discount_value .form-control').val(nFormat(val($(discount).val())));
        calcInvoiceTotal();

    }
    function syncDiscountSubject(discount){
        $('#th_discount_subject').text($(discount).val());
    }

    function removeRow(btn){
        $(btn).parents('tr').remove();
        setBulletinsRows();
    }

    function removeInvoiceRow(btn){
        $(btn).parents('tr').remove();
        calcInvoiceTotal();
    }
    function removePrice_offerRow(btn){
        $(btn).parents('tr').remove();
        calcPrice_offerTotal();
    }
    function removeSalary(btn){
        $(btn).parents('tr').remove();
    }



    function showBulletions(contract_id, route){
        var id = $(this).data("id");
        //var token = $(this).data("token");
        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: route,
            type: 'GET',
            dataType: "html",
            data: {
                "contract_id": id,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data)
            {
                $('#modal_bulletions').modal('show');
                $('#modal_bulletions .modal-body').html(data);
            },
            error: function (data){
                alert(data);
            }

        });

    }
    /*
    function loadDeductions(employee_id, route){
        var token = $('meta[name="csrf-token"]').attr('content');
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "html",
            data: {
                "customer_id": employee_id,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data)
            {
                stopLoader();
                $('#deductions_table tbody').html(data);
                calcDeductionsTotal();
            },
            error: function (data){
                //alert(data);
            }

        });

    }
    function loadAdvances(employee_id, route){
        var token = $('meta[name="csrf-token"]').attr('content');
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "html",
            data: {
                "customer_id": employee_id,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data)
            {
                stopLoader();
                $('#advances_table tbody').html(data);
                calcAdvancesTotal();
            },
            error: function (data){
                //alert(data);
            }

        });

    }
    */
function loadContracts(customer_id, route){
    var token = $('meta[name="csrf-token"]').attr('content');
    startLoader();
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "customer_id": customer_id,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            $('#contracts_table tbody').html(data);
            $('#contract_code').val('');
            setInvoiceInfos();
            $('.row_invoice_infos_bulletins').slideUp();
            $('#invoice_bulletins_table tbody').html('');
            calcInvoiceTotal();
        },
        error: function (data){
            //alert(data);
        }

    });

}
function loadContractsBySeller(seller_id, route){
    var token = $('meta[name="csrf-token"]').attr('content');
    startLoader();
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "seller_id": seller_id,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            $('#contracts_table tbody').html(data);
            $('#contract_code').val('');
            //$('.row_seller_payment_operation_wrap').slideUp();
            //calcInvoiceTotal();
        },
        error: function (data){
            //alert(data);
        }

    });

}
function loadContractsBySupplier(supplier_id, route){
    var token = $('meta[name="csrf-token"]').attr('content');
    startLoader();
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "supplier_id": supplier_id,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            $('#contracts_table tbody').html(data);
            $('#contract_code').val('');
            //$('.row_seller_payment_operation_wrap').slideUp();
            //calcInvoiceTotal();
        },
        error: function (data){
            //alert(data);
        }

    });

}
function loadCustomerStatistics(customer_id, route){
    var token = $('meta[name="csrf-token"]').attr('content');
    startLoader();
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "customer_id": customer_id,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            var obj = JSON.parse(data);
            console.log(obj);
            $('#customer_invoices_count').text(obj.customer_invoices_count);
            $('#customer_invoices_total').text(obj.customer_invoices_total);
            $('#last_invoice_amount').text(obj.last_invoice_amount);
            $('#last_invoice_month_ar').text(obj.last_invoice_month_ar);
            $('#last_invoice_dt').text(obj.last_invoice_dt);
            $('#payments_total').text(obj.payments_total);
            $('#last_payment_total').text(obj.last_payment_total);
            $('#last_payment_dt').text(obj.last_payment_dt);
            $('#customer_rest_balance').text(obj.customer_rest_balance);

        },
        error: function (data){
            //alert(data);
        }

    });

}

function setAllCost(input) {
    var all = $(input).is(":checked");
    if(all){
        $('#month_id').val('');
        $('#year').val('');
    }
}
function resetAllCost(input) {
    if($(input).val() != ''){
        $("#cost_all").prop('checked', false);
    }
}
function loadCustomerCost(owner){
    var route = $('#route').val(),
        customer_id = $('#customer_id').val(),
        month = $('#month_id').val(),
        year = $('#year').val(),
        customer_name = $('#customer_name').val(),
        all = $('#cost_all').is(":checked")? 1: 0;

    if(owner == 1) {
        month = null;
        year = null;
        all = 1;
    }
    console.log(route);
    console.log(customer_id);
    console.log(month);
    console.log(year);
    console.log(all);
    if(!route || !customer_id){
        alert("يرجى اختيار العميل !");
        return false;
    }
    if(all){
        $('#month_id').val('');
        $('#year').val('');
    }

    var token = $('meta[name="csrf-token"]').attr('content');
    startLoader();
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "customer_id": customer_id,
            "customer_name": customer_name,
            "month": month,
            "year": year,
            "all": all,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            var obj = JSON.parse(data);
            console.log(obj);
            $('#suppliers_payments_total').text(nFormat(obj.suppliers_payments_total));
            $('#paies_transfered_total').text(nFormat(obj.paies_transfered_total));
            $('#customers_expenses_total').text(nFormat(obj.customers_expenses_total));
            $('#sellers_payments_total').text(nFormat(obj.sellers_payments_total));
            $('#customers_payments_total').text(nFormat(obj.customers_payments_total));
            $('#payed_taxes_total').text(nFormat(obj.payed_taxes_total));
            $('#net_total').text(nFormat(obj.net_total));


        },
        error: function (data){
            //alert(data);
        }

    });

}
function loadContractsByCustomer(customer_id, route){
    var token = $('meta[name="csrf-token"]').attr('content');
    startLoader();
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "customer_id": customer_id,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            $('#contracts_table tbody').html(data);
            $('#contract_id').val('');
        },
        error: function (data){
            //alert(data);
        }

    });

}
function loadContractsByCustomerForPaie(customer_id, route, row_id){
    var token = $('meta[name="csrf-token"]').attr('content');
    startLoader();
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "customer_id": customer_id,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            $('.paie_table tr[row_id="' + row_id + '"] .td_work_zone .contracts_table').html(data);
        },
        error: function (data){
            //alert(data);
        }

    });

}
function selectContract(contract, route){
    console.log(contract);
    if(contract) {
        var token = $('meta[name="csrf-token"]').attr('content'),
        nb_days = val($('#nb_days').val());
        route = route + '/' + contract.id;
        console.log(route);
        startLoader();
        $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "contract_id": contract.id,
            "nb_days": nb_days,
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            stopLoader();
            $('#invoice_bulletins_table tbody').html(data);
            $('#contract_id').val(contract.id);
            $('#contract_code').val(contract.code);
            setInvoiceInfos();
            setTableDays($('#nb_days').val());
            $('.row_invoice_infos_bulletins').slideDown();
        },
        error: function (data){
            alert(data);
        }

    });
    }

}
function selectContractForPayment(contract){
    console.log(contract);
    if(contract) {
        $('#contract_id').val(contract.id)
        $('#seller_id').val(contract.seller_id)
        $('.row_payment_operation_wrap').slideDown();
        //$('#amount').val(contract.seller_commission).focus();
        if(contract.seller_id > 0){
            loadDeductionsContractForPayment(contract);
            loadLastSellerPayment(contract.id);
            $('.seller_pay_wrap').slideDown();
            $('.last_payment_wrap').slideDown();
            $('.supplier_pay_wrap').slideUp();
            $('#contract_obj').val(JSON.stringify(contract));
        }
        else {
            $('#month_id').val('');
            $('#amount').val('');
            $('#deduction').val('');
            $('#advance').val('');
            $('.seller_pay_wrap').slideUp();
            $('.last_payment_wrap').slideUp();
            $('.supplier_pay_wrap').slideUp();
            $('#contract_obj').val('');
        }
    }

}
function selectContractForCustomerExpense(contract){
    if(contract) {
        $('#contract_id').val(contract.id)
        $('#supplier_id').val(contract.supplier_id)
        $('.row_payment_operation_wrap').slideDown();

        $('#month_id').val('');
        $('.supplier_pay_wrap').slideUp();
        $('#contract_obj').val('');
        $('#amount').val('');
        $('#credit').val('');

    }

}
function selectContractForEmployee(contract){
    if(contract) {
        $('#contract_id').val(contract.id)
        $('#city').val(contract.city);

    }

}
function selectContractForPaie(btn, contract){
    if(contract) {
       $(btn).parents('tr').children('.td_city').children('span').text(contract.city);
       $(btn).parents('tr').children('.td_city').children('input').val(contract.city);

    }

}
function selectContractForSupplierPayment(contract){
    console.log(contract);
    $('.last_payment_wrap').slideUp();
    $('.seller_pay_wrap').slideUp();

    if(contract) {
        $('#contract_id').val(contract.id)
        $('#supplier_id').val(contract.supplier_id)
        $('.row_payment_operation_wrap').slideDown();
        if(contract.supplier_id > 0){
            $('.supplier_pay_wrap').slideDown();
            $('#contract_obj').val(JSON.stringify(contract));
            var supplier_amount = contract.contract_total - contract.supplier_commission;
            console.log(supplier_amount);
            $('#supplier_amount').val(supplier_amount);
            $('#credit').focus();
            //$('#credit').val(contract.total_contract);
        }
        else {
            $('#month_id').val('');
            $('.supplier_pay_wrap').slideUp();
            $('#contract_obj').val('');
            $('#amount').val('');
            $('#credit').val('');
        }
    }

}
function selectContractForSellerPayment(contract){
    console.log(contract);
    if(contract) {
        var token = $('meta[name="csrf-token"]').attr('content'),
            url = $('#da_totals_route').val();

        url += '/' + contract.seller_id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: "text",
            data: {
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                console.log(data);
                var da = JSON.parse(data);
                console.log(data);

                $('#total_advances').val(nFormat(da.total_advances));
                $('#total_deductions').val(nFormat(da.total_deductions));
                $('#contract_id').val(contract.id)
                $('.row_seller_payment_operation_wrap').slideDown();
                $('#amount').val(contract.seller_commission).focus();
                $('#contract_obj').val(JSON.stringify(contract));
                calcSellerContractAmountNet();
            },
            error: function (data) {
                alert(data);
            }

        });
    }

}

function loadDeductionsContractForPayment(contract){
    console.log(contract);
    if(contract) {
        var token = $('meta[name="csrf-token"]').attr('content'),
            url = $('#da_totals_route').val();

        url += '/' + contract.seller_id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: "text",
            data: {
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                var da = JSON.parse(data);
                $('#total_advances').val(nFormat(da.total_advances));
                $('#total_deductions').val(nFormat(da.total_deductions));
                $('#amount').val(contract.seller_commission).focus();
                calcSellerContractAmountNet();
            },
            error: function (data) {
                alert(data);
            }

        });
    }

}
function loadLastSellerPayment(contract_id){
    if(contract_id) {
        var token = $('meta[name="csrf-token"]').attr('content'),
            url = $('#seller_last_payment_route_route').val();

        url += '/' +contract_id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: "text",
            data: {
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                $('#last_payment_table tbody').html(data);
            },
            error: function (data) {
                alert(data);
            }

        });
    }

}

function modalCustomers(route){
    var token = $('meta[name="csrf-token"]').attr('content');

    console.log(route);
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            $('#modal_customers').modal('show');
            $('#modal_customers .modal-body').html(data);
        },
        error: function (data){
            alert(data);
        }

    });

}
function modalEmployees(route, source) {
    if (source == 'paie_list') {
        var paie_dt = $('#dt_paie').val();
        if (!paie_dt) {
            alert("يرجى تحديد تاريخ المسير !");
            $('#dt_paie').focus();
            return false;
        }
    }

    var token = $('meta[name="csrf-token"]').attr('content');

    console.log(route);
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            $('#modal_employees').modal('show');
            $('#modal_employees .modal-body').html(data);
        },
        error: function (data){
            alert(data);
        }

    });

}
function modalSellers(route, source) {
    var token = $('meta[name="csrf-token"]').attr('content');

    console.log(route);
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            $('#modal_sellers').modal('show');
            $('#modal_sellers .modal-body').html(data);
        },
        error: function (data){
            alert(data);
        }

    });

}
function modalSuppliers(route, source) {
    var token = $('meta[name="csrf-token"]').attr('content');

    console.log(route);
    $.ajax({
        url: route,
        type: 'GET',
        dataType: "html",
        data: {
            "_method": 'GET',
            "_token": token,
        },
        success: function (data)
        {
            $('#modal_suppliers').modal('show');
            $('#modal_suppliers .modal-body').html(data);
        },
        error: function (data){
            alert(data);
        }

    });

}

function selectEmployee(employee, route) {
    window.location.href = route;
    /*if (employee) {
        $('#employee_id').val(employee.id);
        $('#employee_name').val(employee.first_name + ' ' + employee.last_name);

        loadDeductions(employee.id, route);
        route = route.replace("deductions_by_employee", "advances_by_employee");
        loadAdvances(employee.id, route);
        $('#modal_employees').modal('hide');
        $('.deductions_advances_wrap').slideDown();
    }
    */
}

function selectCustomer(customer, route) {
    if (customer) {
        $('#customer_name_ar').val(customer.name_ar);
        $('#customer_id').val(customer.id);
        $('#customer_code').val(customer.code);
        $('#customer_vat').val(customer.vat);
        loadContracts(customer.id, route);
        $('#modal_customers').modal('hide');
    }
}
function selectCustomerHome(customer, route) {
    console.log(customer);
    if (customer) {
        $('#customer_name').val(customer.name_ar);
        $('#customer_id').val(customer.id);
        loadCustomerStatistics(customer.id, route);
        $('#modal_customers').modal('hide');
    }
}
function selectCustomerCost(customer, route) {
    console.log(customer);
    if (customer) {
        $('#customer_name').val(customer.name_ar);
        $('#customer_id').val(customer.id);
        $('#route').val(route);
        $('#modal_customers').modal('hide');
    }
}
function selectCustomerForPayment(customer, route) {
    console.log(customer);
    if (customer) {
        $('#customer_name').val(customer.name_ar);
        $('#customer_id').val(customer.id);
        loadContractsByCustomer(customer.id, route);
        $('#modal_customers').modal('hide');
    }
}
function selectCustomerForExpense(customer, route) {
    console.log(route);
    if (customer) {
        $('#customer_name').val(customer.name_ar);
        $('#customer_id').val(customer.id);
        loadContractsByCustomer(customer.id, route);
        $('#modal_customers').modal('hide');
    }
}
function selectCustomerForEmployee(customer, route) {
    console.log(route);
    if (customer) {
        $('#customer_name').val(customer.name_ar);
        $('#work_zone').val(customer.name_ar);
        $('#customer_id').val(customer.id);
        loadContractsByCustomer(customer.id, route);
        $('#modal_customers').modal('hide');
        $('.edit_employee_customer').slideDown();
        $('.edit_employee_contract').slideDown();

    }
}
function selectCustomerForPaie(customer, route, row_id) {
    console.log(row_id);
    console.log(customer);

    if (customer) {
        $('.paie_table tr[row_id="' + row_id + '"] .td_work_zone span').text(customer.name_ar);
        $('.paie_table tr[row_id="' + row_id + '"] .td_work_zone input').val(customer.name_ar);
        loadContractsByCustomerForPaie(customer.id, route, row_id);
        $('#modal_customers').modal('hide');

    }
}
function selectSeller(seller, route) {
    console.log(seller);
    if (seller) {
        $('#seller_name').val(seller.first_name);
        $('#seller_id').val(seller.id);
        loadContractsBySeller(seller.id, route);
        $('#modal_sellers').modal('hide');
    }
}
function selectSupplier(supplier, route) {
    console.log(supplier);
    if (supplier) {
        $('#supplier_name').val(supplier.supplier_name);
        $('#supplier_id').val(supplier.id);
        loadContractsBySupplier(supplier.id, route);
        $('#modal_suppliers').modal('hide');
    }
}
function selectCustomerForBalance(customer) {
    if (customer) {
        $('#customer_name').val(customer.name_ar);
        $('#customer_id').val(customer.id);
        $('#modal_customers').modal('hide');
    }
    goToBalance();
}

function calcSellerContractAmountNet(){
    var amount = val($('#amount').val()),
        deduction = val($('#deduction').val()),
        advance = val($('#advance').val()),
        amount_net = 0;

    amount_net = amount - (advance + deduction);
    $('#amount_net').val(nFormat(amount_net));


}
function goToBalance(){
    var route_url = $('#route_url').val(),
        customer_id = $('#customer_id').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val();

    var url = route_url;
    if(customer_id != '') {
        url = route_url + '/' + customer_id + '_preview=0_' + dt_from + '_' + dt_to;
    }
    window.location.href = url;
}
function goToCompanyBalance(){
    var route_url = $('#route_url').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val();

    var url = route_url + '/preview=0_' + dt_from + '_' + dt_to;

    window.location.href = url;
}
function goToPurchaseBalance(){
    var route_url = $('#route_url').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val();

    if(dt_from && dt_to){
        var url = route_url + '/preview=0_' + dt_from + '_' + dt_to;
        window.location.href = url;
    }

}

function selectEmployeeForBalance(employee) {
    if (employee) {
        $('#employee_name').val(employee.employee_name);
        $('#employee_id').val(employee.id);
        $('#modal_employees').modal('hide');
    }
    goToEmployeeBalance();
}
function selectSellerForBalance(seller) {
    if (seller) {
        $('#seller_name').val(seller.first_name);
        $('#seller_id').val(seller.id);
        $('#modal_sellers').modal('hide');
    }
    goToSellerBalance();
}
function selectSupplierForBalance(supplier) {
    if (supplier) {
        $('#supplier_name').val(supplier.supplier_name);
        $('#supplier_id').val(supplier.id);
        $('#modal_suppliers').modal('hide');
    }
    goToSupplierBalance();
}

function goToEmployeeBalance(){
    var route_url = $('#route_url').val(),
        employee_id = $('#employee_id').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val();

    var url = route_url;
    if(employee_id != '') {
        url = route_url + '/' + employee_id + '_preview=0_' + dt_from + '_' + dt_to;
    }
    window.location.href = url;
}
function goToSellerBalance(){
    var route_url = $('#route_url').val(),
        seller_id = $('#seller_id').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val();

    var url = route_url;
    if(seller_id != '') {
        url = route_url + '/' + seller_id + '_preview=0_' + dt_from + '_' + dt_to;
    }
    window.location.href = url;
}
function goToSupplierBalance(){
    var route_url = $('#route_url').val(),
        supplier_id = $('#supplier_id').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val();

    var url = route_url;
    if(supplier_id != '') {
        url = route_url + '/' + supplier_id + '_preview=0_' + dt_from + '_' + dt_to;
    }
    window.location.href = url;
}

function goSearchDates(){
    var route_url = $('#route_url').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val();

    if(dt_from != '' && dt_to != '') {
        route_url += '/preview=0_' + dt_from + '_' + dt_to;
        window.location.href = route_url;
    }
}
function goSearchTaxes(){
    var route_url = $('#route_url').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val(),
        period = $('#period option:selected').val();

    if(period != '' || (dt_from != '' && dt_to != '')) {
        route_url += '/preview=0_' + dt_from + '_' + dt_to + '_' + period;
        window.location.href = route_url;
    }
}
function previewSearch(paper_id){
    var url = $('#preview_url').val(),
        doc_type = $('#doc_type').val();

    console.log(doc_type);

    if (doc_type == 'customers_balance') {
        if(val($('#customer_id').val()) == 0){
            alert("يرجى الاختيار اولا !");
            return false;
        }
    }
    if (doc_type == 'sellers_balance') {
        if(val($('#seller_id').val()) == 0){
            alert("يرجى الاختيار اولا !");
            return false;
        }
    }
    if (doc_type == 'suppliers_balance') {
        if(val($('#supplier_id').val()) == 0){
            alert("يرجى الاختيار اولا !");
            return false;
        }
    }
    if (doc_type == 'employees_balance') {
        if(val($('#employee_id').val()) == 0){
            alert("يرجى الاختيار اولا !");
            return false;
        }
    }
    if(!url.includes('preview')){
        url += "/preview=1";
    }
    url = url.replace("preview=0", "preview=1");

    url += '/' + paper_id;

    console.log(url);
    //return ;
    window.open(url, '_blank');

}

function previewPaies(e, btn){
    e.preventDefault();
    var route = $(btn).attr('href'),
        search = $('#dt_search').val();
    if(search){
        route += "/" + search;
    }

    console.log(route);
    window.open(route, '_blank');

}
function resetSearchTaxes(){
    $('#dt_from').val('');
    $('#dt_to').val('');
    $('#period').val( $('#period option:first-child').val());
    goSearchTaxes();
}

function setNbDays(){
    var dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val(),
        month_days = val($('#month_days').val());

    if(dt_from && dt_to) {
       var days = calcNbDays(dt_from, dt_to);
       if(days < month_days || days > month_days) {
           $('#nb_days').val(30);
       } else {
           $('#nb_days').val(month_days);
       }
        $('#nb_days_period').val(days);
        setTableDays(days);
    }
}

function setTableDays(nb_days){
    var rows = $('#invoice_bulletins_table tbody tr[extra=0]');
    console.log("rows: " + rows.length);
    if(rows.length > 0) {
        for(var i = 0; i < rows.length; i++) {
            $(rows[i]).attr('row_nb_days', nb_days);
            $(rows[i]).children('.td_nb_days').children('.form-control').val(nb_days);
            calcInvoiceRow($(rows[i]).children('.td_nb_days').children('.form-control'));
        }
        calcInvoiceTotal();
    }
}
function setPaiesDays(nb_days){
    var rows = $('.paie_table tbody tr');
    if(rows.length > 0) {
        for(var i = 0; i < rows.length; i++) {
            $(rows[i]).children('.td_nb_days').children('.form-control').val(nb_days);
            calcSalary(rows[i]);

        }
    }
}

function calcNbDays(dt_from, dt_to){
    const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
    dt_from = new Date(dt_from);
    dt_to = new Date(dt_to);
    dt_to.setDate(dt_to.getDate() + 1);
    return  Math.round(Math.abs((dt_from - dt_to) / oneDay));
}
function setPaymentNumber(){
    var pay_dt = $('#dt').val(),
        doc_id = $('#doc_id').val();

    if(pay_dt != '') {
        var dt = new Date(pay_dt),
            month_id,
            year_id,
            pay_number;

        month_id = dt.getMonth();
        year_id = dt.getFullYear();

        var str = year_id + '';
        year_id = str.substr(str.length - 2);
        month_id += 1;
        month_id = (month_id < 10)? '0' + month_id : month_id;

        pay_number = year_id + month_id + doc_id;

        $('#number').val(pay_number);
    }
    else {
        $('#number').val(doc_id);

    }

}

function setInvoiceInfos(){
    var customer_code = $('#customer_code').val(),
        contract_code = $('#contract_code').val(),
        invoice_code = $('#invoice_code').val(),
        invoice_dt = $('#dt').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val(),
        realMonthDays = 30;
    if(
        customer_code !='' &&
        contract_code != '' &&
        //invoice_code != '' &&
        invoice_dt != ''
    ) {

        var dt = new Date(invoice_dt),
            month_id,
            year_id,
            month_name_en,
            month_name_ar,
            invoice_number;

        month_id = dt.getMonth();
        year_id = dt.getFullYear();
        month_name_en = monthNames_en[month_id];
        month_name_ar = monthNames_ar[month_id];
        realMonthDays = monthDays_count[month_id];

        var str = year_id + '';
        year_id = str.substr(str.length - 2);
        month_id += 1;
        month_id = (month_id < 10)? '0' + month_id : month_id;

        invoice_number = year_id + month_id + customer_code + contract_code + invoice_code;
        //invoice_number = year_id + '_' + month_id + '_' + customer_code + '_' + contract_code + '_' + invoice_code;

        /*console.log(invoice_dt);
        console.log(dt);
        console.log(month_id);
        console.log(year_id);
        console.log(month_name_en);
        console.log(month_name_ar);*/

        $('#month_id').val(month_id);
        $('#year_id').val(year_id);
        $('#month_ar').val(month_name_ar);
        $('#month_en').val(month_name_en);
        $('#invoice_number').val(invoice_number);
        $('#month_days').val(realMonthDays);

        //if(dt_from == '' && dt_to == '') {
            $('#nb_days').val(realMonthDays);
        //}
        setTableDays(realMonthDays);
    } else {
        $('#month_id').val('');
        $('#year_id').val('');
        $('#month_ar').val('');
        $('#month_en').val('');
        $('#invoice_number').val('');
        $('#nb_days').val(realMonthDays);
    }
}

function new_invoice(){
    var token = $('meta[name="csrf-token"]').attr('content'),
        customer_id = $('#customer_id').val(),
        contract_id = $('#contract_id').val(),
        invoice_dt = $('#dt').val(),
        url = $('#route_url').val();

    if(
        customer_id != '' &&
        contract_id != '' &&
        invoice_dt != ''
    ) {
        url += '/' + customer_id + '/' + contract_id + '/' + invoice_dt;

        startLoader();
        $.ajax({
            url: url,
            type: 'GET',
            dataType: "text",
            data: {
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                stopLoader();
                console.log(data);
                $('#invoice_code').val(data);
                setInvoiceInfos();
                if(data > 1){
                    alert("أنت بصدد إنشاء فاتورة أخرى في نفس الشهر للعميل " + $('#customer_name_ar').val() + " !");
                    $('#invoice_code').addClass('alert-danger');
                } else {
                    $('#invoice_code').removeClass('alert-danger');
                }
            },
            error: function (data) {
                alert(data);
            }

        });

    }
}

function saveInvoice(){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $('#default_form').attr('action');

    console.log(route);

    // validation start
    var customer_id = $('#customer_id').val(),
        contract_id = $('#contract_id').val(),
        invoice_id = $('#invoice_id').val(),
        invoice_number = $('#invoice_number').val(),
        invoice_dt = $('#dt').val(),
        dt_from = $('#dt_from').val(),
        dt_to = $('#dt_to').val(),
        nb_days = $('#nb_days').val(),
        vat = $('#vat').val(),
        vat_due_dt = $('#vat_due_dt').val(),
        discount_value = $('#discount_value').val(),
        discount_subject = $('#discount_subject').val(),
        //factor = val($('#factor').val()),
        company_commission = val($('#company_commission').val());

    if(customer_id == '') {
        alert("يرجى اختيار العميل !");
        return false;
    }
    if(contract_id == '') {
        alert("يرجى اختيار العقد !");
        return false;
    }
    if(invoice_number == '') {
        alert("يرجى تحديد رقم الفاتورة !");
        $('#invoice_number').focus();
        return false;
    }
    if(invoice_dt == '') {
        alert("يرجى تحديد تاريخ الفاتورة !");
        $('#invoice_dt').focus();
        return false;
    }
    if(dt_from == '') {
        alert("يرجى تحديد الفترة من تاريخ !");
        $('#dt_from').focus();
        return false;
    }
    if(dt_to == '') {
        alert("يرجى تحديد الفترة إلى تاريخ !");
        $('#dt_to').focus();
        return false;
    }

    if(vat == '') {
        alert("برجى تحديد الضريبة !");
        $('#vat').focus();
        return false;
    }
    if(val(vat) > 0 && vat_due_dt == '') {
        alert("برجى تحديد تاريخ استحقاق الضريبة الضريبة !");
        $('#vat_due_dt').focus();
        return false;
    }
    if(val(discount_value) > 0 && discount_subject == '') {
        alert("يرجى تحديد سبب الخصم !");
        $('#discount_subject').focus();
        return false;
    }

    /*if(factor == 0 && company_commission == 0){
        alert("يرجى تحديد نسبة الشركة !");
        $('#company_commission').focus();
        return false;
    }*/

    var bulletins = getInvoiceBulletinsRows();
    if(!bulletins){
        return false;
    }

    var obj = {};
    $("#default_form").find(":input").each(function() {
        obj[this.name] = $(this).val();
    });


    obj['bulletins'] = bulletins;
    //obj['_method'] = 'POST';
    obj['_token'] = token;

    console.log(obj);

    startLoader();

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
            //return ;
            if(data.valid){
                window.location.href = data.route;
            }
            else {
                alert(data);
                $('body').html(data);
            }
        },
        error: function (data){
            $('body').html(data);
        }

    });

}
function saveCustomerExpense(){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $('#default_form').attr('action');

    console.log(route);

    // validation start
    var customer_id = $('#customer_id').val(),
        contract_id = $('#contract_id').val(),
        dt = $('#dt').val(),
        month_id = $('#month_id').val(),
        credit = val($('#credit').val());

    if(customer_id == '') {
        alert("يرجى اختيار العميل !");
        return false;
    }
    if(month_id == '') {
        alert("يرجى اختيار الشهر !");
        return false;
    }
    if(contract_id == '') {
        alert("يرجى اختيار العقد !");
        return false;
    }
    if(dt == '') {
        alert("يرجى تحديد تاريخ العملية !");
        $('#dt').focus();
        return false;
    }
    if(credit == 0) {
        alert("يرجى تحديد المبلغ المحصل !");
        $('#credit').focus();
        return false;
    }

    var obj = {};
    $("#default_form").find(":input").each(function() {
        obj[this.name] = $(this).val();
    });

    //obj['_method'] = 'POST';
    obj['_token'] = token;

    console.log(obj);
//return ;
    startLoader();

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
            //return ;
            if(data.valid){
                window.location.href = data.route;
            }
            else {
                alert(data);
                $('body').html(data);
            }
        },
        error: function (data){
            $('body').html(data);
        }

    });

}
function savePayment(factor){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $('#default_form').attr('action');

    console.log(route);

    // validation start
    var customer_id = $('#customer_id').val(),
        seller_id = $('#seller_id').val(),
        supplier_id = $('#supplier_id').val(),
        contract_id = $('#contract_id').val(),
        doc_id = $('#doc_id').val(),
        dt = $('#dt').val(),
        credit = val($('#credit').val()),
        month_id = $('#month_id option:selected').val(),
        supplier_month_id = $('#supplier_month_id option:selected').val(),
        deduction = val($('#deduction').val()),
        advance = val($('#advance').val()),
        amount = val($('#amount').val()),
        supplier_amount = val($('#supplier_amount').val()),
        amount_net = val($('#amount_net').val());
    if(customer_id == '') {
        alert("يرجى اختيار العميل !");
        return false;
    }
    if(contract_id == '') {
        alert("يرجى اختيار العقد !");
        return false;
    }
    if(dt == '') {
        alert("يرجى تحديد تاريخ العملية !");
        $('#dt').focus();
        return false;
    }
    if(credit == 0) {
        alert("يرجى تحديد المبلغ المحصل !");
        $('#credit').focus();
        return false;
    }

    if(seller_id > 0 && amount == 0) {
        alert("يرجى تحديد المبلغ المستحق للمسوق !");
        $('#amount').focus();
        return false;
    }
    if(supplier_id > 0 && supplier_amount == 0) {
        alert("يرجى تحديد المبلغ المستحق على شركة الحراسات !");
        $('#supplier_amount').focus();
        return false;
    }

    if(seller_id > 0 && month_id == '') {
        alert("يرجى تحديد الشهر !");
        $('#month_id').focus();
        return false;
    }
    if(supplier_id > 0 && supplier_month_id == '') {
        alert("يرجى تحديد الشهر !");
        $('#supplier_month_id').focus();
        return false;
    }
    var obj = {};
    $("#default_form").find(":input").each(function() {
        obj[this.name] = $(this).val();
    });

    //obj['_method'] = 'POST';
    obj['month_id'] = (supplier_id > 0)? supplier_month_id : month_id;
    obj['_token'] = token;

    console.log(obj);
//return ;
    startLoader();

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
            //return ;
            if(data.valid){
                window.location.href = data.route;
            }
            else {
                alert(data);
                $('body').html(data);
            }
        },
        error: function (data){
            $('body').html(data);
        }

    });

}
function saveSellerPayment(){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $('#default_form').attr('action');

    console.log(route);

    // validation start
    var seller_id = $('#seller_id').val(),
        contract_id = $('#contract_id').val(),
        contract_obj = $('#contract_obj').val(),
        dt = $('#dt').val(),
        month_id = $('#month_id option:selected').val(),
        deduction = $('#deduction').val(),
        advance = $('#advance').val(),
        amount = $('#amount').val(),
        amount_net = $('#amount_net').val();
    ;

    if(seller_id == '') {
        alert("يرجى اختيار المندوب !");
        return false;
    }
    if(contract_id == '') {
        alert("يرجى اختيار العقد !");
        return false;
    }
    if(dt == '') {
        alert("يرجى تحديد تاريخ العملية !");
        $('#dt').focus();
        return false;
    }
    if(month_id == '') {
        alert("يرجى تحديد الشهر !");
        $('#month_id').focus();
        return false;
    }
    if(val(amount) == 0) {
        alert("يرجى تحديد المبلغ !");
        $('#amount').focus();
        return false;
    }
    var obj = {};
    $("#default_form").find(":input").each(function() {
        obj[this.name] = $(this).val();
    });

    //obj['_method'] = 'POST';
    obj['_token'] = token;

    console.log(obj);

    startLoader();

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
            //return ;
            if(data.valid){
                window.location.href = data.route;
            }
            else {
                alert(data);
                $('body').html(data);
            }
        },
        error: function (data){
            $('body').html(data);
        }

    });

}
function saveSupplierPayment (){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $('#default_form').attr('action');

    console.log(route);

    // validation start
    var supplier_id = $('#supplier_id').val(),
        contract_id = $('#contract_id').val(),
        contract_obj = $('#contract_obj').val(),
        dt = $('#dt').val(),
        month_id = $('#month_id option:selected').val(),
        supplier_amount = $('#supplier_amount').val();

    if(supplier_id == '') {
        alert("يرجى اختيار المورد !");
        return false;
    }
    if(contract_id == '') {
        alert("يرجى اختيار العقد !");
        return false;
    }
    if(dt == '') {
        alert("يرجى تحديد تاريخ العملية !");
        $('#dt').focus();
        return false;
    }
    if(month_id == '') {
        alert("يرجى تحديد الشهر !");
        $('#month_id').focus();
        return false;
    }
    if(val(supplier_amount) == 0) {
        alert("يرجى تحديد المبلغ !");
        $('#supplier_amount').focus();
        return false;
    }
    var obj = {};
    $("#default_form").find(":input").each(function() {
        obj[this.name] = $(this).val();
    });

    //obj['_method'] = 'POST';
    obj['_token'] = token;

    console.log(obj);

    startLoader();

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
            //return ;
            if(data.valid){
                window.location.href = data.route;
            }
            else {
                alert(data);
                $('body').html(data);
            }
        },
        error: function (data){
            $('body').html(data);
        }

    });

}

function savePrice_offer(){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $('#default_form').attr('action');

    console.log(route);

    // validation start
    var customer_name = $('#customer_name').val(),
        customer_city = $('#customer_city').val(),
        customer_tel = $('#customer_tel').val(),
        customer_dealer = $('#customer_dealer').val(),
        customer_dealer_mobile = $('#customer_dealer_mobile').val(),
        customer_dealer_email = $('#customer_dealer_email').val(),
        customer_address = $('#customer_address').val();


    if(customer_name == '') {
        alert("يرجى تحديد اسم الشركة !");
        $('#customer_name').focus();
        return false;
    }
    if(customer_city == '') {
        alert("يرجى تحديد المدينة !");
        $('#customer_city').focus();
        return false;
    }
    if(customer_dealer == '') {
        alert("يرجى تحديد اسم الموظف المسؤول !");
        $('#customer_dealer').focus();
        return false;
    }
    if(customer_dealer_mobile == '') {
        alert("يرجى تحديد رقم جوال الموظف المسؤول !");
        $('#customer_dealer_mobile').focus();
        return false;
    }
    if(customer_address == '') {
        alert("يرجى تحديد العنوان !");
        $('#customer_address').focus();
        return false;
    }


    var bulletins = getPrice_offerBulletinsRows();
    if(!bulletins){
        alert("يرجى إضافة بيانات !");
        return false;
    }

    var obj = {};
    $("#default_form").find(":input").each(function() {
        obj[this.name] = $(this).val();
    });

    obj['bulletins'] = bulletins;
    //obj['_method'] = 'POST';
    obj['_token'] = token;

    console.log(obj);

    startLoader();

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
            if(data.valid){
                window.location.href = data.route;
            }
            else {
                alert(data);
                $('body').html(data);
            }
        },
        error: function (data){
            $('body').html(data);
        }

    });

}

function saveDeductionsAdvance(route, type){
    var token = $('meta[name="csrf-token"]').attr('content');

    var ad_dt = $('.' + type + '_dt'),
        ad_label = $('.' + type + '_label'),
        ad_debit = $('.' + type + '_debit');

    console.log(route);
    console.log(type);

    // validation start
    var employee_id = $('#employee_id').val();


    if(employee_id == '') {
        alert("يرجى تحديد الموظف !");
        return false;
    }
    if(!$(ad_dt).val()) {
        alert("يرجى تحديد التاريخ !");
        $(ad_dt).focus();
        return false;
    }

    if(!$(ad_label).val()) {
        alert("يرجى تحديد البيان !");
        $(ad_label).focus();
        return false;
    }

    if(!$(ad_debit).val()) {
        alert("يرجى تحديد القيمة !");
        $(ad_debit).focus();
        return false;
    }

    if (confirm("تأكيد ")) {
        startLoader();

        var obj = {
            "employee_id": employee_id,
            "ad_dt": $(ad_dt).val(),
            "ad_label": $(ad_label).val(),
            "ad_debit": $(ad_debit).val(),
            "type": type,
            "_method": 'POST',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function saveSellerDeductionsAdvance(route, type){
    var token = $('meta[name="csrf-token"]').attr('content');

    var ad_dt = $('.' + type + '_dt'),
        ad_label = $('.' + type + '_label'),
        ad_debit = $('.' + type + '_debit');

    console.log(route);
    console.log(type);

    // validation start
    var seller_id = $('#seller_id').val();


    if(seller_id == '') {
        alert("يرجى تحديد المندوب !");
        return false;
    }
    if(!$(ad_dt).val()) {
        alert("يرجى تحديد التاريخ !");
        $(ad_dt).focus();
        return false;
    }

    if(!$(ad_label).val()) {
        alert("يرجى تحديد البيان !");
        $(ad_label).focus();
        return false;
    }

    if(!$(ad_debit).val()) {
        alert("يرجى تحديد القيمة !");
        $(ad_debit).focus();
        return false;
    }

    if (confirm("تأكيد ")) {
        startLoader();

        var obj = {
            "seller_id": seller_id,
            "ad_dt": $(ad_dt).val(),
            "ad_label": $(ad_label).val(),
            "ad_debit": $(ad_debit).val(),
            "type": type,
            "_method": 'POST',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function payInvoice(btn, id, route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(id);
    console.log(route);

    var pay_dt = $(btn).prev('.pay_dt').val(),
        pay_ref = $(btn).prev().prev('.pay_ref').val();

    if(!pay_ref) {
        alert("يرجى تحديد إيصال السداد !");
        $(btn).prev().prev('.pay_ref').focus();
        return false;
    }
    if(!pay_dt) {
        alert("يرجى تحديد تاريخ السداد !");
        $(btn).prev('.pay_dt').focus();
        return false;
    }
    if (confirm("تأكيد السداد؟")) {
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: {
                "id": id,
                "pay_dt": pay_dt,
                "pay_ref": pay_ref,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function payInvoiceTaxes(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    var pay_dt = $('.pay_dt'),
        pay_ref = $('.pay_ref');

    if(!$(pay_ref).val()) {
        alert("يرجى تحديد إيصال السداد !");
        $(pay_ref).focus();
        return false;
    }
    if(!$(pay_dt).val()) {
        alert("يرجى تحديد تاريخ السداد !");
        $(pay_dt).focus();
        return false;
    }
    if (confirm("تأكيد السداد؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            invoices = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                invoices += $(rows[i]).attr('invoice_id') + ';';
            }
        }
        console.log(invoices);
        var obj = {
            "invoices": invoices,
            "pay_dt": $(pay_dt).val(),
            "pay_ref": $(pay_ref).val(),
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function accept_Paies(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    var accept_dt = $('.accept_dt');

    if(!$(accept_dt).val()) {
        alert("يرجى تحديد تاريخ التعميد !");
        $(accept_dt).focus();
        return false;
    }
    if (confirm("تأكيد التعميد؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            salaries = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                salaries += $(rows[i]).attr('salary_id') + ';';
            }
        }
        console.log(salaries);
        var obj = {
            "salaries": salaries,
            "accept_dt": $(accept_dt).val(),
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function deny_Paies(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    var deny_notes = $('.deny_notes').val();

    if (confirm("تأكيد الرفض؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            salaries = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                salaries += $(rows[i]).attr('salary_id') + ';';
            }
        }
        console.log(salaries);
        var obj = {
            "salaries": salaries,
            "deny_notes": deny_notes,
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function transfer_Paies(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    var transfer_dt = $('.transfer_dt'),
        transfer_notes = $('.transfer_notes').val();

    if(!$(transfer_dt).val()) {
        alert("يرجى تحديد تاريخ الصرف !");
        $(transfer_dt).focus();
        return false;
    }
    if (confirm("تأكيد الصرف؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            salaries = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                salaries += $(rows[i]).attr('salary_id') + ';';
            }
        }
        console.log(salaries);
        var obj = {
            "salaries": salaries,
            "transfer_dt": $(transfer_dt).val(),
            "trans_notes": transfer_notes,
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function acceptSellersPayments(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    var accept_dt = $('.accept_dt');

    if(!$(accept_dt).val()) {
        alert("يرجى تحديد تاريخ التعميد !");
        $(accept_dt).focus();
        return false;
    }
    if (confirm("تأكيد التعميد؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            payments = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                payments += $(rows[i]).attr('payment_id') + ';';
            }
        }
        console.log(payments);
        var obj = {
            "payments": payments,
            "accept_dt": $(accept_dt).val(),
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function denySellersPayments(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    var deny_notes = $('.deny_notes').val();

    if (confirm("تأكيد الرفض؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            payments = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                payments += $(rows[i]).attr('payment_id') + ';';
            }
        }
        console.log(payments);
        var obj = {
            "payments": payments,
            "deny_notes": deny_notes,
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function transferSellersPayments(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    var transfer_dt = $('.transfer_dt');

    if(!$(transfer_dt).val()) {
        alert("يرجى تحديد تاريخ الصرف !");
        $(transfer_dt).focus();
        return false;
    }

    if (confirm("تأكيد الصرف؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            payments = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                payments += $(rows[i]).attr('payment_id') + ';';
            }
        }
        console.log(payments);
        var obj = {
            "payments": payments,
            "transfer_dt": $(transfer_dt).val(),
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function deleteSalary(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("تأكيد الحذف؟")) {
        startLoader();
        var obj = {
            "_method": 'DELETE',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function deleteSellerPayment(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("تأكيد الحذف؟")) {
        startLoader();
        var obj = {
            "_method": 'DELETE',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function cancelSellerPaymentTransfer(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("إلغاء صرف المستحق؟")) {
        startLoader();
        var obj = {
            "_method": 'DELETE',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function cancelSellerPaymentAccept(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("إلغاء تعميد المستحق؟")) {
        startLoader();
        var obj = {
            "_method": 'DELETE',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function cancelSalaryTransfer(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("إلغاء صرف الراتب؟")) {
        startLoader();
        var obj = {
            "_method": 'DELETE',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function cancelSalaryAccept(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("إلغاء تعميد الراتب؟")) {
        startLoader();
        var obj = {
            "_method": 'DELETE',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}


function cancelPayInvoiceTaxes(route){
    var token = $('meta[name="csrf-token"]').attr('content');

    if (confirm("تأكيد السداد؟")) {
        startLoader();
        var rows = $('#example tbody tr[sel=1]'),
            invoices = '';
        if(rows.length > 0){
            for (var i = 0; i < rows.length; i++){
                invoices += $(rows[i]).attr('invoice_id') + ';';
            }
        }
        console.log(invoices);
        var obj = {
            "invoices": invoices,
            "_method": 'POST',
            "_token": token,
        };
        console.log(obj);
        $.ajax({
            url: route,
            type: 'POST',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function resetPriceOffer(btn, id, route){
    var token = $('meta[name="csrf-token"]').attr('content');

    if (confirm("تأكيد ؟")) {
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: {
                "id": id,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function acceptPrice_offer(btn, id, route){
    var token = $('meta[name="csrf-token"]').attr('content');

    var accept_dt = $(btn).prev('.form-group').children('.accept_dt').val(),
        model_id = $(btn).parent().children('.price_offer_models_wrap').children('select').val();

    if(!model_id) {
        alert("يرجى تحديد صيغة عرض السعر !");
        $(btn).parent().children('.price_offer_models_wrap').children('select').focus();
        return false;
    }
    if(!accept_dt) {
        alert("يرجى تحديد تاريخ قبول عرض السعر !");
        $(btn).prev('.form-group').children('.accept_dt').focus();
        return false;
    }
    if (confirm("تأكيد القبول ؟")) {
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: {
                "id": id,
                "accept_dt": accept_dt,
                "model_id": model_id,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function denyPrice_offer(btn, id, route){
    var token = $('meta[name="csrf-token"]').attr('content');

    var notes = $(btn).prev('.notes').val();

    if(!notes) {
        alert("يرجى تحديد الملاحظات !");
        $(btn).prev().prev('.notes').focus();
        return false;
    }

    if (confirm("تأكيد الرفض ؟")) {
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: {
                "id": id,
                "notes": notes,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function checkNumber(input){
    var reg_number = /^([0-9.-]+)$/;
    var input_value = $(input).val();
    if (input_value !== '' && !reg_number.test(input_value)) {
        alert("يرجى كتابة أرقام عربية (0-9) !");
        $(input).focus().val('');
    }
}
function checkDeduction(input){
    var deduction = val($(input).val()),
        total_deductions = val($(input).parent().prev().text());
    if(deduction > total_deductions){
        alert('الخصم يجب ألا يتجاوز مجموع الخصم !');
        $(input).val('').focus();
        return false;
    }
}
function checkAdvance(input){
    var advance = val($(input).val()),
        total_advances = val($(input).parent().prev().text());
    if(advance > total_advances){
        alert('السلفة يجب ألا تتجاوز مجموع السلف !');
        $(input).val('').focus();
        return false;
    }
}


function showModalPayTaxes(){
    var rows = $('#example tbody tr[sel=1]');
    if(rows.length > 0){
        $('#modal_pay_taxes').modal('show');
        setTimeout(function (){
            $('.pay_ref').focus();
        }, 500);
    } else {
        alert('يرجى اختيار فاتورة على الأقل !');
    }
}
function showModalAcceptPaies(){
    var rows = $('#example tbody tr[sel=1]');
    if(rows.length > 0){
        $('#modal_accept_paies').modal('show');
        setTimeout(function (){
            $('.accept_dt').focus();
        }, 500);
    } else {
        alert('يرجى اختيار راتب على الأقل !');
    }
}
function showModalDenyPaies(){
    var rows = $('#example tbody tr[sel=1]');
    if(rows.length > 0){
        $('#modal_deny_paies').modal('show');
        setTimeout(function (){
            $('.deny_notes').focus();
        }, 500);
    } else {
        alert('يرجى اختيار راتب على الأقل !');
    }
}
function showModalTransferPaies(){
    var rows = $('#example tbody tr[sel=1]');
    if(rows.length > 0){
        $('#modal_transfer_paies').modal('show');
        setTimeout(function (){
            $('.transfer_dt').focus();
        }, 500);
    } else {
        alert('يرجى اختيار راتب على الأقل !');
    }
}

function showModalAcceptSellersPayments(){
    var rows = $('#example tbody tr[sel=1]');
    if(rows.length > 0){
        $('#modal_accept_sellers_payments').modal('show');
        setTimeout(function (){
            $('.accept_dt').focus();
        }, 500);
    } else {
        alert('يرجى اختيار مستحق على الأقل !');
    }
}
function showModalDenySellersPayments(){
    var rows = $('#example tbody tr[sel=1]');
    if(rows.length > 0){
        $('#modal_deny_sellers_payments').modal('show');
        setTimeout(function (){
            $('.deny_notes').focus();
        }, 500);
    } else {
        alert('يرجى اختيار مستحق على الأقل !');
    }
}
function showModalTransferSellersPayments(){
    var rows = $('#example tbody tr[sel=1]');
    if(rows.length > 0){
        $('#modal_transfer_sellers_payments').modal('show');
        setTimeout(function (){
            $('.transfer_dt').focus();
        }, 500);
    } else {
        alert('يرجى اختيار مستحق على الأقل !');
    }
}

function showModalAddDeductionAdvance(type){
    $('#modal_add_' + type).modal('show');
}

function checkAllInvoices(input){
    var table = $(input).parents('table');
    $(".check_row").prop('checked', $(input).is(":checked"));
    if($(input).is(":checked")) {
        $(".check_row").parent().parent().attr("sel", "1");
    } else {
        $(".check_row").parent().parent().attr("sel", "0");
    }
}

function checkUncheckInvoice(checkedbox) {
    if($(checkedbox).is(":checked")) {
        $(checkedbox).parent().parent().attr("sel", "1");
    } else {
        $(checkedbox).parent().parent().attr("sel", "0");
    }
}

function goTo(location){
    window.location.href = location;
}

function CancelPayInvoice(id, route){
    var token = $('meta[name="csrf-token"]').attr('content');

    if (confirm("تأكيد إلغاء السداد ؟")) {
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: {
                "id": id,
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function removeDeductionAdvanceRow(route){
    var token = $('meta[name="csrf-token"]').attr('content');

    if (confirm("تأكيد الحذف ؟")) {
        startLoader();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: {
                "_method": 'GET',
                "_token": token,
            },
            success: function (data) {
                stopLoader();
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert(data);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}

function showAcceptPriceOfferWrap(btn){
    $(btn).parent().slideUp();
    $(btn).parent().next('.accept_price_offer_wrap').slideDown();
    $(btn).parent().next('.accept_price_offer_wrap').children().children('.accept_dt').focus();
}
function showDenyPriceOfferWrap(btn){
    $(btn).parent().slideUp();
    $(btn).parent().next().next('.deny_price_offer_wrap').slideDown();
    $(btn).parent().next().next('.deny_price_offer_wrap').children('.notes').focus();
}
function resetPriceOfferAcceptDeny(btn){
    $(btn).parent().slideUp();
    $(btn).parents().children('.price_offer_accept_deny_btns_wrap').slideDown();
}


function appendSalary(employee){
    var token = $('meta[name="csrf-token"]').attr('content'),
        url = $('#da_totals_route').val(),
        modal_customers_route = $('#modal_customers_route').val(),
        nb_days = $('#nb_days').val(),
        rows_count = $('.paie_table tbody tr').length,
        last_row = $('.paie_table tbody tr:last-child'),
        row_id = 1;

    if(rows_count > 0) {
        row_id = val($(last_row).attr('row_id')) + 1;
    }

    url += '/' + employee.id;

    modal_customers_route += '/' + row_id

    $.ajax({
        url: url,
        type: 'GET',
        dataType: "text",
        data: {
            "_method": 'GET',
            "_token": token,
        },
        success: function (data) {
            console.log(data);
            var da =  JSON.parse(data);
            console.log(data);
            var salary_net = (employee.salary / nb_days) * nb_days;

            var row = `<tr row_id="` + row_id + `" employee_id="` + employee.id + `">
        <td class="table_actions">
            <button type="button" class="btn_remove btn btn-danger" onclick="removeSalary(this)"><i class="fa fa-times"></i></button>

        </td>
        <td class="td_name">` + employee.employee_name + `</td>
                <td class="td_job">` + employee.job_name + `</td>

                <td class="td_work_zone">
                    <span>` + employee.work_zone + `</span><br>
                    <input type="hidden" class="form-control" value="` + employee.work_zone + `" readonly>
                    <button type="button" class="btn btn-primary btn-sm" onclick="modalCustomers('` + modal_customers_route + `')"><i class="fa fa-search"></i> اختر العميل</button>
                    <ul class="contracts_table"></ul>
                </td>
                 <td class="td_city">
                    <span>` + employee.city + `</span>
                    <input type="hidden" class="form-control" value="` + employee.city + `" readonly>
                </td>
                <td class="td_salary">
                    <input type="text" class="form-control" value="` + employee.salary + `" onkeyup="checkNumber(this);calcSalaryRow(this)">
                </td>
                <td class="td_nb_days">
                    <input type="text" class="form-control" onkeyup="checkNumber(this);calcSalaryRow(this)" value="` + nb_days + `">
                </td>
                <td class="td_total_deductions currency">` + nFormat(da.total_deductions) + `</td>
                <td class="td_deduction">
                    <input type="text" class="form-control" onkeyup="checkNumber(this);checkDeduction(this);calcSalaryRow(this)" value="0">
                </td>
                <td class="td_total_advances currency">` + nFormat(da.total_advances) + `</td>
                <td class="td_advance">
                    <input type="text" class="form-control" onkeyup="checkNumber(this);checkAdvance(this);calcSalaryRow(this)" value="0">
                </td>
                <td class="td_extra">
                    <input type="text" class="form-control" onkeyup="checkNumber(this);calcSalaryRow(this)" value="0">
                </td>
                <td class="td_salary_net currency bold">` + nFormat(salary_net) + `</td>
            </tr>`;

            var rows_count = $('.paie_table tbody tr[employee_id]').length;
            //if(rows_count > 0){
                $('.paie_table tbody').append(row);
            //} else {
             //   $('.paie_table tbody').html(row);
            //}
            $('#modal_employees').modal('hide');
            $('.paie_table tbody tr:last-child td.td_salary .form-control').focus();
        },
        error: function (data) {
            alert(data);
        }

    });


}

function calcSalaryRow(input){
    var row = $(input).parents('tr');
    calcSalary(row);

}
function calcSalary(row){
    var paie_nb_days = $('#nb_days').val();
    if(!paie_nb_days) {
        alert('يرجى تحديد عدد الأيام !');
        return false;
    }
    var salary = val($(row).children('.td_salary').children('.form-control').val()),
        extra = val($(row).children('.td_extra').children('.form-control').val()),
        advance = val($(row).children('.td_advance').children('.form-control').val()),
        deduction = val($(row).children('.td_deduction').children('.form-control').val()),
        nb_days = val($(row).children('.td_nb_days').children('.form-control').val()),
        salary_net = 0;

    salary = (salary / paie_nb_days) * nb_days;
    salary_net = salary - (advance + deduction) + extra;

    $(row).children('.td_salary_net').text(nFormat(salary_net));
}

function savePaies(route, method){
    var token = $('meta[name="csrf-token"]').attr('content');

    console.log(route);

    var paie_dt = $('#dt_paie').val(),
        month_id = $('#month_id').val(),
        nb_days = $('#nb_days').val(),
        month_days = $('#month_days').text();

    if (!paie_dt) {
        alert("يرجى تحديد تاريخ المسير !");
        $('#dt_paie').focus();
        return false;
    }

    var paies = getPaiesRows();
    console.log(paies);
    if(!paies){
        return false;
    }

    var obj = {
        month_id: month_id,
        nb_days: nb_days,
        month_days: month_days,
        paie_dt: paie_dt,
        paies: paies,
        _token : token,
        _method : method
    };
    console.log(obj);

    startLoader();

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
            if(data.valid == 1){
                window.location.href = data.route;
            }
            else {
                alert(data);
            }
        },
        error: function (data){
            $('body').html(data);
        }

    });

}

function getPaiesRows(){
    var rows = $('.paie_table tbody tr'),
        obj = '';


    if(rows.length > 0) {
        for(var i = 0; i < rows.length; i++) {
            console.log($(rows[i]).children('.td_salary').children('.form-control').val());
            var employee_id = $(rows[i]).attr('employee_id'),
                city = $(rows[i]).children('.td_city').children('.form-control').val(),
                work_zone = $(rows[i]).children('.td_work_zone').children('.form-control').val(),
                salary = val($(rows[i]).children('.td_salary').children('.form-control').val()),
                nb_days = val($(rows[i]).children('.td_nb_days').children('.form-control').val()),
                advance = val($(rows[i]).children('.td_advance').children('.form-control').val()),
                deduction = val($(rows[i]).children('.td_deduction').children('.form-control').val()),
                extra = val($(rows[i]).children('.td_extra').children('.form-control').val()),
                salary_net = val($(rows[i]).children('.td_salary_net').text());

            if(val(employee_id) == 0){
                alert("يرجى تحديد الموظف !");
                return false;
            }
            if(city == ''){
                alert("يرجى تحديد المدينة !");
                $(rows[i]).children('.td_city').children('.form-control').focus();
                return false;
            } if(work_zone == ''){
                alert("يرجى تحديد موقع العمل !");
                $(rows[i]).children('.work_zone').children('.form-control').focus();
                return false;
            }
            if(salary == 0){
                alert("يرجى تحديد الراتب !");
                $(rows[i]).children('.td_salary').children('.form-control').focus();
                return false;
            }
            if(nb_days == 0){
                alert("يرجى تحديد الدوام الفعلي !");
                $(rows[i]).children('.td_nb_days').children('.form-control').focus();
                return false;
            }
            obj += employee_id + ';' + city + ';' + work_zone + ';' + salary + ';' + nb_days + ';' + advance + ';' + deduction + ';' + extra + ';' + salary_net + '::';
        }
    }
    return obj;
}

function setPaieMonth(){
    var paie_dt = $('#dt_paie').val(),
        realMonthDays = 30;
    console.log(paie_dt);

    if(paie_dt != '') {
        var dt = new Date(paie_dt),
            month_id,
            month_name_ar;

        month_id = dt.getMonth();
        month_name_ar = monthNames_ar[month_id];
        realMonthDays = monthDays_count[month_id];

        month_id += 1;
        month_id = (month_id < 10)? '0' + month_id : month_id;


        $('#month_id').val(month_id);
        $('#month_ar').text(month_name_ar);

        $('#month_days').text(realMonthDays);
        $('#month_days').parent().show();

        $('#nb_days').val(realMonthDays);
        $('.nb_days_type').slideDown();
    }
}

function setPaieNbDays(nb_days){
    var paie_dt = $('#dt_paie').val();

    if(paie_dt != '') {
        $('#nb_days').val(nb_days);
        setPaiesDays(nb_days);
    } else {
        alert('يرجى تحديد تاريخ المسير !');
    }
}

function setBank_account_name(){
    $('#bank_account_name').val($('#employee_name').val());
}

function toggleBtnPrint(){
    var print_signature = $('#print_signature'),
        print_cachet = $('#print_cachet'),
        print_btns = $('.print_btn'),
        cachet = 0,
        signature = 0;

    if($(print_signature).is(':checked')){
        signature = 1;
    }  else {
        signature = 0;
    }
    if($(print_cachet).is(':checked')){
        cachet = 1;
    }  else {
        cachet = 0;
    }

    for(var i = 0; i < print_btns.length; i++){
        $(print_btns[i]).attr('href', $(print_btns[i]).attr('prefix_href') + '/' + signature + '/' + cachet);
    }

}

function toggleSellerWrap(checkbox){
    if($(checkbox).is(':checked')){
        $('.contract_seller_wrap').slideDown();
    } else {
        $('.contract_seller_wrap').slideUp();
    }
}
function toggleCanLogin(checkbox){
    if($(checkbox).is(':checked')){
        $('.seller_login_wrap').slideDown();
        $('#email').focus();
    } else {
        $('.seller_login_wrap').slideUp();
        $('#email').val('');
        $('#password_visible').val('');

    }
}

function deleteDefault(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("تأكيد الحذف؟")) {
        startLoader();
        var obj = {
            "_method": 'DELETE',
            "_token": token,
        };
        $.ajax({
            url: route,
            type: 'GET',
            dataType: "text",
            data: obj,
            success: function (data) {
                console.log(data);
                stopLoader();
                var response = JSON.parse(data);

                if (response.status == 1) {
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (data) {
                alert(data);
            }

        });
    }
}
function reset(route){
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(route);

    if (confirm("هل تريد تصفير قاعدة البيانات ؟")) {
        if (confirm("متأكد؟ ")) {
            startLoader();
            var obj = {
                "_method": 'DELETE',
                "_token": token,
            };
            $.ajax({
                url: route,
                type: 'GET',
                dataType: "text",
                data: obj,
                success: function (data) {
                    console.log(data);
                    stopLoader();
                    var response = JSON.parse(data);

                    if (response.status == 1) {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (data) {
                    alert(data);
                }

            });
        }
    }
}

function checkFormAddContract(btn){
    var total_amount = val($('#total_amount').text()),
        city = $('#city').val(),
        address = $('#address').val(),
        dt_start = $('#dt_start').val(),
        dt_end = $('#dt_end').val();

    if(total_amount == 0){
        alert("يرجى إضافة بيانات !");
        return false;
    }

    if(city == ''){
        alert("يرجى تحديد المدينة !");
        $('#city').focus();
        return false;
    }
    if(address == ''){
        alert("يرجى تحديد العنوان !");
        $('#address').focus();
        return false;
    }
    if(dt_start == ''){
        alert("يرجى تحديد تاريخ بداية العقد !");
        $('#dt_start').focus();
        return false;
    }
    if(dt_end == ''){
        alert("يرجى تحديد تاريخ نهاية العقد !");
        $('#dt_end').focus();
        return false;
    }

    $(btn).parents('form').submit();

}
/*
function setSearchSession(input){
    var search = $(input).val(),
        route = $('#route_url').val(),
        token = $('meta[name="csrf-token"]').attr('content'),
        obj = {
            search: search,
            _token : token,
            _method : 'POST'
        };
    console.log(obj);

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
        },
        error: function (data){
            console.log(data);
        }

    });

}
*/
function setDtSearchSession(dt_search){
    var route = $('#route_url').val(),
        token = $('meta[name="csrf-token"]').attr('content'),
        obj = {
            dt_search: dt_search,
            _token : token,
            _method : 'POST'
        };
    console.log(obj);

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
        },
        error: function (data){
            console.log(data);
        }

    });

}
function setSearchSession(){
    var columns = $('#example thead th');


    for(var i = 0; i < columns.length -1; i++){
        columns[i]['name'] = $(columns[i]).attr('name');
        columns[i]['value'] = $(columns[i]).children('input').val();

        console.log(columns[i]['name']);
        console.log(columns[i]['value']);
    }
    columns = JSON.stringify(columns);

    var route = $('#route_url').val(),
        token = $('meta[name="csrf-token"]').attr('content'),
        obj = {
            columns: columns,
            _token : token,
            _method : 'POST'
        };
    console.log(obj);

    $.ajax({
        url: route,
        type: 'POST',
        dataType: "JSON",
        data: obj,
        success: function (data)
        {
            console.log(data);
        },
        error: function (data){
            console.log(data);
        }

    });

}
