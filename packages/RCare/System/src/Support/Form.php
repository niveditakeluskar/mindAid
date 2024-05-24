<?php

/**
 * Form Generation Utility
 * Generate various form components with the required markup for display errors
 * Created by David Ludwig
 */
 
namespace RCare\System\Support;

use AvpLab\PhpHtmlBuilder as HtmlBuilder;
use Carbon\Carbon;

class Form
{
    // Text Boxes --------------------------------------------------------------

    /**
     * Generate a generic input of the given type
     *
     * @param  string $type
     * @param  string $name
     * @param  array  $attributes
     * @param  string $value
     * @return string
     */
    public static function input(string $type, string $name, array $attributes = [], string $value = "", int $min = null, int $max = null, string $placeholder = "")
    {
        if($type=='date'){
            $attributes["min"]    = defaultParameter($attributes, "min", "1901-01-01");
            $attributes["max"]    = defaultParameter($attributes, "max", "2999-12-31");
        }
        if($type=='number'){
            $attributes["min"]    = defaultParameter($attributes, "min", $min);
            $attributes["max"]    = defaultParameter($attributes, "max", $max);
            $attributes["placeholder"]    = defaultParameter($attributes, "placeholder", $placeholder);
        }
        $attributes["class"]        = rtrim("form-control " . defaultParameter($attributes, "class", ""));
        $attributes["name"]         = defaultParameter($attributes, "name", $name);
        $attributes["type"]         = defaultParameter($attributes, "type", $type);
        $attributes["value"]        = defaultParameter($attributes, "value", $value);
        $attributes["autocomplete"] = defaultParameter($attributes, "autocomplete", "off");
        $builder = new HtmlBuilder();
        $builder->input($attributes)->endOpened();
        if (!isset($attributes["data-feedback"])) {
            $builder->div()->setClass("invalid-feedback")->end();
        }
        return $builder->build();
    }

    /**
     * Generate a checkbox
     *
     * @param  string $name
     * @param  string $id
     * @param  string $label
     * @param  string $value
     * @param  array  $attributes
     * @param  bool   $checked
     * @return string
     */
    public static function checkBox(
        string $name,
        string $id,
        string $label,
        string $value,
        array $attributes = [],
                                    bool $checked = false
    ) {
        $attributes["class"]   ="custom-control-input";
        $attributes["id"]      = $id;
        $attributes["name"]    = defaultParameter($attributes, "name", $name);
        $attributes["type"]    = "checkbox";
        $attributes["value"]   = $value;
        if ($checked) {
            $attributes["checked"] = "checked";
        }
        $builder = new HtmlBuilder();
        $builder->div()->setClass("custom-control custom-checkbox");
        $builder->input($attributes)->endOpened();
        $builder->label($label)->setClass("custom-control-label")->setFor($id)->end();
        return $builder->end()->build();
    }

    /**
    * Generate a sectioncheckbox
    *
    * @param  string $name
    * @param  string $id
    * @param  string $label
    * @param  string $value
    * @param  array  $attributes
    * @param  bool   $checked
    * @return string
    */
    public static function sectionCheckBox(
        string $name,
        string $id,
        string $label,
        string $value,
        array $attributes = [],
        bool $checked = false
    ) {
        $attributes["class"]   ="custom-control-input";
        $attributes["id"]      = $id;
        $attributes["name"]    = defaultParameter($attributes, "name", $name);
        $attributes["type"]    = "checkbox";
        $attributes["value"]   = $value;
        if ($checked) {
            $attributes["checked"] = "checked";
        }
        $builder = new HtmlBuilder();
        $builder->label($label)->setFor($id)->end();
        $builder->input($attributes)->setClass("show-field")->endOpened();
        return $builder->build();
    }

    // Select Boxes ------------------------------------------------------------

    /**
     * Generate a select box
     *
     * @param  string $label
     * @param  string $name
     * @param  array  $options
     * @param  array  $attributes
     * @param  string $selected
     * @return string
     */
    public static function select(
        string $label,
        string $name,
        array $options = [],
        array $attributes = [],
        string $selected = ""
    ) {
        $classes = isset($attributes["class"]) ? $attributes["class"] : "";
        /*custom-select*/
        $attributes["class"] = rtrim("custom-select show-tick $classes");
        $attributes["name"] = $name;
        $builder = new HtmlBuilder();
        $setselected = "";

        $builder->select($attributes)
                ->option(["value" => ''])->addText($label == "None" ? $label : "Select $label")->end();
               
                if($label != "None")
                {
                    if($label=='Medication')
                    {
                         $builder->option(["value" => 'other'])->addText("Other")->end();
                    }
                   
                }
        foreach ($options as $key => $option) {
            if($selected == $key){
                $setselected = "";
            }
            $builder->option(["value" => $key, $setselected])->addText($option)->end();
        }
        $builder->end();
        if (!isset($attributes["data-feedback"])) {
            $builder->div()->setClass("invalid-feedback")->end();
        }
        return $builder->build();
    }

