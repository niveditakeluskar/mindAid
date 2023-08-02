<?php

/*
|--------------------------------------------------------------------------
| RCare / Org / OrgPackages / QCTemplates Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
    Route::prefix('ccm')->group(function () {
        Route::get('/content-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@listContentTemplates')->name('ccm-content-template');
        Route::get('/questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@listQuestionnaireTemplates')->name('ccm-questionnaire-template');
        Route::get('/decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@listDecision')->name('ccm-decisiontree-template');
    });
    Route::prefix('rpm')->group(function () {
        Route::get('/content-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@listContentTemplates')->name('rpm-content-template');
        Route::get('/questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@listQuestionnaireTemplates')->name('rpm-questionnaire-template');
        Route::get('/decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@listDecision')->name('rpm-decisiontree-template');
    });
    Route::prefix('patients')->group(function () {
        Route::get('/content-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@listContentTemplates')->name('patients-content-template');
        Route::get('/questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@listQuestionnaireTemplates')->name('patients-questionnaire-template');
        Route::get('/decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@listDecision')->name('patients-decisiontree-template');
    });
});

Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('org')->group(function () {
        Route::get('/get-dynamic-template/{moduleId}/{subModuleId}/{stageId}/{stepId}/questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@getDynamicQuestionnaireTemplate')->name('get.dynamic.questionnaire.template');
        Route::get('get-template/{moduleid}/{stepid}/{type}/stepWise', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@getTemplate');
		
        Route::get('/ajax/template/{module}/{subModuleId}/{templateId}/list', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@getTemplateList')->name('get.template.list');
    });
    Route::prefix('ccm')->group(function () {
        //ccm content template routes
        
        Route::get('update-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@UpdateTemplate')->name('update-content-template');
        Route::post('save-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@saveTemplate')->name('save-template');
        Route::get('delete-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@DeleteTemplate');
        Route::get('/add-content-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@addTemplate')->name('add-content-template');
        Route::get('view-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@viewTemplateDetails');
        Route::get('print-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@printContentTemplate')->name('print.content.template');


        //ccm questionnaire template routes
        
        Route::get('update-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@UpdateTemplate')->name('update-questionnaire-template');
        Route::post('save-qtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@saveTemplate')->name('save-qtemplate');
        Route::get('delete-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@DeleteTemplate');
        Route::get('/add-questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@addTemplate')->name('add-questionnaire-template');
        Route::get('view-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@viewTemplateDetails');
        Route::view('dynamic-template-question', 'QCTemplates::QuestionnaireTemplates.dynamic-template-question');
        Route::get('print-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@printQuestionnaireTemplate')->name('print.questionnaire.template');
        Route::get('/copy-questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@copyQTemplate')->name('copy-questionnaire-template');

        
        Route::get('/add-decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@addDecision')->name('add-decisiontree-template');
        Route::post('save-dtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@saveDTemplate')->name('save-dtemplate');
        Route::view('dynamic-template-decision', 'QCTemplates::DecisionTreeTemplates.questionnairetest');
        Route::get('view-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@disableviewdecisiontree');
        Route::get('update-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@viewdecisiontree');
        Route::post('EditDecisionTree', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@EditDecision')->name('edit-Decision-Tree');
        Route::get('update-decision-tree-inline', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@UpdateDecisionTreeInline')->name('update.decision.tree.inline');
        Route::get('print-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@printDecisionTreeTemplate')->name('print.decisiontree.template');
        Route::post('/ajax/uploadDTImage','RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@DecisionTreeimage');
        Route::get('/copy-decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@copyDecision')->name('copy-decisiontree-template');
        Route::post('copy-dtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@copyDTemplate')->name('copy-dtemplate');

        Route::post('render-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@renderTemplate')->name('render-template');
    });

    Route::prefix('rpm')->group(function () {
        //rpm content template routes
        
        Route::get('update-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@UpdateTemplate')->name('update-content-template');
        Route::post('save-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@saveTemplate')->name('save-template');
        Route::get('delete-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@DeleteTemplate');
        Route::get('/add-content-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@addTemplate')->name('add-content-template');
        Route::get('view-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@viewTemplateDetails');
        Route::get('print-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@printContentTemplate')->name('print.content.template');

        //ccm questionnaire template routes
        
        Route::get('update-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@UpdateTemplate')->name('update-questionnaire-template');
        Route::post('save-qtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@saveTemplate')->name('save-qtemplate');
        Route::get('delete-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@DeleteTemplate');
        Route::get('/add-questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@addTemplate')->name('add-questionnaire-template');
        Route::get('view-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@viewTemplateDetails');
        Route::view('dynamic-template-question', 'QCTemplates::QuestionnaireTemplates.dynamic-template-question');
        Route::get('print-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@printQuestionnaireTemplate')->name('print.questionnaire.template');
        Route::get('/copy-questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@copyQTemplate')->name('copy-questionnaire-template');
        //rpm decision tree
        //Route::get('/decisiontree-template', 'RCare\Rpm\Http\Controllers\QuestionnaireController@listDecision')->name('listRpmDecision');
       
        Route::get('/add-decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@addDecision')->name('add-decisiontree-template');
        Route::post('save-dtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@saveDTemplate')->name('save-dtemplate');
        Route::view('dynamic-template-decision', 'QCTemplates::DecisionTreeTemplates.questionnairetest');
        Route::get('view-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@viewdecisiontree');
        Route::get('update-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@viewdecisiontree');
        Route::post('EditDecisionTree', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@EditDecision')->name('edit-Decision-Tree');
        Route::get('print-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@printDecisionTreeTemplate')->name('print.decisiontree.template');
   
        Route::get('/copy-decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@copyDecision')->name('copy-decisiontree-template');
        Route::post('copy-dtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@copyDTemplate')->name('copy-dtemplate');

        Route::post('render-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@renderTemplate')->name('render-template');
    });

    Route::prefix('patients')->group(function () {
        //rpm content template routes
        
        Route::get('update-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@UpdateTemplate')->name('update-content-template');
        Route::post('save-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@saveTemplate')->name('save-template');
        Route::get('delete-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@DeleteTemplate');
        Route::get('/add-content-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@addTemplate')->name('add-content-template');
        Route::get('view-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@viewTemplateDetails');
        Route::get('print-content-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\ContentTemplateController@printContentTemplate')->name('print.content.template');

        //ccm questionnaire template routes
        
        Route::get('update-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@UpdateTemplate')->name('update-questionnaire-template');
        Route::post('save-qtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@saveTemplate')->name('save-qtemplate');
        Route::get('delete-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@DeleteTemplate');
        Route::get('/add-questionnaire-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@addTemplate')->name('add-questionnaire-template');
        Route::get('view-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@viewTemplateDetails');
        Route::view('dynamic-template-question', 'QCTemplates::QuestionnaireTemplates.dynamic-template-question');
        Route::get('print-questionnaire-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@printQuestionnaireTemplate')->name('print.questionnaire.template');

        //rpm decision tree
        
        Route::get('/add-decisiontree-template', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@addDecision')->name('add-decisiontree-template');
        Route::post('save-dtemplate', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@saveDTemplate')->name('save-dtemplate');
        Route::view('dynamic-template-decision', 'QCTemplates::DecisionTreeTemplates.questionnairetest');
        Route::get('view-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@disableviewdecisiontree');
        Route::get('update-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@viewdecisiontree');
        Route::post('EditDecisionTree', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@EditDecision')->name('edit-Decision-Tree');
        Route::get('update-decision-tree-inline', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@UpdateDecisionTreeInline')->name('update.decision.tree.inline');
        Route::get('print-decisiontree-template/{id}', 'RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@printDecisionTreeTemplate')->name('print.decisiontree.template');
        Route::post('/ajax/uploadDTImage','RCare\Org\OrgPackages\QCTemplates\src\Http\Controllers\QuestionnaireTemplateController@DecisionTreeimage');
    });
});