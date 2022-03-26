@php
    $factor = \App\Helper\Helper::factor();
@endphp
<aside class="main-sidebar">

    <section class="sidebar">

    <ul class="sidebar-menu" data-widget="tree">
        @if(auth()->user()->seller_id == 0)
            @if(auth()->user()->can('home_read'))
                <li><a href="{{ route('dashboard.statistics') }}"><i class="fa fa-calculator"></i> <span>الإحصائية</span></a></li>
            @endif
        <li><a href="{{ route('dashboard.cost.index') }}"><i class="fa fa-dollar"></i> <span>مركز التكلفة</span></a></li>
        <li class="treeview">
            <a href="#" class="threeview_header">
                <i class="fa fa-home"></i>
                <span>@lang('site.dashboard')</span>
            </a>
            <ul class="treeview-menu" style="display: none;">
                @if(auth()->user()->can('company_balance_read'))
                <li><a href="{{ route('dashboard.company_balance.index') }}"><i class="fa fa-briefcase"></i><span>صافي الارباح والخسائر</span></a></li>
                @endif
                @if(auth()->user()->id == 1)
                <li><a href="{{ route('dashboard.companies.index') }}"><i class="fa fa-university"></i><span>@lang('site.companies.title')</span></a></li>
                @endif
                @if(auth()->user()->can('purchases_read'))
                <li><a href="{{ route('dashboard.purchases.index') }}"><i class="fa fa-shopping-cart"></i><span>المشتريات</span></a></li>
                <li><a href="{{ route('dashboard.purchases_balance.index') }}"><i class="fa fa-bars"></i><span>كشف حساب المشتريات</span></a></li>

                @endif
                @if(auth()->user()->can('expenses_read'))
                <li><a href="{{ route('dashboard.expenses.index') }}"><i class="fa fa-money"></i><span>المصروفات</span></a></li>
                <li><a href="{{ route('dashboard.expenses_balance.index') }}"><i class="fa fa-bars"></i><span>كشف حساب المصروفات</span></a></li>
                @endif
                @if(auth()->user()->can('incomes_read'))
                <li><a href="{{ route('dashboard.incomes.index') }}"><i class="fa fa-plus"></i><span>إيرادات إضافية</span></a></li>
                <li><a href="{{ route('dashboard.incomes_balance.index') }}"><i class="fa fa-bars"></i><span>كشف حساب الإيرادات الإضافية</span></a></li>
                @endif
            </ul>
        </li>
        @if($factor)
        <li class="treeview">
            <a href="#" class="threeview_header">
                <i class="fa fa-th"></i>
                <span>الموردين</span>
            </a>
            <ul class="treeview-menu" style="display: none;">
                @if(auth()->user()->can('suppliers_read'))
                <li><a href="{{ route('dashboard.suppliers.index') }}"><i class="fa fa-truck"></i><span>الموردين</span></a></li>
                @endif
                @if(auth()->user()->can('collections_read'))
                <li><a href="{{ route('dashboard.collections.index') }}"><i class="fa fa-money"></i><span> التحصيل من الموردين</span></a></li>
                <li><a href="{{ route('dashboard.collections_balance.index') }}"><i class="fa fa-bars"></i><span>كشف حساب التحصيل</span></a></li>
                @endif
                @if(auth()->user()->can('suppliers_payments_read'))
                <li><a href="{{ route('dashboard.suppliers_payments.index') }}"><i class="fa fa-money"></i><span>المستحقات على الموردين</span></a></li>
                @endif
                @if(auth()->user()->can('suppliers_balance_read'))
                <li><a href="{{ route('dashboard.suppliers_balance.index') }}"><i class="fa fa-bars"></i><span>كشف حساب الموردين</span></a></li>
                @endif
            </ul>
        </li>
        @else
            <li class="treeview">
                <a href="#" class="threeview_header">
                    <i class="fa fa-th"></i>
                    <span>المسوقين</span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    @if(auth()->user()->can('sellers_read'))
                    <li><a href="{{ route('dashboard.sellers.index') }}"><i class="fa fa-users"></i><span>المسوقين</span></a></li>
                    @endif
                    @if(auth()->user()->can('sellers_deductions_advances_read'))
                    <li><a href="{{ route('dashboard.sellers_deductions_advances.index') }}"><i class="fa fa-money"></i><span>السلف والخصومات</span></a></li>
                        <li><a href="{{ route('dashboard.seller_deductions_advances_balance.index') }}"><i class="fa fa-bars"></i><span>كشف السلف والخصم</span></a></li>
                    @endif


                    @if(auth()->user()->can('sellers_payments_read'))
                    <li><a href="{{ route('dashboard.sellers_payments.index') }}"><i class="fa fa-money"></i><span>مستحقات المسوقين</span></a></li>
                    @endif
                    @if(auth()->user()->can('sellers_payments_accepted_read'))
                    <li><a href="{{ route('dashboard.sellers_payments_accepted.index') }}"><i class="fa fa-credit-card"></i><span>صرف مستحقات المسوقين</span></a></li>
                    @endif
                    @if(auth()->user()->can('sellers_payments_transfered_read'))
                    <li><a href="{{ route('dashboard.sellers_payments_transfered.index') }}"><i class="fa fa-bars"></i><span>مدفوعات المسوقين</span></a></li>
                        <li><a href="{{ route('dashboard.cancel_sellers_payments.index') }}"><i class="fa fa-reply"></i><span> إلغاء صرف المستحقات</span></a></li>
                        @endif
                    @if(auth()->user()->can('sellers_balance_read'))
                    <li><a href="{{ route('dashboard.sellers_balance.index') }}"><i class="fa fa-bars"></i><span>كشف حساب المسوقين</span></a></li>
                    @endif
                </ul>
            </li>
        @endif
        <li class="treeview">
            <a href="#" class="threeview_header">
                <i class="fa fa-handshake-o"></i>
                <span>العملاء</span>
            </a>
            <ul class="treeview-menu" style="display: none;">
                @if(auth()->user()->can('customers_read'))
                    <li><a href="{{ route('dashboard.customers.index') }}"><i class="fa fa-handshake-o"></i><span>العملاء</span></a></li>
                @endif
                @if(auth()->user()->can('payments_read'))
                    <li><a href="{{ route('dashboard.payments.index') }}"><i class="fa fa-money"></i><span> التحصيل</span></a></li>
                @endif
                    <li><a href="{{ route('dashboard.customer_expenses.index') }}"><i class="fa fa-bars"></i><span> مصروفات على العملاء</span></a></li>
                @if(auth()->user()->can('customers_balance_read'))
                    <li><a href="{{ route('dashboard.customers_balance.index') }}"><i class="fa fa-bars"></i><span>كشف حساب العملاء</span></a></li>
                @endif
            </ul>
        </li>

        <li class="treeview">
            <a href="#" class="threeview_header">
                <i class="fa fa-file-o"></i>
                <span>الفواتير</span>
            </a>
            <ul class="treeview-menu" style="display: none;">
                @if(auth()->user()->can('invoices_read'))
                <li><a href="{{ route('dashboard.invoices.index') }}"><i class="fa fa-file-o"></i><span>الفواتير</span></a></li>
                @endif
                @if(auth()->user()->can('invoices_taxes_read'))
                <li><a href="{{ route('dashboard.invoices_taxes.index') }}"><i class="fa fa-money"></i><span>البيان الضريبي</span></a></li>
                @endif
                @if(auth()->user()->can('invoices_taxes_payed_read'))
                <li><a href="{{ route('dashboard.invoices_taxes_payed.index') }}"><i class="fa fa-bars"></i><span>الضرائب المسددة</span></a></li>
                @endif
            </ul>
        </li>

        <li class="treeview">
            <a href="#" class="threeview_header">
                <i class="fa fa-th"></i>
                <span>الموظفين</span>
            </a>
            <ul class="treeview-menu" style="display: none;">
                @if(auth()->user()->can('jobs_read'))
                <li><a href="{{ route('dashboard.jobs.index') }}"><i class="fa fa-suitcase"></i><span>الوظائف</span></a></li>
                @endif
                @if(auth()->user()->can('employees_read'))
                <li><a href="{{ route('dashboard.employees.index') }}"><i class="fa fa-users"></i><span>الموظفين</span></a></li>
                @endif
                @if(auth()->user()->can('deductions_advances_read'))
                <li><a href="{{ route('dashboard.deductions_advances.index') }}"><i class="fa fa-money"></i><span>السلف والخصومات</span></a></li>
                <li><a href="{{ route('dashboard.deductions_advances_balance.index') }}"><i class="fa fa-bars"></i><span>كشف السلف والخصم</span></a></li>
                @endif
                @if(auth()->user()->can('paies_read'))
                <li><a href="{{ route('dashboard.paies.index') }}"><i class="fa fa-credit-card"></i><span> الرواتب</span></a></li>
                @endif
                @if(auth()->user()->can('paies_accepted_read'))
                <li><a href="{{ route('dashboard.paies_accepted.index') }}"><i class="fa fa-credit-card"></i><span> صرف الرواتب</span></a></li>
                @endif
                @if(auth()->user()->can('paies_transfered_read'))
                <li><a href="{{ route('dashboard.paies_transfered.index') }}"><i class="fa fa-credit-card"></i><span> الرواتب المدفوعة</span></a></li>
                <li><a href="{{ route('dashboard.cancel_paies.index') }}"><i class="fa fa-reply"></i><span> إلغاء صرف الرواتب</span></a></li>
                @endif
                @if(auth()->user()->can('employees_balance_read'))
                <li><a href="{{ route('dashboard.employees_balance.index') }}"><i class="fa fa-bars"></i><span> كشف حساب الموظفين</span></a></li>
                @endif
            </ul>
        </li>

        <li class="treeview">
                <a href="#" class="threeview_header">
                    <i class="fa fa-file-o"></i>
                    <span>عروض السعر</span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    @if(auth()->user()->can('price_offers_models_read'))
                        <li><a href="{{ route('dashboard.price_offers_models.index') }}"><i class="fa fa-file"></i><span> صيغ عروض السعر</span></a></li>
                    @endif
                    @if(auth()->user()->can('price_offers_read'))
                        <li><a href="{{ route('dashboard.price_offers.index') }}"><i class="fa fa-file-o"></i><span> عروض السعر</span></a></li>
                    @endif
                </ul>
            </li>
        @else
        @if(auth()->user()->can('price_offers_read'))
            <li><a href="{{ route('dashboard.price_offers.index') }}"><i class="fa fa-file-o"></i><span> عروض السعر</span></a></li>
        @endif

        @endif

        @if(auth()->user()->can('users_read'))
            <li><a href="{{ route('dashboard.users.index') }}"><i class="fa fa-users"></i><span>المستخدمين</span></a></li>
        @endif

    </ul>

    </section>

</aside>

