
_temp_name='timon_template_';

function getId() {
  return Math.floor(Math.random() * Math.floor(30000));
}

function getTemplates() {
  var templateList='';
  for (var i = 0; i < localStorage.length; i++) {
    if (localStorage.key(i).startsWith(_temp_name)) {
      value=JSON.parse(localStorage.getItem(localStorage.key(i)));
      templateList+='<div class="template-item" data-id="'+localStorage.key(i)+'">'+
                        '<div class="template-item-delete" data-id="119"><i class="fa fa-trash-o"></i></div>'+
                        '<div class="template-item-icon"><i class="fa fa-file-text-o"></i></div>'+
                        '<div class="template-item-name">'+value.name+'</div>'+
                    '</div>';
    }

  }
  if (localStorage.length==0) {
    templateList='<center>No item</center>';
  }
  return templateList;


}



$(function () {
  loadSteps();
});


$(document).on('click','.step-delete',function () {
  dataId=$(this).parents('li').attr('data-id');
  stepForm=$('.tsf-nav-step li').eq(dataId).attr('data-target');

  $('.step-list li').eq(dataId).remove();
  $('.tsf-nav-step li').eq(dataId).remove();
  $('.tsf-content').find('.'+stepForm).remove();
});
$(document).on('click','.save-template',function () {
  $('.template-name').val('');
  $('#popup_save_template').modal('show');
});
$(document).on('click','.btn-save-template',function () {

  templateName=$('.template-name').val();
  content=$('.bal-content-wrapper').html();

  $('.input-error').text('');
  if ($('.template-name').val().length < 1) {
      $('.input-error').text('Template name required');
      return false;
  }

  var template = {
    'name': templateName,
    'content': content
   };

  localStorage.setItem(_temp_name+getId(), JSON.stringify(template));
  $('#popup_save_template').modal('hide');
});

$(document).on('click','.load-templates',function () {
  $('.template-list').html(getTemplates());
  $('#popup_load_template').modal('show');
});

$(document).on('click','.btn-load-template',function () {
  dataId=$('.template-item.active').attr('data-id');
  template=JSON.parse(localStorage.getItem(dataId));
  $('.bal-content-wrapper').html(template.content);
  $('#popup_load_template').modal('hide');

  changeFormSettings();
});

$(document).on('click','.template-item-delete',function (e) {
  e.stopPropagation();
  dataId=$(this).parents('.template-item').attr('data-id');
  localStorage.removeItem(dataId);
  $(this).parents('.template-item').remove();
  $('.btn-load-template').hide();
});
