<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | following language lines contain default error messages used by
    | validator class. Some of these rules have multiple versions such
    | as size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute mora biti sprejet.',
    'active_url' => ':attribute ni veljavna povezava.',
    'after' => ':attribute mora biti kasnejši od :date.',
    'after_or_equal' => ':attribute mora biti kasnejši ali enak :date.',
    'alpha' => ':attribute lahko vsebuje le črke.',
    'alpha_dash' => ':attribute lahko vsebuje le črke, številke, "/" in "_".',
    'alpha_num' => ':attribute lahko vsebuje le črke in številke.',
    'array' => ':attribute mora biti seznam',
    'before' => ':attribute mora biti pred datumom :date.',
    'before_or_equal' => ':attribute mora biti enak ali pred datumom :date.',
    'between' => [
        'numeric' => ':attribute mora biti med :min in :max.',
        'file' => ':attribute mora biti med :min in :max kilobajtov.',
        'string' => ':attribute mora biti med :min in :max znakov.',
        'array' => ':attribute mora imeti med :min in :max elementov.',
    ],
    'boolean' => ':attribute mora biti true or false.',
    'confirmed' => ':attribute potrditev se ne ujema.',
    'date' => ':attribute ni veljaven datum.',
    'date_equals' => ':attribute se mora ujemati z :date.',
    'date_format' => ':attribute ne ustreza formatu :format.',
    'different' => ':attribute in :other morata biti različna.',
    'digits' => ':attribute mora biti dolg :digits cifer .',
    'digits_between' => ':attribute mora biti dolg med :min in :max digits.',
    'dimensions' => ':attribute ima napačne dimenzije.',
    'distinct' => ':attribute se ne sme ponoviti.',
    'email' => ':attribute mora biti veljaven e-mail naslov.',
    'ends_with' => ':attribute se mora končati z :values',
    'exists' => 'izbran :attribute ni veljaven.',
    'file' => ':attribute mora biti datoteka.',
    'filled' => ':attribute mora biti izpolnjen.',
    'gt' => [
        'numeric' => ':attribute mora biti večji od :value.',
        'file' => ':attribute mora bit večji od :value kilobajtov.',
        'string' => ':attribute mora biti greater than :value znakov.',
        'array' => ':attribute must have more than :value elementov.',
    ],
    'gte' => [
        'numeric' => ':attribute mora biti greater than or equal :value.',
        'file' => ':attribute mora biti greater than or equal :value kilobajtov.',
        'string' => ':attribute mora biti greater than or equal :value znakov.',
        'array' => ':attribute must have :value elementov or more.',
    ],
    'image' => ':attribute mora biti slika.',
    'in' => 'atribut :attribute ni veljaven.',
    'in_array' => ':attribute  does not exist in :other.',
    'integer' => ':attribute mora biti celo število.',
    'ip' => ':attribute mora biti a valid IP address.',
    'ipv4' => ':attribute mora biti a valid IPv4 address.',
    'ipv6' => ':attribute mora biti a valid IPv6 address.',
    'json' => ':attribute mora biti a valid JSON string.',
    'lt' => [
        'numeric' => ':attribute mora biti less than :value.',
        'file' => ':attribute mora biti less than :value kilobajtov.',
        'string' => ':attribute mora biti less than :value znakov.',
        'array' => ':attribute must have less than :value elementov.',
    ],
    'lte' => [
        'numeric' => ':attribute mora biti manjši ali enak :value.',
        'file' => ':attribute mora biti majši od :value kilobajtov.',
        'string' => ':attribute mora biti krajši od :value znakov.',
        'array' => ':attribute ne sme imeti več kot :value elementov.',
    ],
    'max' => [
        'numeric' => ':attribute ne sme biti večje od :max.',
        'file' => ':attribute ne sme biti večji od :max kilobajtov.',
        'string' => ':attribute ne sme biti daljši od :max znakov.',
        'array' => ':attribute ne sme imeti več kot :max elementov.',
    ],
    'mimes' => ':attribute mora biti datoteka tipa :values.',
    'mimetypes' => ':attribute mora biti datoteka tipa :values.',
    'min' => [
        'numeric' => ':attribute mora biti vsaj :min.',
        'file' => ':attribute mora biti velik vsaj :min kilobajtov.',
        'string' => ':attribute mora biti dolg vsaj :min znakov.',
        'array' => ':attribute mora imeti vsaj :min elementov.',
    ],
    'not_in' => 'izbran atribut :attribute je neveljaven.',
    'not_regex' => ':attribute format je nepravilen.',
    'numeric' => ':attribute mora biti a številka.',
    'present' => ':attribute  mora biti prisoten.',
    'regex' => ':attribute je  v nepravilnem formatu.',
    'required' => ':attribute je obvezen.',
    'required_if' => ':attribute  is required when :other is :value.',
    'required_unless' => ':attribute  is required unless :other is in :values.',
    'required_with' => ':attribute  is required when :values is present.',
    'required_with_all' => ':attribute  is required when :values are present.',
    'required_without' => ':attribute  is required when :values is not present.',
    'required_without_all' => ':attribute  is required when none of :values are present.',
    'same' => ':attribute in :other se morata ujemati.',
    'size' => [
        'numeric' => ':attribute mora biti :size.',
        'file' => ':attribute mora biti velik :size kilobajtov.',
        'string' => ':attribute mora imeti :size znakov.',
        'array' => ':attribute mora vsebovati :size elementov.',
    ],
    'starts_with' => ':attribute se mora začeti z :values',
    'string' => ':attribute mora biti niz.',
    'timezone' => ':attribute mora biti veljavno območje.',
    'unique' => ':attribute je že zaseden.',
    'uploaded' => ':attribute neuspešno naloženo.',
    'url' => ':attribute format ni veljaven.',
    'uuid' => ':attribute mora biti veljaven UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
