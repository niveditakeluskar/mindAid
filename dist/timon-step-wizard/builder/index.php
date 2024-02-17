<?php

define("Version", rand());


 ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8">
    <meta name="description" lang="en" content="Bal – Email Newsletter Builder - This is a drag & drop email builder plugin based on Jquery and PHP for developer. You can simply integrate this script in your web project and create custom email template with drag & drop">
    <meta name="keywords" lang="en" content="bounce, bulk mailer, campaign, campaign email, campaign monitor, drag & drop email builder, drag & drop email editor, mailchimp, mailer, newsletter, newsletter email, responsive, retina ready, subscriptions, templates">
    <meta name="robots" content="index, follow">
    <title>Form Builder - Timon Step Form</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/bal-email-editor.css?<?php echo Version; ?>" rel="stylesheet" />
    <link href="assets/css/colorpicker.css?<?php echo Version; ?>" rel="stylesheet" />
		<link href="../assets/css/tsf-wizard.bundle.min.css?<?php echo Version; ?>" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="bal-editor-container clearfix">
        <aside class="bal-left-menu-container clearfix">
            <div class="bal-nav">
                <ul class="bal-left-menu">
                    <li class="bal-menu-item active tab-selector" data-tab-selector="tab-elements">
                        <i class="fa fa-puzzle-piece"></i>
                        <span class="bal-menu-name">Elements</span>
                    </li>
                    <li class="bal-menu-item tab-selector" data-tab-selector="tab-property">
                        <i class="fa fa-pencil"></i>
                        <span class="bal-menu-name">Property</span>
                    </li>
										<li class="bal-menu-item tab-selector" data-tab-selector="tab-step-form">
                        <i class="fa fa-cogs "></i>
                        <span class="bal-menu-name">Form settings</span>
                    </li>

										<li class="bal-menu-item blank-page">
                        <i class="fa fa-file"></i>
                        <span class="bal-menu-name">Add new step</span>
                    </li>
                    <li class="bal-menu-item bal-collapse btn-collapse-bottom ">
                        <i class="fa fa-chevron-circle-left"></i>
                        <span class="bal-menu-name">Collapse menu</span>
                    </li>
                </ul>
            </div>
            <div class="bal-elements">
                <div class="bal-elements-container">
                    <div class="tab-elements bal-element-tab active">
                        <ul class="bal-elements-accordion">

                          <?php

                          include_once 'config.php';

                          $jsonElements = file_get_contents(ELEMENTS_DIRECTORY);

                          $jfo = json_decode($jsonElements);
                          $elements=  $jfo->elements;
                            $_accordionItems='';
                            for ($i=0; $i <sizeof( $elements); $i++) {

                              echo '<li class="bal-elements-accordion-item" data-type="'.strtolower($elements[$i]->name).'">
                                                <a class="bal-elements-accordion-item-title">'.$elements[$i]->name.'</a>';

                              echo '<div class="bal-elements-accordion-item-content">
                                                 <ul class="bal-elements-list">';

                             $items=$elements[$i]->items;

                               for ($j=0; $j < sizeof($items); $j++) {
                                  echo '<li>
                                             <div class="bal-elements-list-item">
                                                 <div class="bal-preview">
                                                     <div class="bal-elements-item-icon">
                                                         <i class="'.$items[$j]->icon.'"></i>
                                                     </div>
                                                     <div class="bal-elements-item-name">
                                                         '.$items[$j]->name.'
                                                     </div>
                                                 </div>
                                                 <div class="bal-view">
                                                 <div class="sortable-row">
                                                     <div class="sortable-row-container">
                                                         <div class="sortable-row-actions">
                                                             <div class="row-move row-action">
                                                                 <i class="fa fa-arrows-alt"></i>
                                                             </div>
                                                             <div class="row-remove row-action">
                                                                 <i class="fa fa-remove"></i>
                                                             </div>
                                                             <div class="row-duplicate row-action">
                                                                 <i class="fa fa-files-o"></i>
                                                             </div>
																														 <div class="row-code row-action">
                                                                 <i class="fa fa-code"></i>
                                                             </div>
                                                         </div>
                                                         <div class="sortable-row-content">
                                                             '.str_replace('[site-url]',SITE_URL,file_get_contents(SITE_DIRECTORY.$items[$j]->content)).'
                                                         </div>
                                                     </div>
                                                 </div>
                                                 </div>
                                             </div>
                                         </li>';
                               }

                               //
                              echo '</ul></div>';
                              echo '</li>';
                            }

                          ?>
                        </ul>
                    </div>
                    <div class="tab-property bal-element-tab">
                        <ul class="bal-elements-accordion">

                            <li class="bal-elements-accordion-item" data-type="background">
                                <a class="bal-elements-accordion-item-title">Background</a>
                                <div class="bal-elements-accordion-item-content clearfix">
                                    <div id="bg-color" class="bg-color bg-item" setting-type="background-color">
                                        <i class="fa fa-adjust"></i>
                                    </div>
                                    <!-- <div class="bg-item bg-image" setting-type="background-image">
                                        <i class="fa fa-image"></i>
                                    </div> -->
                                </div>
                            </li>

														<li class="bal-elements-accordion-item" data-type="step-property">
															<a class="bal-elements-accordion-item-title active">Step form</a>
														    <div class="bal-elements-accordion-item-content" style="display: block;">
														        <div class="bal-social-content-box ">
														            <label>Text</label>
														            <input type="text" class="step-form-input " data-type="main-text">
														            <label>Subtext</label>
														            <input type="text" class="step-form-input " data-type="sub-text">
														            <!-- <label class="checkbox-title">
														                <input type="checkbox" name="name" checked="" class="step-form-number "> Show step number </label> -->
														            <br>
														            <!-- <label class="checkbox-title">
														                <input type="checkbox" name="name" class="step-form-subtext "> Show step subtext </label> -->
														        </div>
														    </div>
														</li>

                            <li class="bal-elements-accordion-item" data-type="padding">
                                <a class="bal-elements-accordion-item-title">Padding</a>
                                <div class="bal-elements-accordion-item-content">
                                    <div class=" bal-element-boxs clearfix ">
                                        <div class="big-box col-sm-6 ">
                                            <input type="text" class="form-control padding all" setting-type="padding">
                                        </div>
                                        <div class="small-boxs col-sm-6">
                                            <div class="row">
                                                <input type="text" class="form-control padding number" setting-type="padding-top">
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control padding number" setting-type="padding-left">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control padding number" setting-type="padding-right">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="text" class="form-control padding number" setting-type="padding-bottom">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="bal-elements-accordion-item" data-type="border-radius">
                                <a class="bal-elements-accordion-item-title">Border Radius</a>
                                <div class="bal-elements-accordion-item-content">
                                    <div class=" bal-element-boxs bal-border-radius-box clearfix ">
                                        <div class="big-box col-sm-6 ">
                                            <input type="text" class="form-control border-radius all" setting-type="border-radius">
                                        </div>
                                        <div class="small-boxs col-sm-6">
                                            <div class="row clearfix">
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control border-radius" setting-type="border-top-left-radius">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control border-radius" setting-type="border-top-right-radius">
                                                </div>
                                            </div>
                                            <div class="row clearfix margin">
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control border-radius" setting-type="border-bottom-left-radius">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control border-radius" setting-type="border-bottom-right-radius">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="bal-elements-accordion-item" data-type="text-style">
                                <a class="bal-elements-accordion-item-title">Text Style</a>
                                <div class="bal-elements-accordion-item-content">
                                    <div class="bal-element-boxs bal-text-style-box clearfix ">
                                        <div class="bal-element-font-family col-sm-8">
                                            <select class="form-control font-family" setting-type="font-family">
                                                <option value="Arial">Arial</option>
                                                <option value="Helvetica">Helvetica</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Times New Roman">Times New Roman</option>
                                                <option value="Verdana">Verdana</option>
                                                <option value="Tahoma">Tahoma</option>
                                                <option value="Calibri">Calibri</option>
                                            </select>
                                        </div>
                                        <div class="bal-element-font-size col-sm-4">
                                            <input type="text" name="name" class="form-control number" value="14" setting-type="font-size" />
                                        </div>

                                        <div class="bal-icon-boxs bal-text-icons clearfix">
                                            <div class="bal-icon-box-item fontStyle" setting-type="font-style" setting-value="italic">
                                                <i class="fa fa-italic"></i>
                                            </div>
                                            <div class="bal-icon-box-item active underline " setting-type="text-decoration" setting-value="underline">
                                                <i class="fa fa-underline"></i>
                                            </div>
                                            <div class="bal-icon-box-item line " setting-type="text-decoration" setting-value="line-through">
                                                <i class="fa fa-strikethrough"></i>
                                            </div>
                                        </div>


                                        <div class="bal-icon-boxs bal-align-icons clearfix">
                                            <div class="bal-icon-box-item left active">
                                                <i class="fa fa-align-left"></i>
                                            </div>
                                            <div class="bal-icon-box-item center ">
                                                <i class="fa fa-align-center"></i>
                                            </div>
                                            <div class="bal-icon-box-item right">
                                                <i class="fa fa-align-right"></i>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="bal-icon-boxs bal-text-icons ">
                                            <div id="text-color" class="bal-icon-box-item text-color" setting-type="color">
                                            </div>
                                            Text Color
                                        </div>
                                        <div class="bal-icon-boxs bal-font-icons clearfix">
                                            <div class="bal-icon-box-item" setting-type="bold">
                                                <i class="fa fa-bold"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

														<li class="bal-elements-accordion-item" data-type="image-settings">
																<a class="bal-elements-accordion-item-title">Image settings</a>
																<div class="bal-elements-accordion-item-content">
																		<div class="bal-social-content-box ">
																		   	<div class="change-image">Change image</div>
																				<label>Image width</label>
																					<input type="text" class="image-width  image-size " setting-type="" >

																				<label>Image height</label>
																				<input type="text" class="image-height  image-size" setting-type="">
																		</div>
																</div>
														</li>

														<li class="bal-elements-accordion-item" data-type="input-text">
															<a class="bal-elements-accordion-item-title active">Text</a>
															<div class="bal-elements-accordion-item-content" style="display: block;">
																<div class="col-sm-12 bal-social-content-box ">
																	<div class="row ">
																		<div class="input-text-item" data-type="input-text-field">
																		<label class="small-title">Input Type</label>

																		<select class="form-control  form-select input-text-type input-text-control " data-input-type="type">
																			<option value="text" selected="">Text</option>
																			<option value="tel">Tel</option>
																			<option value="url">URL</option>
																			<option value="color">Color</option>
																			<option value="password">Password</option>
																		</select>
																	</div>
																		<div  class="input-text-item " data-type="input-date-field">
																			<label class="small-title">Input Type</label>
																			<select class="form-control  form-select input-date-type input-text-control" data-input-type="date-type">
																				<option value="date" selected="">Date</option>
																				<option value="datetime-local">DateTime-Local</option>
																				<option value="time">Time</option>
																				<option value="month">Month</option>
																				<option value="week">Week</option>
																			</select>
																		</div>

																		<div class="input-text-item"  data-type="input-date-field input-text-field input-textarea-field  input-number-field input-email-field input-file-field input-checkbox-field input-select-field input-radio-field">
																				<label class="small-title">Id</label>
																				<input type="text" name="name" value="" class="input-id input-text-control " data-input-type="input-id">
																		</div>
																		<div class="input-text-item"  data-type="input-date-field input-text-field input-textarea-field  input-number-field input-email-field input-file-field input-checkbox-field input-select-field input-radio-field">
																				<label class="small-title">Name</label>
																				<input type="text" name="name" value="" class="input-name input-text-control " data-input-type="input-name">
																		</div>
																				<div  class="input-text-item" data-type="input-checkbox-field">
																				    <label class="small-title">Checkboxes</label>
																				    <textarea class="form-control form-textarea input-text-control" style="min-height:100px" data-input-type="checkbox">First Choice|check</textarea>
																				</div>
																				<div  class="input-text-item" data-type="input-select-field">
																						<label class="small-title">Options</label>
																						<textarea class="form-control form-textarea input-text-control" style="min-height:100px" data-input-type="select">First Choice|select</textarea>
																				</div>
																				<div  class="input-text-item" data-type="input-radio-field">
																						<label class="small-title">Radios</label>
																						<textarea class="form-control form-textarea input-text-control" style="min-height:100px" data-input-type="radio">First Choice|select</textarea>
																				</div>
																				<div class="input-text-item" data-type="input-date-field input-text-field input-textarea-field  input-number-field input-email-field input-file-field input-checkbox-field input-select-field input-radio-field">
																				    <label class="small-title">Label</label>
																				    <input type="text" name="name" value="" class="input-label input-text-control" data-input-type="label">
																				</div>
																				<div class="input-text-item" data-type="input-file-field">
																						<label class="small-title">Accept</label>
																						<input type="text" name="name" value="" class="input-accept input-text-control" data-input-type="accept">
																				</div>
																				<div class="input-text-item" data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field">
																				    <label class="small-title">Placeholder</label>
																				    <input type="text" name="name" value="" class="input-placeholder input-text-control" data-input-type="placeholder">
																				</div>
																				<div  class="input-text-item" data-type="  input-textarea-field ">
																				    <label class="small-title">Field size</label>
																				    <input type="number" name="number" value="" class="input-field-size input-text-control" data-input-type="rows" />
																				</div>
																				<div class="input-text-item" data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field">
																				    <label class="small-title">Predefined value</label>
																				    <input type="text" name="name" value="" class="input-value  input-text-control " data-input-type="value">
																				</div>
																				<div  class="input-text-item" data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field input-file-field input-checkbox-field input-select-field input-radio-field">
																				    <label class="small-title">Help Text</label>
																				    <input type="text" name="name" value="" class="input-help-text input-text-control" data-input-type="help-text" />
																				</div>
																				<div  class="input-text-item" data-type="input-number-field ">
																				    <label class="small-title">Min number</label>
																				    <input type="text" name="name" value="" class="input-min-number input-text-control " data-input-type="min-number">
																				</div>
																				<div class="input-text-item"  data-type="input-number-field">
																				    <label class="small-title">Max number</label>
																				    <input type="text" name="name" value="" class="input-max-number input-text-control " data-input-type="max-number">
																				</div>
																				<div  class="input-text-item" data-type="input-number-field">
																				    <label class="small-title">Step number</label>
																				    <input type="text" name="name" value="" class="input-step-number input-text-control " data-input-type="step-number">
																				</div>
																				<div class="input-text-item"  data-type="input-date-field">
																				    <label class="small-title">Min date</label>
																				    <input type="text" name="name" value="" class="input-min-date  input-text-control " data-input-type="min-date">
																				</div>
																				<div class="input-text-item"  data-type="input-date-field">
																				    <label class="small-title">Max date</label>
																				    <input type="text" name="name" value="" class="input-max-date input-text-control " data-input-type="max-date">
																				</div>

																				<div class="input-text-item"  data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field input-file-field input-checkbox-field  input-select-field input-radio-field">
																				    <label class="small-title">CSS Class</label>
																				    <input type="text" name="name" value="" class="input-css-class input-text-control " data-input-type="input-class">
																				</div>
																				<div  class="input-text-item" data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field input-file-field input-checkbox-field  input-select-field input-radio-field">
																				    <label class="small-title">Label CSS Class</label>
																				    <input type="text" name="name" value="" class="input-label-css-class input-text-control " data-input-type="label-class">
																				</div>

																				<div class="input-text-item"  data-type=" input-file-field">
																				    <label class="checkbox-title">
																				        <input type="checkbox" name="name" class="input-file-multi input-text-control " data-input-type="multi-file"> Multi select</label>
																				    <br>
																				</div>

																				<div class="input-text-item"  data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field input-checkbox-field  input-select-field input-radio-field">
																				    <label class="checkbox-title">
																				        <input type="checkbox" name="name" class="input-required input-text-control " data-input-type="required"> Required</label>
																				    <br>
																				</div>
																				<div class="input-text-item"  data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field  input-select-field ">
																				    <label class="checkbox-title">
																				        <input type="checkbox" name="name" class="input-readonly input-text-control " data-input-type="readonly"> Read only</label>
																				    <br>
																				</div>
																				<div class="input-text-item"  data-type="input-date-field input-text-field input-textarea-field input-number-field input-email-field input-checkbox-field  input-select-field input-radio-field">
																				    <label class="checkbox-title">
																				        <input type="checkbox" name="name" class="input-disabled input-text-control " data-input-type="disabled"> Disabled
																						</label>
																				    <br>
																		</div>
																	</div>
																</div>
															</div>
														</li>
                        </ul>
                    </div>

										<div class="tab-step-form bal-element-tab ">
									    <ul class="bal-elements-accordion">
                        <li class="bal-elements-accordion-item" data-type="all-step-form">
                         <a class="bal-elements-accordion-item-title ">All Steps</a>
                           <div class="bal-elements-accordion-item-content" style="display: block;">
                               <div class="bal-social-content-box ">
                                   <ul class="step-list">

                                   </ul>
                               </div>
                           </div>
                       </li>
									        <li class="bal-elements-accordion-item" data-type="step-form">
														<a class="bal-elements-accordion-item-title">Step form settings</a>
									            <div class="bal-elements-accordion-item-content" >
									                <div class="bal-social-content-box ">
									                    <label>Step style</label>
									                    <select class="form-control form-select step-form-style" >
									                        <option value="style1" selected="selected">Style 1</option>
									                        <option value="style2">Style 2</option>
									                        <option value="style3">Style 3</option>
									                        <option value="style4">Style 4</option>
									                        <option value="style5">Style 5</option>
									                        <option value="style5_circle">Style 5 with circle</option>
									                        <option value="style6">Style 6</option>
									                        <option value="style7_borderTop">Style 7 (border top)</option>
									                        <option value="style7_borderBottom">Style 7 (border bottom)</option>
									                        <option value="style7_borderLeft">Style 7 (border left)</option>
									                        <option value="style7_borderRight">Style 7 (border right)</option>
									                        <option value="style7_borderTop_circle">Style 7 (border top) with circle </option>
									                        <option value="style7_borderBottom_circle">Style 7 (border bottom) with circle </option>
									                        <option value="style7_borderLeft_circle">Style 7 (border left) with circle </option>
									                        <option value="style7_borderRight_circle">Style 7 (border right) with circle </option>
									                        <option value="style8">Style 8</option>
									                        <option value="style8_circle">Style 8 with circle </option>
									                        <option value="style9">Style 9</option>
									                        <option value="style10">Style 10</option>
									                        <option value="style11">Style 11</option>
									                        <option value="style12">Style 12</option>
									                    </select>
									                    <br>
									                    <label>Step form effect</label>
									                    <select class="form-control form-select step-form-effect">
									                        <option value="basic" selected="selected">Basic</option>
									                        <option value="bounce">Bounce</option>
									                        <option value="slideRightLeft">Slide from right to left</option>
									                        <option value="slideLeftRight">Slide from left to right</option>
									                        <option value="flip">Flip</option>
									                        <option value="transformation">Transformation</option>
									                        <option value="slideDownUp">Slide from down to up</option>
									                        <option value="slideUpDown">Slide from up to down</option>
									                    </select>
									                    <br>
									                    <label>Step navigation position</label>
									                    <select class="form-control form-select step-form-nav-pos">
									                        <option value="top" selected="selected">Top</option>
									                        <option value="bottom">Bottom</option>
									                        <option value="right">Right</option>
									                        <option value="left">Left</option>
									                    </select>
																			<br>
									                    <label>Form height</label>
									                    <input type="text" name="step-form-height"  class="step-form-height" value="auto">
																			<br>
									                    <label>Next button text</label>
									                    <input type="text" name="step-form-next-button"  class="step-form-next" value="NEXT">
																			<br>
									                    <label>Previous button text</label>
									                    <input type="text" name="step-form-prev-button"  class="step-form-prev" value="PREV" >
																			<br>
																			<label>Finish button text</label>
																			<input type="text" name="step-form-finish-button"  class="step-form-finish" value="FINISH" >
									                    <br>
									                    <label class="checkbox-title">
									                        <input type="checkbox" name="name" checked="" class="step-form-show-number"> Show step number </label>
									                    <br>
									                    <label class="checkbox-title">
									                        <input type="checkbox" name="name" class="step-form-transition"> Show transition </label>
									                </div>
									            </div>
									        </li>
									    </ul>

									</div>

                </div>
                <div class="bal-settings">
                    <ul>
                        <li class="bal-setting-item preview" data-toggle="tooltip" title="Preview">
                            <i class="fa fa-eye"></i>
                        </li>
                        <li class="bal-setting-item export" data-toggle="tooltip" title="Export">
                            <i class="fa fa-share"></i>
                        </li>
												<li class="bal-setting-item save-template" data-toggle="tooltip" title="" data-original-title="Save Template">
													<i class="fa fa-floppy-o"></i>
												</li>
												<li class="bal-setting-item load-templates" data-toggle="tooltip" title="" data-original-title="Load Template">
													<i class="fa fa-file-text"></i>
												</li>
                    </ul>

                </div>
            </div>

        </aside>
        <div class="bal-content">
            <div class="bal-content-wrapper" data-types="padding">
                <div class="bal-content-main lg-width">
									<div class="tsf-wizard tsf-wizard-1">
											<!-- BEGIN NAV STEP-->
											<div class="tsf-nav-step">
													<!-- BEGIN STEP INDICATOR-->
													<ul class="gsi-step-indicator triangle gsi-style-1  gsi-transition ">
															<li class="current" data-target="step-1">
																	<a href="#0">
																			<span class="number">1</span>
																			<span class="desc">
																					<label>Account setup</label>
																					<span class="">Account details</span>
																			</span>
																	</a>
															</li>
															<li  data-target="step-2">
																	<a href="#0">
																			<span class="number">2</span>
																			<span class="desc">
																					<label>Billing </label>
																					<span  class="">Infor</span>
																			</span>
																	</a>
															</li>
													</ul>
													<!-- END STEP INDICATOR--->
											</div>
											<!-- END NAV STEP-->

											<!-- BEGIN STEP CONTAINER -->
											<div class="tsf-container">
													<!-- BEGIN CONTENT-->
													<form class="tsf-content">
															<!--<form class="tsf-form">-->
															<!-- BEGIN STEP 1-->
															<div class="tsf-step step-1 active">
																					<div class="tsf-step-content email-editor-elements-sortable">
																						<div class="sortable-row">
																									<div class="sortable-row-container">
																										 <div class="sortable-row-actions">
																											 <div class="row-move row-action">
																												 <i class="fa fa-arrows-alt"></i>
																											 </div>
																											 <div class="row-duplicate row-action">
																												<i class="fa fa-files-o"></i>
																											</div>
																											 <div class="row-remove row-action">
																												 <i class="fa fa-remove"></i>
																											 </div>
																											 <div class="row-code row-action">
																												 <i class="fa fa-code"></i>
																											 </div>
																										 </div>
																										 <div class="sortable-row-content" >
																											 <div class="main" data-types="background,text-style,padding,input-text" data-last-type="input-text" data-type="input-text-field">
																											     <div class="element-content">
																											         <!-- Text -->
																											         <div class="form-group row">
																											             <label class="col-xs-2 col-form-label" data-type="label">Text Field</label>
																											             <div class="col-xs-10">
																											                 <input class="form-control" type="text"
																											                 data-type="input"  />
																											                 <span class="help-block" data-type="help">Help text</span>
																											             </div>
																											         </div>
																											     </div>
																											 </div>
																											</div>
																										</div>
																								</div>


																					</div>
															</div>
															<!-- END STEP 1-->
															<!-- BEGIN STEP 2-->
															<div class="tsf-step step-2">
																			<!-- BEGIN STEP CONTENT-->
																			<div class="tsf-step-content email-editor-elements-sortable">
																				<div class="sortable-row">
																							<div class="sortable-row-container">
																								 <div class="sortable-row-actions">
																									 <div class="row-move row-action">
																										 <i class="fa fa-arrows-alt"></i>
																									 </div>
																									 <div class="row-duplicate row-action">
																										<i class="fa fa-files-o"></i>
																									</div>
																									 <div class="row-remove row-action">
																										 <i class="fa fa-remove"></i>
																									 </div>
																									 <div class="row-code row-action">
																										 <i class="fa fa-code"></i>
																									 </div>
																								 </div>
																								 <div class="sortable-row-content" >
																									 <div class="main" data-types="background,text-style,padding,input-text" data-last-type="input-text" data-type="input-text-field">
																											 <div class="element-content">
																													 <!-- Text -->
																													 <div class="form-group row">
																															 <label class="col-xs-2 col-form-label" data-type="label">Text Field</label>
																															 <div class="col-xs-10">
																																	 <input class="form-control" type="text"
																																	 data-type="input"  />
																																	 <span class="help-block" data-type="help">Help text</span>
																															 </div>
																													 </div>
																											 </div>
																									 </div>
																									</div>
																								</div>
																						</div>
																			</div>
																			<!-- END STEP CONTENT-->
															</div>
															<!-- END STEP 2-->
															<!--</form>-->
													</form>
													<!-- END CONTENT-->
													<!-- BEGIN CONTROLS-->
													<div class="tsf-controls ">
															<!-- BEGIN PREV BUTTTON-->
															<button type="button" data-type="prev" class="btn btn-left tsf-wizard-btn">
																	<i class="fa fa-chevron-left"></i> PREV
															</button>
															<!-- END PREV BUTTTON-->
															<!-- BEGIN NEXT BUTTTON-->
															<button type="button" data-type="next" class="btn btn-right tsf-wizard-btn">
																	NEXT <i class="fa fa-chevron-right"></i>
															</button>
															<!-- END NEXT BUTTTON-->
															<!-- BEGIN FINISH BUTTTON-->
															<button type="submit" data-type="finish" class="btn btn-right tsf-wizard-btn">
																	FINISH
															</button>
															<!-- END FINISH BUTTTON-->
													</div>
													<!-- END CONTROLS-->
											</div>
											<!-- END STEP CONTAINER -->


									</div>
									  <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="bal-context-menu">
            <ul class="context-menu-items">
							<li class="bal-context-menu-item font-style" data-menu-type="font-family">
								<div>
							<select class="form-control font-family" >
									<option value="Arial" selected="selected">Arial</option>
									<option value="Helvetica">Helvetica</option>
									<option value="Georgia">Georgia</option>
									<option value="Times New Roman">Times New Roman</option>
									<option value="Verdana">Verdana</option>
									<option value="Tahoma">Tahoma</option>
									<option value="Calibri">Calibri</option>
							</select>
						</div>
					</li>
								<li class="bal-context-menu-item font-style" data-menu-type="font-size">
										<div>
												<select class="font-size form-control">
													<option value="6px">6px</option>
													<option value="8px">8px</option>
													<option value="9px">9px</option>
													<option value="10px">10px</option>
													<option value="11px">11px</option>
													<option value="12px">12px</option>
													<option value="14px" selected="selected">14px</option>
													<option value="18px">18px</option>
													<option value="24px">24px</option>
													<option value="30px">30px</option>
													<option value="36px">36px</option>
													<option value="48px">48px</option>
												</select>
										</div>
								</li>
                <li class="bal-context-menu-item" data-menu-type="bold">
                    <div>
                        <i class="fa fa-bold"></i>
                    </div>
                </li>
                <li class="bal-context-menu-item" data-menu-type="italic">
                    <div>
                        <i class="fa fa-italic"></i>
                    </div>
                </li>
                <li class="bal-context-menu-item" data-menu-type="underline">
                    <div>
                        <i class="fa fa-underline"></i>
                    </div>
                </li>
                <li class="bal-context-menu-item" data-menu-type="strikethrough">
                    <div>
                        <i class="fa fa-strikethrough"></i>
                    </div>
                </li>
                <li class="bal-context-menu-item" data-menu-type="link">
                    <div>
                        <i class="fa fa-link"></i>
                    </div>

                </li>
            </ul>
						<div class="context-menu-hyperlink ">
							<div class="row">
								<div class="col-md-10"> <input type="text" class="form-control context-menu-hyperlink-input" ></div>
								<div class="col-md-1">
										<a href="javascript:void(0)" class="context-menu-hyperlink-close" title="Close">&times;</a>
								</div>
							</div>
						</div>
        </div>


    </div>

    <div class="modal fade popup_images" id="popup_images" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Select image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="file" class="input-file" accept="image/*" >
                  </div>
                  <div class="col-sm-6">
                      <button class="btn-upload">Upload</button>
                   </div>
                 </div>

                 <div class="upload-images">
                   <div class="row">

                   </div>
                 </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success btn-select" >Select</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
          </div>

        </div>
    </div>

    <div class="modal fade " id="popup_save_template" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Save template<br>
              <small>Please write template name</small></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12">
                    <input type="text" class="form-control template-name" placeholder="Please enter template name"  >
                    <br>
                    <label class="input-error" style="color:red"></label>
                  </div>
                 </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success btn-save-template" >Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
    </div>
    <div class="modal fade " id="popup_load_template" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Load template<br>
              <small>Please select template for load</small></h4>
            </div>
            <div class="modal-body">
              <div class="template-list">

                </div>
                <label class="template-load-error" style="color:red"></label>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success btn-load-template" >Load</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
    </div>

    <!-- for demo version -->
    <div class="modal fade " id="popup_demo" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Demo<br>
                </h4>
              </div>
              <div class="modal-body">
                  <label  style="color:red">This is demo version. There is not access to use more.
                        If you want to more please buy plugin. <a href="https://codecanyon.net/item/timon-step-form-wizard/15830006?ref=askerov">Timon - Step Form Wizard</a></label>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
    </div>
		<div class="modal fade " id="popup_load_template" role="dialog">
			<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h4 class="modal-title">Load template<br>
						<small>Please select template for load</small>
					</h4>
				</div>
				  <div class="modal-body">
						<div class="template-list">
						</div>
						<label class="template-load-error" style="color:red"></label>
					</div><div class="modal-footer">
						<button type="button" class="btn btn-success btn-load-template">Load</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade " id="popup_save_template" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Save template<br><small>Please write template name</small></h4></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="text" class="form-control template-name" placeholder="Please enter template name">
                        <br>
                        <label class="input-error" style="color:red"></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-save-template">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="assets/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/vendor/jquery-nicescroll/dist/jquery.nicescroll.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<!--for tinymce  -->
	  <script src="http://cdn.tinymce.com/4/tinymce.min.js"></script>

    <script src="assets/js/colorpicker.js?<?php echo Version; ?>"></script>
    <script src="assets/js/bal-email-editor.js?<?php echo Version; ?>"></script>
		<script src="assets/js/save-template.js?<?php echo Version; ?>"></script>
		<script src="../assets/js/tsf-wizard.bundle.min.js?<?php echo Version; ?>"></script>


		<script type="text/javascript">
				// $('.tsf-wizard-1').tsfWizard({
				// 		 stepEffect: 'basic',
				// 		 stepStyle: 'style1',
				// 		 navPosition: 'top',
				// 		 validation: false,
				// 		 stepTransition: true,
				// 		 showButtons: true,
				// 		 showStepNum: true,
				// 		 height: 'auto',
				// 		 disableSteps:'none'
				//  });
		</script>


</body>
</html>
