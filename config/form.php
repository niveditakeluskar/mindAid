<?php

return [
//	"billing_codes" => [
//		"billing_initial_awv"           => "Initial AWV (G0438)",
//		"billing_subsequent_awv"        => "Subsequent AWV (G0439)",
//		"billing_advanced_care_plan"    => "Advanced Care Plan (99497)",
//		"billing_care_plan_development" => "Care Plan Development (G0506)"
//	],

	"education" => [
		"High School",
    	"Trade School",
		"Some College",
		"College",
		"Unknown"
	],

    "activeStatus" => [
        1 => "Active",
        0 => "Inactive"
    ],

	"category" => [
		"1",
		"1",
		"2",
        "33"
	],

	"license_model" => [
		 "RAAR",
		 "SAAS"  
	],

	"answer_format" => [
        1 => "Dropdown",
        2 => "Textbox",
        3 => "Radio",
        4 => "Checkbox",
        5 => "Textarea"
	],
	"activity" => [
		1 => "Monitor Data",
		2 => "Go To HOC",
		3 => "Setup New Patient"
	],
	"review_data" => [
		1 => "Not recorded",
		2 => "Within guidelines",
		3 => "Out of guidelines"
	],
	"answer" => [
		1 => "Left voice mail",
		2 => "No voice mail"
	],
	
	
	// For the medication list in questionnaire
	// These are all frequency of use
	// FYI: These are latin abbreviations.
//	"medical_abbreviations" => [
//		"QD", // Daily
//		"BID", // Twice Daily
//		"TID", // Three time a Day
//		"QID", // Four times a day
//		"As Directed", // As prescribed by doctor
//		"ac", // before meal
//		"pc", // after meal
//		"HS", // before bed
//		"PRN", // as needed
//		"Monthly",
//		"Weekly"
//	],

	"occupation_status" => [
		"Working",
		"Retired",
		"Disabled"
	],

//	"military" => [ //added on 12Apr2019
//		0 => "Yes",
//		1 => "No",
//		2 => "Unknown"
//	],

//	"select_yes_no_option" => [ //added on 7May2019
//		0 => "No",
//		1 => "Yes"
//	],

//	"preferred_contact" => [
//		0 => 'Primary phone number',
//		1 => 'Secondary phone number',
//		2 => 'Email',
//		3 => 'Other contact'
//	],

	"states" => [
		"AK" => "Alaska",
		"AL" => "Alabama",
		"AR" => "Arkansas",
		"AZ" => "Arizona",
		"CA" => "California",
		"CO" => "Colorado",
		"CT" => "Connecticut",
		"DC" => "District of Columbia",
		"DE" => "Delaware",
		"FL" => "Florida",
		"GA" => "Georgia",
		"HI" => "Hawaii",
		"IA" => "Iowa",
		"ID" => "Idaho",
		"IL" => "Illinois",
		"IN" => "Indiana",
		"KS" => "Kansas",
		"KY" => "Kentucky",
		"LA" => "Louisiana",
		"MA" => "Massachusetts",
		"MD" => "Maryland",
		"ME" => "Maine",
		"MI" => "Michigan",
		"MN" => "Minnesota",
		"MO" => "Missouri",
		"MS" => "Mississippi",
		"MT" => "Montana",
		"NC" => "North Carolina",
		"ND" => "North Dakota",
		"NE" => "Nebraska",
		"NH" => "New Hampshire",
		"NJ" => "New Jersey",
		"NM" => "New Mexico",
		"NV" => "Nevada",
		"NY" => "New York",
		"OH" => "Ohio",
		"OK" => "Oklahoma",
		"OR" => "Oregon",
		"PA" => "Pennsylvania",
		"PR" => "Puerto Rico",
		"RI" => "Rhode Island",
		"SC" => "South Carolina",
		"SD" => "South Dakota",
		"TN" => "Tennessee",
		"TX" => "Texas",
		"UT" => "Utah",
		"VA" => "Virginia",
		"VT" => "Vermont",
		"WA" => "Washington",
		"WI" => "Wisconsin",
		"WV" => "West Virginia",
		"WY" => "Wyoming"
	],
	
	"ethnicities" => [
		"Caucasian",
		"African-American",
		"Asian",
		"Hispanic/Latino",
		"Unknown"
	],

//	"titles" => [
//		"A.U.",
//		"AE-C",
//		"ANP",
//		"ATC",
//		"Au.D.",
//		"B.C.-ADM",
//		"B.S.",
//		"BSN",
//		"CCC-A",
//		"CCC-SLP",
//		"CCRN",
//		"CDE",
//		"CDTC",
//		"CFNP",
//		"CGC",
//		"CHT",
//		"CLE",
//		"CMA",
//		"CNM",
//		"CNS",
//		"CNSC",
//		"CNSD",
//		"COMT",
//		"CPM",
//		"CSCS",
//		"CSO",
//		"CSSD",
//		"D.O.",
//		"DPM",
//		"DPT",
//		"Ed.D.",
//		"Ed.S.",
//		"FAAP",
//		"FACC",
//		"FCCP",
//		"FACOG",
//		"FNP",
//		"FNP-C",
//		"IBCLC",
//		"L.E.",
//		"LCSW",
//		"M.A.",
//		"M.D.",
//		"M.S.",
//		"MFT",
//		"MHS",
//		"MMS",
//		"MPA",
//		"MPH",
//		"MPT",
//		"MSPAS",
//		"MSPT",
//		"MSW",
//		"N.P.",
//		"NP-C",
//		"O.D.",
//		"OCN",
//		"OCS",
//		"OTR",
//		"P.A.",
//		"PA-C",
//		"P.T.",
//		"Ph.D.",
//		"PHN",
//		"Psy D",
//		"R.D.",
//		"R.N.",
//		"RCP",
//		"RNC",
//		"RNFA",
//		"SCS",
//		"WHNP",
//	],

//	"usage" => [
//		"N/A",
//		"No",
//		"Sometimes",
//		"Always"
//	],

	"password_requirements" => "min:6",

//	"admission_facility_type" => [
//		"ac_inpatient"               => "AC Inpatient",
//		"ac_observation"             => "AC Observation",
//		"ac_op_partial_hosp"         => "AC OP Partial Hosp",
//		"rehab_hospital"             => "Rehab Hospital",
//		"ltac_snf"                   => "LTAC- SNF",
//		"mental_health_partial_hosp" => "Mental Health Partial Hosp",
//		"other"                      => "Other"
//	],

//	"admitted_form" => [
//		"direct_admission"       => "Direct Admission",
//		"through_ed"             => "Through ED",
//		"readmit_thirty"         => "Re-admission w/in 30 days",
//		"snf"                    => "SNF",
//		"mental_health_facility" => "Mental Health Facility",
//		"other"                  => "Other-Not Qualified",
//		"unknown"                => "Unknown"
//	],

//	"discharged_to" => [
//		"home"                   => "Home",
//		"family_member_home"     => "Family Member Home",
//		"non_family_member_home" => "Non-family member home",
//		"assisted_living"        => "Assisted Living",
//		"rest_foster_home"       => "Rest/Foster Home",
//		"hospice"                => "Hospice- TCM is N/A",
//		"snf"                    => "SNF- TCM is N/A",
//		"rehab"                  => "Rehab- TCM N/A",
//		"other"                  => "Other-Not Qualified"
//	],

//	"first_contact_attempt_method" => [
//		0 => "Telephone",
//		1 => "E-mail",
//		2 => "Face to Face"
//	],

//	"second_contact_attempt_method" => [
//		"telephone" => "Telephone",
//		"e_mail" => "E-mail",
//		"face_to_face" => "Face to Face"
//	],

//	"addl_contact_attempt_method" =>[
//		"telephone" => "Telephone",
//		"e_mail" => "E-mail",
//		"face_to_face" => "Face to Face"
//	],

//	"first_contact_attempt_successful"=>[
//		0=> "Yes",
//        1=> "No"
//	],

//	"second_contact_attempt_successful"=>[
//		0=> "Yes",
//        1=> "No"
//	],

//	"addl_contact_attempt_successful"=>[
//		0=> "Yes",
//        1=> "No"
//	],

//	"timely_initial_contact_outcome" =>[
//		 0=> "Completed",
//         1=> "Missed"
//	],

//	"addl_timely_initial_contact_outcome" =>[
//		 0=> "Completed",
//         1=> "Missed"
//	],
	
//	"med_recon_status" => [
//	    0 => "In Progress",
//		1 => "Completed"
//	],
	
//	"med_recon_discharge_medications_type" => [
//	    0 => "New",
//		1 => "Existing"
//	],
    
//	"med_recon_medication_changes_type" =>[
//		0 => "Discontinued",
//		1 =>"Discrepency"
//	],
	
//	"billing_ending" => [
//		0 => "99495 Moderate Complexity",
//		1 => "99496 High Complexity",
//		2 => "99495 Moderate Complexity (Telehealth)",
//		3 => "99496 High Complexity (Telehealth)",
//		4 => "Unsuccessful TCM-Unable to Bill"
//	],
	
//	"med_recon_compliance_issue_reason" => [
//	    0 => "Ability to Obtain",
//		1 => "Cost",
//		2 => "Dose",
//		3 => "Inability to Consume",
//		4 => "Education/Understanding",
//		5 => "Ineffectiveness",
//		6 => "Intolerance",
//		7 => "Motivation",
//		8 => "Non-Compliance",
//		9 => "Side Effects",
//		10 => "Other"
//	],
	
//	"section" => [
//		"adl" => [
//			"is_hearing_impaired",
//			"can_see_clearly",
//			"has_glasses_contacts",
//			"is_living_alone",
//			"has_caregiver",
//			"cognitive_impairment",
//			"handles_own_finances",
//			"shops_independently"
//		],
//		"caffeine" => [
//			"caffeine_coffee",
//			"caffeine_tea",
//			"caffeine_cola"
//		],
//		"childhood_illnesses" => [
//			"measles",
//			"mumps",
//			"rubella",
//			"chickenpox",
//			"rheumatic_fever",
//			"polio"
//		],
//		"devices" => [
//			"devices_pacemaker",
//			"devices_defibrillator",
//			"devices_port_a_cath",
//			"devices_brain_stim",
//			"devices_bladder_stim",
//		    "devices_other" //Added on 12Apr2019
//		],
//		"depression_medication" => [
//			"depression_med_elavil",
//			"depression_med_tofranil",
//			"depression_med_cymbalta",
//			"depression_med_anafranil",
//			"depression_med_surmontil",
//			"depression_med_zoloft",
//			"depression_med_norpramin",
//			"depression_med_celexa",
//			"depression_med_paxil",
//			"depression_med_sinequan",
//			"depression_med_lexapro",
//			"depression_med_effexor",
//			"depression_med_prozac",
//		],
//		"diagnosed_by_doctors" => [
//			"diagnosed_arthritis",
//			"diagnosed_asthma",
//			"diagnosed_cancer_breast",
//			"diagnosed_cancer_colon",
//			"diagnosed_depression",
//			"diagnosed_diabetes",
//			"diagnosed_heart_attack",
//			"diagnosed_high_cholesterol",
//			"diagnosed_hypertension",
//			"diagnosed_stroke",
//			"diagnosed_congestive_heart_failure",
//			"diagnosed_cancer_other"  //Added on 18March2019
//		],
//		"family_history" => [
//			"history_high_blood_pressure",
//			"history_stroke",
//			"history_heart_disease",
//			"history_high_cholesterol",
//			"history_diabetes",
//			"history_glaucoma",
//			"history_cancer",
//			"history_alcoholism",
//			"history_asthma_copd",
//			"history_depression_suicide",
//			"history_abdominal_aortic_aneurysm" //Added on 18March2019
//		],
//		"immunizations" => [
//			"immunization_tetanus",
//			"immunization_hepatitis",
//			"immunization_influenza",
//			"immunization_pneumonia",
//			"immunization_varicella"
//		],
//		"other_problems" => [
//			"other_problems_skin",
//			"other_problems_chest_heart",
//			"other_problems_head_neck",
//			"other_problems_back",
//			"other_problems_ears",
//			"other_problems_intestinal",
//			"other_problems_nose",
//			"other_problems_bladder",
//			"other_problems_throat",
//			"other_problems_bowel",
//			"other_problems_lungs",
//			"other_problems_circulation",
//			"other_problems_recent_changes_weight",
//			"other_problems_recent_changes_sleep",
//			"other_problems_recent_changes_energy_level"
//		],
//		"preventive_services" => [
//			"recommend_abdom_aortic_screen",
//			"recommend_alcohol_misuse_screen",
//			"recommend_bone_mass_measurements",
//			"recommend_cardio_disease_screen",
//			"recommend_cardio_disease_bt",
//			"recommend_cervical_cancer_screen",
//			"recommend_colorectal_cancer_screen",
//			"recommend_counsel_tobacco",
//			"recommend_depression_screen",
//			"recommend_diabetes_smt",
//			"recommend_glaucoma_screen",
//			"recommend_hep_c_screen",
//			"recommend_hiv_screen",
//			"recommend_lung_cancer_screen",
//			"recommend_mammogram_screen",
//			"recommend_nutrition_therapy",
//			"recommend_obesity_screen",
//			"recommend_medicare_visit",
//			"recommend_prostate_cancer_screen",
//			"recommend_std_screen",
//			"recommend_flu_shots",
//			"recommend_hep_b_shots",
//			"recommend_pneumococcal_shots",
//			"recommend_tobacco_screen",
//			"recommend_yearly_wellness",
//			"recommend_pelvic_exam"
//		],
//		"routine_screenings" => [
//			"routine_screening_mammography",
//			"routine_screening_std",
//			"routine_screening_prostate",
//			"routine_screening_bone_density",
//			"routine_screening_ultrasound",
//			"routine_screening_cholesterol",
//			"routine_screening_triglyceride",
//			"routine_screening_hdl",
//			"routine_screening_colonoscopy",
//			"routine_screening_pap"
//		],
//		"routine_screening_results" => [
//			"routine_screening_triglyceride",
//			"routine_screening_hdl",
//			"routine_screening_cholesterol"
//		],
//		"non_billable_outcomes" => [
//			"outcome_greater_than_3_years_since_last_ov",
//			"outcome_no_prior_office_visit",
//			"outcome_initial_contact_not_timely",
//			"outcome_medication_reconciliation_not_timely_or_completed",
//			"outcome_face_to_face_not_completed",
//			"outcome_face_to_face_not_completed_timely",
//			"outcome_patient_expired_since_discharge",
//			"outcome_admission_facility_type",
//			"outcome_discharge_to_non_qualified_location",
//			"outcome_no_discharge_instructions_available",
//			"outcome_readmission_for_same_or_similar_condition_occurred"
//		],
//		"face_to_face_status" => [
//            0 => "In Progress",
//            1 => "Completed"
//		],
//		"face_to_face_notCompleted_reason" => [
//            0 => "Patient Refused",
//            1 => "Readmitted",
//            2 => "Missed Time Frame",
//            3 => "Deceased",
//            4 => "Transportation Issue",
//            5 => "Other"
//		],
//		"home_health" => [
//			"completed"   => "Completed",
//			"in_progress" => "In Progress"
//		]
//	]
];
