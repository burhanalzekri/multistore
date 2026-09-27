<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'حقل :attribute يجب أن يكون نصاً.',
    'integer' => 'حقل :attribute يجب أن يكون رقماً صحيحاً.',
    'numeric' => 'حقل :attribute يجب أن يكون رقماً.',
    'image' => 'حقل :attribute يجب أن يكون صورة.',
    'array' => 'حقل :attribute يجب أن يكون مصفوفة.',
    'file' => 'حقل :attribute يجب أن يكون ملفاً.',
    'min' => [
        'numeric' => 'حقل :attribute يجب ألا يقل عن :min.',
        'file' => 'حقل :attribute يجب ألا يقل حجمه عن :min كيلوبايت.',
        'string' => 'حقل :attribute يجب ألا يقل عن :min حرفاً.',
        'array' => 'حقل :attribute يجب أن يحتوي على :min عناصر على الأقل.',
    ],
    'max' => [
        'numeric' => 'حقل :attribute يجب ألا يزيد عن :max.',
        'file' => 'حقل :attribute يجب ألا يزيد حجمه عن :max كيلوبايت.',
        'string' => 'حقل :attribute يجب ألا يزيد عن :max حرفاً.',
        'array' => 'حقل :attribute يجب ألا يحتوي على أكثر من :max عنصر.',
    ],
    'in' => 'القيمة المختارة في :attribute غير صحيحة.',
    'boolean' => 'حقل :attribute يجب أن يكون صحيحاً أو خطأ.',
    'unique' => 'قيمة :attribute مستخدمة مسبقاً.',
    'email' => 'حقل :attribute يجب أن يكون بريداً إلكترونياً صحيحاً.',

    'attributes' => [
        'name' => 'اسم المنتج',
        'price' => 'السعر',
        'stock' => 'المخزون',
        'description' => 'الوصف',
        'category_id' => 'التصنيف',
        'image' => 'الصورة الرئيسية',
        'gallery' => 'صور المعرض',
        'video' => 'الفيديو',
        'compare_price' => 'السعر قبل الخصم',
        'barcode' => 'الباركوود',
        'variants' => 'الخيارات',
        'variants.*.stock' => 'كمية المتغير',
        'variants.*.size' => 'المقاس',
        'variants.*.color' => 'اللون',
    ],
];
