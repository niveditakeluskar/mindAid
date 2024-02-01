<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gender'         => 'A gender must be selected',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',
    'f_name' => 'The :attribute must be a valid UUID.',
    'within_year' => 'The :attribute must be less than 18 months ago.',
    'medication_unique_name' => 'Medication name is already taken.', // added by pranali 29Oct2020
    'provider_unique_name' => 'Provider name is already taken.', // added by pranali 29Oct2020
    'time_textbox' => 'Please enter time in HH:MM:SS formate.', // added by pranali 30Oct2020
    'alpha_spaces' => 'The :attribute may only contain letters and spaces.', //added by pranali 11Feb2021
    'phone' => 'The :attribute may only contain 10 digit number.', // added by priya 12Feb2021
    'address' => 'The :attribute may only contain letters, number, comma and dashesh', //added by priya 12Feb2021
    'module_unique_name' => 'Module name is already taken.', // added by pranali 16June2022
    'text_comments_slash' => 'The :attribute may only contain letters, numbers, forward slashes, commas, dashes, full stops, round-bracket, and apostrophes', //added by ashviniarumugan

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'gender' => [
            'required' => 'A gender selection is required'
        ],
        'attribute-name' => [
            'rule-name' => 'custom-message'
        ],
        'api_url' => [
            'required_if' => '* The API url field is required.'
        ],

        'username' => [
            'required_if' => '* The username field is required.'
        ],
        'password' => [
            'required_if' => '* The password field is required.'
        ],

        'user_name' => [
            'required_if' => '* The username field is required.'
        ],
        'pass' => [
            'required_if' => '* The password field is required.'
        ],
        'from_name' => [
            'required_if' => '* The from name field is required.'
        ],
        'from_email' => [
            'required_if' => '* The from email field is required.'
        ],
        'host' => [
            'required_if' => '* The host field is required.'
        ],
        'port' => [
            'required_if' => '* The port field is required.'
        ],
        'cc_email' => [
            'required_if' => '* The cc email field is required.'
        ],

        //ccm validation

        //preparation
        'condition_requirnment' => [
            'required' => 'Please select atleast option.'
        ],
        'condition_requirnment_notes' => [
            'required_if' => 'The condition requirnment notes field is required.'
        ],
        'newofficevisit' => [
            'required' => 'The new office Visit field is required.'
        ],
        'nov_notes' => [
            'required_if' => 'The new office visit notes field is required.'
        ],
        'newdiagnosis' => [
            'required' => 'The new diagnosis field is required.'
        ],
        'nd_notes' => [
            'required_if' => 'The new diagnosis notes field is required.'
        ],
        'med_added_or_discon' => [
            'required' => 'The medications added or discontinued field is required.'
        ],
        'med_added_or_discon_notes' => [
            'required_if' => 'The new medications added or discontinued notes field is required.'
        ],
        'report_requirnment' => [
            'required' => 'Please select any option.'
        ],
        'report_requirnment_notes' => [
            'required_if' => 'The report requirnment notes field is required.'
        ],
        'newdme' => [
            'required' => 'The new dme field is required.'
        ],
        'dme_notes' => [
            'required_if' => 'The new dme notes field is required.'
        ],
        'changetodme' => [
            'required' => 'The change to dme field is required.'
        ],
        'ctd_notes' => [
            'required_if' => 'The change to dme notes field is required.'
        ],
        'uid' => [
            'required' => 'Patient with this First Name, Last Name and DOB already exist.'
        ],
        'email' => [
            'required_if' => 'The email field is required when `no email` is not selected.'
        ],
        'call_answer_template_id' => [
            'required_if' => 'The call answer template field is required when call status is answered.'
        ],
        'call_continue_status' => [
            'required_if' => 'The call continue status field is required when call status is answered.'
        ],
        'call_continue_followup_date' => [
            'required_if' => 'The call continue followup date field is required when call continue status is no.'
        ],
        'voice_mail' => [
            'required_if' => 'The voice mail field is required when call status is not answered.'
        ],
        'phone_no' => [
            'required_if' => 'The phone no field is required when call status is not answered.'
        ],
        'call_not_answer_template_id' => [
            'required_if' => 'The call not answer template field is required when call status is not answered.'
        ],
        'call_followup_date' => [
            'required_if' => 'The call followup date field is required when call status is not answered.'
        ],
        'reason_for_visit' => [
            'required_if' => 'Therapist come home care is answered yes'
        ],
        'follow_up_date' => [
            'required_if' => 'The follow up date field is required when "Do you know when the Home Service ends?" answered as no.'
        ],
        'service_end_date' => [
            'required_if' => 'The home service end date field is required when "Do you know when the Home Service ends?" answered as yes.'
        ],
        'med_description' => [
            'required_if' => 'The medication description name is required when name is other.'
        ],
        'symptoms.*' => [
            'required' => 'The symptom field is required.'
        ],
        'goals.*' => [
            'required' => 'The goal field is required.'
        ],
        'tasks.*' => [
            'required' => 'The task field is required.'
        ],
        'allergies.*' => [
            'required' => 'The allergies field is required.'
        ],
        'medications.*' => [
            'required' => 'The medications field is required.'
        ],
        'addparameters.*' => [
            'required' => 'The parameter field is required.'
        ],
        'parameters.*' => [
            'required' => 'The parameter field is required.'
        ],
        'lab.*' => [
            'required' => 'The lab field is required.'
        ],
        'labsdate.*' => [
            'required' => 'The lab date field is required.'
        ],
        'reading.*.*' => [
            'required' => 'The reading field is required.'
        ],
        'high_val.*.*' => [
            'required' => 'The value field is required.'
        ],
        'notes.*' => [
            'required' => 'The notes field is required.'
        ],
        'task_name.*' => [
            'required' => 'The task name field is required.'
        ],
        'followupmaster_task.*' => [
            'required' => 'The task category field is required.'
        ],
        'notes.*' => [
            'required' => 'The notes field is required.'
        ],



        // CPD Review
        'relational_status' => [
            'required_if' => 'Please choose your answer.'
        ],

        'fname.*' => [
            'required_if' => 'The First name field is required .'
        ],

        'lname.*' => [
            'required_if' => 'The Last name field is required.'
        ],
        'age.*' => [
            'required_if' => 'The age field is required.'
        ],
        'address.*' => [
            'required_if' => 'The address field is required.'
        ],
        'location.*' => [
            'required_if' => 'The location field is required.'
        ],
        'relationship.*' => [
            'required_if' => 'The relationship field is required.'
        ],

        'pet_status' => [
            'required' => 'Please choose your answer.'
        ],
        'pet_name.*' => [
            'required_if' => 'The name field is required.'
        ],
        'pet_type.*' => [
            'required_if' => 'The type field is required.'
        ],
        'description.*' => [
            'required_if' => 'The drug field is required.'
        ],

        'travel_status' => [
            'required' => 'Please choose your answer.'
        ],
        'frequency.*' => [
            'required_if' => 'The frequency field is required.'
        ],
        'with_whom.*' => [
            'required_if' => 'The with whom field is required.'
        ],
        'travel_type.*' => [
            'required_if' => 'The travel type field is required.'
        ],
        'upcoming_tips.*' => [
            'required_if' => 'The upcoming tips field is required.'
        ],

        'hobbies_status' => [
            'required' => 'Please choose your answer.'
        ],

        'hobbies_name.*' => [
            'required_if' => 'The name field is required.'
        ],

        'drop_condition' => [
            'required' => 'The Codition field is required.'
        ],
        'drop_code' => [
            'required' => 'The Code field is required.'
        ],
        'newmedications.*' => [
            'required' => 'The New Medication field is required.'
        ],
        'code.*' => [
            'required' => 'The Code field is required.'
        ],
        'imaging.*' => [
            'required' => 'The Imaging field is required.'
        ],

        'imaging_date.*' => [
            'required' => 'The Imaging Date field is required.'
        ],

        'query1' => [
            'required' => 'The Call Close field is required.'
        ],

        'query2' => [
            'required' => 'The Call Close field is required.'
        ],
        'q2_date' => [
            'required_if' =>  'Call Close date field is required'
        ],
        'q2_time' => [
            'required_if' =>  'Call Close time field is required'
        ],
        'q2_datetime' => [
            'required_if'    =>  'Call Close time field is required',
            'after' => 'The date must be a date from next month.'
        ],

        'health_data.*' => [
            'required' =>  'The Health Data is required.'
        ],

        'health_date.*' => [
            'required' =>  'The Health Date is required.'
        ],
        'specify' => [
            'required' =>  'The Company Name is required.'
        ],
        'brand' => [
            'required' =>  'The Prescribing Provider is required.'
        ],
        'module_id' => [
            'required' =>  'The Module Name is required.'
        ],
        'components' => [
            'required' =>  'The Sub Module Name is required.'
        ],
        'med_description' => [ // added by pranali 13Oct2020
            'medication_unique_name' => 'Medication name is already taken.'
        ],
        'provider_name' => [ // added by pranali 29Oct2020
            'required_if' => 'The provider name is required when selected provider is other.'
        ],
        'time' => [ // added by pranali 30Oct2020
            'max' => 'Please enter time in HH:MM:SS format.'
        ],
        'phone' => [ // added by priya 31Dec2020
            'max' => 'Please enter 10 digit Phone number.'
        ],
        'outgoing_phone_number' => [ // added by priya 31Dec2020
            'max' => 'Please enter 10 digit Phone number.'
        ],
        'comments' => [ // added by radha for activation or extend deactivation form
            'required_if' => 'The Reason for Activation field is required.'
        ],
        // 'labdate.*.*' => [ // added by radha for activation or extend deactivation form
        //     'required' => 'The lab date field is required.'
        // ], 
        // 'labsdate.*' => [ // added by radha for lab date field
        //     'required' => 'The lab date field is required.'
        // ],
        'notes' => [ // added by priya for worklist activity
            'required_if' => 'Required if the time is manual.'
        ],
        'net_time' => [
            'required' => 'The time is required.'
        ],
        'name' => [
            'required' => 'This field is required.'
        ],
        'activity.*.*' => [
            'required' => 'This activity field is required.'
        ],
        'activity.*.defaulttime' => [
            'required' => 'This default time field is required.'
        ],
        'activity.*.newactivitypracticebasedtime.*' => [
            'required_unless' => 'This practice time field is required.'
        ],
        'activity.*.newactivitypracticebasedtime.*' => [
            'date_format' => 'Please enter time in HH:MM:SS format.'
        ],
        // 'activity.*.defaulttime.*' => [
        //     'date_format' => 'Please enter time in HH:MM:SS format.'
        // ],
        'time_required.*' => [
            'required_unless' => 'This practice time field is required.'
        ],
        'time_required.*' => [
            'date_format' => 'Please enter time in HH:MM:SS format.'
        ],
        'service_id' => [
            'required' => 'This module field is required.'
        ],
        'description' => [
            'required' => 'This step name is required.'
        ],
        'practice_name' => [
            'required' => 'This ' . config("global.practice_group") . ' field is required.'
        ],
        'activedeactivefromdate' => [
            'required_if' => 'The from date field is required.'
        ],
        'activedeactivetodate' => [
            'required_if' => 'The To date field is required.'
        ],
        'deceasedfromdate' => [
            'required_if' => 'The Date of Deceased field is required.'
        ],
        'specify' => [
            'required_if' => 'The specify field is required when above checkbox is not checked.'
        ],
        'type_of_reactions' => [
            'required_if' => 'The type of reactions field is required when above checkbox is not checked.'
        ],
        'severity' => [
            'required_if' => 'The severity field is required when above checkbox is not checked.'
        ],
        'course_of_treatment' => [
            'required_if' => 'The course of treatment field is required when above checkbox is not checked.'
        ],
        'call_back_date' => [
            'required_if' => ' Please enter the date and time for call back.'
        ],
        'enrl_refuse_reason' => [
            'required_if' => 'Please enter the reason for not enrolling in the program.'
        ],
        'answer_followup_date' => [
            'required_if' => 'The call followup date field is required when call answered is No.'
        ],
        'answer_followup_time' => [
            'required_if' => 'The call followup time field is required when call answered is No.'
        ],

        // Device-order

        'fname' => [
            'required' => 'First Name is required.'
        ],

        'lname' => [
            'required' => 'Last Name is required.'
        ],

        'dob' => [
            'required' => 'DOB is required .'
        ],

        'gender' => [
            'required' => 'Gender is required .'
        ],

        'add_1' => [
            'required' => 'Address is required .'
        ],

        'city' => [
            'required' => 'City is required .'
        ],

        'state' => [
            'required' => 'State is required .'
        ],

        'zipcode' => [
            'required' => 'Zip Code is required .'
        ],

        'mob' => [
            'required' => 'Phone is required .'
        ],

        'family_fname' => [
            'required' => 'First Name is required.'
        ],

        'family_lname' => [
            'required' => 'Last Name is required.'
        ],

        'family_mob' => [
            'required' => 'Phone is required .'
        ],

        'family_add' => [
            'required' => 'Address is required .'
        ],

        'email' => [
            'required' => 'Email is required.'
        ],

        'phone_type' => [
            'required' => 'Phone Type is required.'
        ],

        'Relationship' => [
            'required' => 'Relationship is required .'
        ],

        'ordertype' => [
            'required' => 'Consol Type is required .'
        ],

        'shipping_option' => [
            'required' => 'Shipping Speed is required .'
        ],

        'practices' => [
            'required' => 'Practice Name is required .'
        ],

        'patient_id' => [
            'required' => 'Patient is required .'
        ],
        'device_code' => [
            'required_if' => 'Device Code is required.'
        ],
        'add1' => [
            'required' => 'The Address field is required.'
        ],

        'headquaters_fname' => [
            'required_if' => 'The First Name is required.'
        ],
        'headquaters_phone' => [
            'required_if' => 'The Phone is required.'
        ],
        'headquaters_lname' => [
            'required_if' => 'The Last Name is required.'
        ],
        'headquaters_address' => [
            'required_if' => 'The Address field is required.'
        ],
        'headquaters_city' => [
            'required_if' => 'The City is required.'
        ],
        'headquaters_state' => [
            'required_if' => 'The State is required.'
        ],
        'headquaters_zip' => [
            'required_if' => 'The Zip Code is required.'
        ],
        'headquaters_zip' => [
            'required_if' => 'The Zip Code is required.'
        ],
        'high_val.*.*' => [
            'required_if' => 'The value field is required.'
        ],

        'emr_monthly_summary_date.*' => [
            'required'    =>  'The date field is required',
            'before_or_equal' =>  'Please choose a date on or before today.'
        ],
        'date_of_execution' => [
            'required'    =>  'The date field is required',
            'after_or_equal' =>  'Please choose a date on or after today.'
        ],
        'emr_monthly_summary.*' => [
            'required_with'    => 'The additional notes field is required.',
            'min'              => 'The additional notes must be at least 3 characters.',
        ],

        // 'module' => [
        //     'exists' => 'The module has already been taken...'
        // ],
    ],

    /*
       |--------------------------------------------------------------------------
       | Custom Validation Attributes
       |--------------------------------------------------------------------------
       |
       | The following language lines are used to swap attribute place-holders
       | with something more reader friendly such as E-Mail Address instead
       | of "email". This simply helps us make messages a little cleaner.
       |
     */

    'attributes' => [
        'practice_id'                => 'Practice',
        'provider_id'                => 'Provider',
        'provider_subtype_id'        => "credential",
        'specialist_id'              => "specialist",
        'f_name'                     => 'First Name',
        'l_name'                     => 'Last Name',
        'fname'                      => 'First Name',
        'lname'                      => 'Last Name',
        'add_1'                      => 'Address',
        'mob'                        => 'Primary phone number',
        'org_id'                     => 'Organization',
        'emr'                        => 'EMR',
        'category_id'                => 'Category',
        'practice__id'               => 'Practice',
        'template_type'              => 'Content type',
        'question.q.*.questionTitle' => "Question title",
        'question.q.*.answerFormat'  => "Answer format",
        'api_url'                    => "API url",
        'api_url.required_if'        => "The API url field is required.",
        'med_id'                     => "medication ",
        'provider_type_id'           => "provider type",
        'provider_name'              => "provider",
        // 'name'                       => "practice",
        'q2_datetime'                => "date",
        'sub_provider_type'          => "Credential",
        'health_date.*'              => "Health Date",
        'imaging_date.*'             => "Imaging Date",
        'activity.*'                 => "Activity",
        'task_date.*'                => "task date",
        'task_name.*'                => "task name",
        'followupmaster_task.*'      => "task category",
        'notes.*'                    => "notes",
        'activity.*.defaulttime.*'   => "activity defaulttime",
        'diagnosis'                  => "condition",
        'symptoms.*'                 => "symptoms",
        'goals.*'                    => "goals",
        'tasks.*'                    => "tasks",
        'query1'                     => "call close",
        'activedeactivefromdate'     => "From date",
        'activedeactivetodate'       => "To date",
        'deceasedfromdate'           => "Date of Deceased",
        'deactivation_reason'        => "Reason",
        'labdate.*.*'                => "lab date",
        'labdate.*'                  => "lab date",
        'family_fname'               => "First Name",
        'family_lname'               => "Last Name",
        'military_status'            => "Veteran status",
        "fin_number"                 => "FIN Number",
        // 'validation.phone'           => "Please Enter 10 digit Number"
        "emr_monthly_summary_date.*" => "date",
        "date_of_execution"          => "date"

    ]
];