      /**
     * Generate a select box that will allow you to specify an option group.
     *created by pranali on 23Nov2020
     * @param  string $label
     * @param  string $name
     * @param  array  $options
     * @param  array  $attributes
     * @param  string $selected
     * @return string
     */
    public static function selectWithOptionGroup(
        string $label,
        string $name,
        array $attributes = [],
        string $selected = "",
        array $data
    ) {
        $classes             = isset($attributes["class"]) ? $attributes["class"] : "";
        $attributes["class"] = rtrim("custom-select show-tick select2 $classes");
        $setselected         = "";
        $optgroup            = "";
        $attributes["name"]  = $name;
        $builder             = new HtmlBuilder();
        $builder->addHtml('</br>')->select($attributes)
                ->option(["value" => ''])->addText($label == "None" ? $label : "Select $label")->end();
        foreach ( $data as $d ) {
            if ( $d->group_by != $optgroup ) {
                if ( $optgroup ) {
                    $builder->addHtml('</optgroup>');
                }
                $builder->addHtml('<optgroup label='.$d->group_by.'>');
                $optgroup = $d->group_by;
            }
            if($label == 'Patient Activity' || $label == 'Activity' )
            {
                $builder->option(["value" => $d->id, $setselected])->addText($d->name)->end(); 
            }
            else{
                $c = '';
                if(isset($d->count)){
                    $c = $d->count;
                }
                $builder->option(["value" => $d->id, $setselected])->addText($d->name."  (".$c.")")->end();
            }
         
        }
        $builder->addHtml('</optgroup>');
        $builder->end();
        if (!isset($attributes["data-feedback"])) {
            $builder->div()->setClass("invalid-feedback")->end();
        }
        return $builder->build();
    }

    // without select2 class
    public static function selectWithOptionGroupWithoutSelectClass2(
        string $label,
        string $name,
        array $attributes = [],
        string $selected = "",
        array $data
    ) {
        $classes             = isset($attributes["class"]) ? $attributes["class"] : "";
        $attributes["class"] = rtrim("custom-select show-tick $classes");
        $setselected         = "";
        $optgroup            = "";
        $attributes["name"]  = $name;
        $builder             = new HtmlBuilder();
        $builder->addHtml('</br>')->select($attributes)
                ->option(["value" => ''])->addText($label == "None" ? $label : "Select $label")->end();
        foreach ( $data as $d ) {
            if ( $d->group_by != $optgroup ) {
                if ( $optgroup ) {
                    $builder->addHtml('</optgroup>');
                }
                $builder->addHtml('<optgroup label='.$d->group_by.'>');
                $optgroup = $d->group_by;
            }
            $builder->option(["value" => $d->id, $setselected])->addText($d->name)->end();
        }
        $builder->addHtml('</optgroup>');
        $builder->end();
        if (!isset($attributes["data-feedback"])) {
            $builder->div()->setClass("invalid-feedback")->end();
        }
        return $builder->build();
    }
    /**
     * Generate a searchable select box
     *
     * @param  string $label
     * @param  string $name
     * @param  array  $options
     * @param  array  $attributes
     * @param  string $selected
     * @return string
     */
    public static function selectSearchable(
        string $label,
        string $name,
        array $options = [],
        array $attributes = [],
        string $selected = ""
    ) {
        $classes = isset($attributes["class"]) ? $attributes["class"] : "";
        $attributes["class"] = rtrim("selectpicker form-control show-tick $classes");
        $attributes["name"] = $name;
        $attributes["data-live-search"] = true;
        $attributes["title"] = $label == "None" ? $label : "Select $label";
        $attributes["data-style"] = "custom-select";
        $generateFeedback = !isset($attributes["data-feedback"]);
        $hash = createHash();
        $attributes["data-feedback"] = defaultParameter($attributes, "data-feedback", "$hash");
        $builder = new HtmlBuilder();
        $builder->select($attributes)
                ->option(["value" => ""])->addText($label == "None" ? $label : "Select $label")->end();
        foreach ($options as $key => $option) {
            $builder->option(["value" => $key])->addText($option)->end();
        }
        $builder->end();
        if ($generateFeedback) {
            $builder->div()->setClass("invalid-feedback visible")->setDataFeedbackArea($hash)->end();
        }
        return $builder->build();
    }

    /**
     * Generate a select box that will allow you to specify an 'other' option through a text input.
     *
     * @param  string $label
     * @param  string $name
     * @param  array  $options
     * @param  array  $attributes
     * @param  string $selected
     * @return string
     */
    public static function selectOrOther(
        string $label,
        string $name,
        array $options,
                                         array $attributes = []
    ) {
        $classes = isset($attributes["class"]) ? $attributes["class"] : "";
        $attributes["class"] = rtrim("form-control $classes");
        $attributes["name"] = $name;
        $builder = new HtmlBuilder();
        $builder->div()->setClass("select-other-container")
                ->select($attributes)->setClass("select-other ");
        $builder->option(["value" => ""])->addText("Select $label")->end();
        foreach ($options as $key => $option) {
            $builder->option()->addText($option)->end();
        }
        $builder->option()->setClass("editable")->addText("Enter Other...")->end();
        $builder->end()
                ->div()->setClass("edit-option-container")
                ->input()->setClass("form-control edit-option")->setStyle("display: none;")
                ->setPlaceholder("Click to enter other...")
                ->endOpened()->end();
        if (!isset($attributes["data-feedback"])) {
            $builder->div()->setClass("invalid-feedback")->end();
        }
        return $builder->end()->build();
    }

    // Reusable Components -----------------------------------------------------

    /**
     * In some cases, you may need dynamic data in a Blade directive, but you'll find that that
     * data will be cached, and nothing will seem to update. So, if your directive needs to
     * make use of dynamic data, you're better off creating a dedicated method here.
     */


    /**
     * Create a select box for patient selection
     *
     * @return void
     */
    public static function selectPatient(
        string $name,
        array $attributes = [],
                                        string $selected = ""
    ) {
        $patients = [];
        foreach (Patients::all() as $patient) {
            $patientString = $patient->fname . " " . $patient->mname . " " . $patient->lname . ", DOB: " . $patient->bday;
            $patients[$patient->id] = $patientString;
        }
        return self::selectSearchable("Patient", $name, $patients, $attributes, $selected);
    }
}