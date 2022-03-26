<?php

return [
    'models' => [
        'home' => 'r',
        'cost' => 'r',
        'privacy' => 'all',
        'company_balance' => 'r',
        'companies' => 'r,u',
        'purchases' => 'c,r,u,d',
        'expenses' => 'c,r,u,d',
        'incomes' => 'c,r,u,d',

        'suppliers' => 'c,r,u,d',
        'collections' => 'c,r,u,d',
        'collections_balance' => 'r',
        'suppliers_payments' => 'c,r,u,d',
        'suppliers_balance' => 'r',

        'sellers' => 'c,r,u,d',
        'sellers_deductions_advances' => 'c,r,u,d',
        'sellers_payments' => 'c,r,u,d',
        'sellers_payments_accept_deny' => 'yes',
        'sellers_payments_accepted' => 'c,r',
        'sellers_payments_transfered' => 'c,r',
        'sellers_balance' => 'r',

        'invoices' => 'c,r,u,d',
        'invoices_taxes' => 'c,r',
        'invoices_taxes_payed' => 'c,r',

        'jobs' => 'c,r,u,d',
        'employees' => 'c,r,u,d',
        'deductions_advances' => 'c,r,u,d',
        'paies' => 'c,r,u,d',
        'paies_accept_deny' => 'yes',
        'paies_accepted' => 'c,r',
        'paies_transfered' => 'c,r',
        'employees_balance' => 'r',
        'cancel_paies' => 'r',

        'users' => 'c,r,u,d',

        'customers' => 'c,r,u,d',
        'payments' => 'c,r,u,d',
        'customers_balance' => 'r',

        'price_offers_models' => 'c,r,u,d',
        'price_offers' => 'c,r,u,d',
        'price_offers_accept_deny' => 'yes',

    ],
    'sections' => [
        'home' => 'الإحصائية',
        'cost' => 'مركز التكلفة',
        'privacy' => 'الخصوصية',
        'company_balance' => 'صافي الارباح والخسائر',
        'companies' => 'بروفايل الشركة',
        'purchases' => 'المشتريات',
        'expenses' => 'المصروفات',
        'incomes' => 'إيرادات إضافية',

        'suppliers' => 'الموردين',
        'collections' => 'التحصيل من الموردين',
        'collections_balance' => 'كشف حساب التحصيل',
        'suppliers_payments' => 'المستحقات على الموردين',
        'suppliers_balance' => 'كشف حساب الموردين',

        'sellers' => 'المسوقين',
        'sellers_deductions_advances' => 'السلف والخصومات',
        'sellers_payments' => 'مستحقات المسوقين',
        'sellers_payments_accept_deny' => 'تعميد أو رفض مستحقات المسوقين',
        'sellers_payments_accepted' => 'صرف مستحقات المسوقين',
        'sellers_payments_transfered' => 'مدفوعات المسوقين',
        'sellers_balance' => 'كشف حساب المسوقين',

        'invoices' => 'الفواتير',
        'invoices_taxes' => 'البيان الضريبي',
        'invoices_taxes_payed' => 'الضرائب المسددة',

        'jobs' => 'الوظائف',
        'employees' => 'الموظفين',
        'deductions_advances' => 'السلف والخصومات',
        'paies' => 'الرواتب',
        'paies_accept_deny' => 'تعميد أو رفض الرواتب',
        'paies_accepted' => 'صرف الرواتب',
        'paies_transfered' => 'الرواتب المدفوعة',
        'employees_balance' => 'كشف حساب الموظفين',
        'cancel_paies' => 'إلغاء صرف الرواتب',

        'users' => 'المستخدمين',

        'customers' => 'العملاء',
        'payments' => 'التحصيل',
        'customers_balance' => 'كشف حساب العملاء',

        'price_offers_models' => ' صيغ عروض السعر',
        'price_offers' => 'عروض السعر',
        'price_offers_accept_deny' => 'قبول أو رفض عرض السعر',



    ]
];

/*
return [
    'users',
    'companies',
    'customers'
];

*/
