var _content = $('.bal-content');
var _menu = $('.bal-left-menu-container');
var _is_demo = true;

function loadSteps() {
  var stepsList=$('.step-list');
  stepsList.html('');
  $('.tsf-nav-step li').each(function () {
    stepsList.append('<li data-id="'+$(this).index()+'">'+
                      '<span class="step-name" >'+$(this).find('.desc label').text()+'</span>'+
                      '<span class="step-delete"><i class="fa fa-trash"></i></span>'+
                    '</li>');
  });
}
$(function() {
    // $('[data-type="input"]').focusin(function() {
    //     $(this).blur();
    // });

    /*Nicescroll*/
    $(".bal-elements-container").niceScroll({
        cursorcolor: "#5D8397",
        cursorwidth: "10px",
        background: "#253843"
    });
    //$(".bal-elements-container").css({'overflow':''});
    /*for the tooltip*/
    $('[data-toggle="tooltip"]').tooltip();
    /*accordion*/
    _menu.find('.bal-elements-accordion .bal-elements-accordion-item-title').on('click', function(j) {
        _element = $(this);
        _menuAccordionClick(_element, false);
    });
    _menu.find('.tab-selector').on('click', function() {
        _element = $(this);
        _tabMenuItemClick(_element, true);
    });
    _menu.find('.blank-page').on('click', function() {
        _element = $(this);
        _newStepIndex = $('.tsf-nav-step>ul>li').length + 1;
        $('.tsf-nav-step>ul').append('<li  data-target="step-' + _newStepIndex + '">' +
            '<a href="#0">' +
            '<span class="number">' + _newStepIndex + '</span>' +
            '<span class="desc">' +
            '<label>Step ' + _newStepIndex + ' </label>' +
            '<span  class="">Step info ' + _newStepIndex + '</span>' +
            '</span>' +
            '</a>' +
            '</li>');

        $('.tsf-content').append('<div class="tsf-step step-' + _newStepIndex + '"><div class="tsf-step-content email-editor-elements-sortable"><div class="sortable-row"><div class="sortable-row-container"> <div class="sortable-row-actions"> <div class="row-move row-action"> <i class="fa fa-arrows-alt"></i> </div>	 <div class="row-duplicate row-action"><i class="fa fa-files-o"></i></div><div class="row-remove row-action"> <i class="fa fa-remove"></i> </div><div class="row-code row-action"> <i class="fa fa-code"></i> </div></div><div class="sortable-row-content" > <div class="main" data-types="background,text-style,padding" data-last-type="text-style" > <div class="element-content" contenteditable="true" style="padding:25px 10px;"> Drag eleemnts from the left menu for creating step form </div></div></div></div></div></div></div>');

        //  $('.bal-content-main').html('<div class="email-editor-elements-sortable ui-sortable"><div class="bal-elements-list-item ui-draggable ui-draggable-handle" style="width: auto; height: auto;"><div class="sortable-row"><div class="sortable-row-container"><div class="sortable-row-actions"><div class="row-move row-action"><i class="fa fa-arrows-alt"></i></div><div class="row-remove row-action"><i class="fa fa-remove"></i></div><div class="row-duplicate row-action"><i class="fa fa-files-o"></i></div></div><div class="sortable-row-content"><table class="main" width="100%" cellspacing="0" cellpadding="0" border="0" data-types="background,border-radius,text-style,padding" data-last-type="text-style" style="background-color:#FFFFFF" align="center"><tbody><tr><td class="element-content" align="left" style="padding-left:50px;padding-right:50px;padding-top:10px;padding-bottom:10px;font-family:Arial;font-size:13px;color:#000000;line-height:22px"><div contenteditable="true" class="test-text" style="text-align: center;"><span style="text-align: center;">Drag elements from left menu</span></div></td></tr></tbody></table></div></div></div></div></div>');
        makeSortable();
        changeFormSettings();
        loadSteps();
    });
    _menu.find('.bal-collapse').on('click', function() {
        _element = $(this);
        _dataValue = _element.attr('data-value');
        //console.log(_dataValue);
        if (_dataValue === 'closed') {
            $('.bal-left-menu-container').animate({
                width: 380
            }, 300, function() {
                $('.bal-elements').show();
                $('.bal-content').css({
                    'padding-left': '380px'
                });
                $('.bal-left-menu-container').find('.bal-menu-item:eq(0)').addClass('active');
            });
            _element.find('.bal-menu-name').show();
            _element.find('.fa').removeClass().addClass('fa fa-chevron-circle-left');
            _element.attr('data-value', 'opened');
        } else {
            $('.bal-left-menu-container').animate({
                width: 70
            }, 300, function() {
                $('.bal-elements').hide();
                $('.bal-left-menu-container').find('.bal-menu-item.active').removeClass('active');
            });
            $('.bal-content').css({
                'padding-left': '70px'
            });
            _element.find('.bal-menu-name').hide();
            _element.find('.fa').removeClass().addClass('fa fa-chevron-circle-right');
            _element.attr('data-value', 'closed');
        }
    });
    /*for selecting first tab first accordion*/
    /*drag drop*/

    _menu.find(".bal-elements-list .bal-elements-list-item").draggable({
        connectToSortable: ".email-editor-elements-sortable",
        helper: "clone",
        //revert: "invalid",
        create: function(event, ui) {
            //console.log(event.target);
        },
        drag: function(event, ui) {
            //console.log(ui.helper);
        },
        start: function(event, ui) {
            $(".bal-elements-container").css({
                'overflow': ''
            });
            ui.helper.find('.bal-preview').hide();
            ui.helper.find('.bal-view').show();
        },
        stop: function(event, ui) {
            //ui.helper.find('.demo').hide()
            //$(this).find('.demo').hide();
            //ui.helper.find('.configrutaion,.preview').hide()
            //console.log('ddd')
            $(".bal-elements-container").css({
                'overflow': 'hidden'
            });
            ui.helper.html(ui.helper.find('.bal-view').html());
            $('.email-editor-elements-sortable .bal-elements-list-item').css({
                'width': '',
                'height': ''
            });
            makeSortable();
            //  $('.email-editor-elements-sortable .bal-elements-list-item').removeClass('bal-elements-list-item ui-draggable ui-draggable-handle');
        }
    });
    makeSortable();
    /*elements*/
    //text style
    _menu.find('.bal-text-icons .bal-icon-box-item').on('click', function() {
        _element = $(this);
        if (_element.hasClass('active')) {
            _element.removeClass('active');
        } else {
            _element.addClass('active');
        }
        if (_menu.find('.bal-text-icons .bal-icon-box-item.fontStyle').hasClass('active')) {
            changeSettings('font-style', 'italic');
        } else {
            changeSettings('font-style', '');
        }
        _value = '';
        if (_menu.find('.bal-text-icons .bal-icon-box-item.underline').hasClass('active')) {
            _value += 'underline ';
        }
        if (_menu.find('.bal-text-icons .bal-icon-box-item.line').hasClass('active')) {
            _value += ' line-through';
        }
        changeSettings('text-decoration', _value);
    });
    _menu.find('.bal-font-icons .bal-icon-box-item').on('click', function() {
        _element = $(this);
        if (_element.hasClass('active')) {
            _element.removeClass('active');
        } else {
            _element.addClass('active');
        }
        if (_menu.find('.bal-font-icons .bal-icon-box-item').hasClass('active')) {
            changeSettings('font-weight', 'bold');
        } else {
            changeSettings('font-weight', '');
        }
    });
    _menu.find('.bal-align-icons .bal-icon-box-item').on('click', function() {
        _element = $(this);
        _menu.find('.bal-align-icons .bal-icon-box-item').removeClass('active');
        _element.addClass('active');
        _value = 'left';
        if (_menu.find('.bal-align-icons .bal-icon-box-item.center').hasClass('active')) {
            _value = 'center';
        }
        if (_menu.find('.bal-align-icons .bal-icon-box-item.right').hasClass('active')) {
            _value = 'right';
        }
        changeSettings('text-align', _value);
    });
    /* colorpicker */
    $('#bg-color').ColorPicker({
        color: '#fff',
        onChange: function(hsb, hex, rgb) {
            $('#bg-color').css('background-color', '#' + hex);
            changeSettings($('#bg-color').attr('setting-type'), '#' + hex);
        }
    });
    $('#text-color').ColorPicker({
        color: '#000',
        onChange: function(hsb, hex, rgb) {
            $('#text-color').css('background-color', '#' + hex);
            changeSettings($('#text-color').attr('setting-type'), '#' + hex);
        }
    });
    $('.bal-content-wrapper').click();
    _tabMenu('elements');



    tinymce.init({
        selector: 'div.bal-content-wrapper',
        theme: 'inlite',
        plugins: ' link',
        width: 300,
        selection_toolbar: 'fontselect fontsizeselect bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | link | unlink removeformat',
        fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt 72pt",
        inline: true,
        paste_data_images: false
    });

    jQuery('.sortable-row-actions').html('');
    jQuery('.sortable-row-actions').append('<div class="row-move row-action"><i class="fa fa-arrows-alt"></i></div>');
    jQuery('.sortable-row-actions').append('<div class="row-remove row-action"><i class="fa fa-remove"></i></div>');
    jQuery('.sortable-row-actions').append('<div class="row-duplicate row-action"><i class="fa fa-files-o"></i></div>');
    jQuery('.sortable-row-actions').append('<div class="row-code row-action"><i class="fa fa-code"></i></div>');


    $('.bal-content-wrapper').removeAttr('contenteditable');
    $('.step-form-style').change();
});
var _aceEditor;

function makeSortable() {
    $(".email-editor-elements-sortable").sortable({
        //revert: true,
        group: 'no-drop',
        handle: '.row-move'
        // ,scroll: false
        // , tolerance: "pointer"
        //,axis: "y"
    });
}
//change settings
$(document).on('keyup', '.bal-left-menu-container .form-control', function() {
    _element = $(this);
    if (_element.hasClass('all') && _element.hasClass('padding')) {
        _menu.find('.padding:not(".all")').val(_element.val());
    }
    if (_element.hasClass('all') && _element.hasClass('border-radius')) {
        _menu.find('.border-radius:not(".all")').val(_element.val());
    }
    if (typeof _element.attr('setting-type') !== "undefined") {
        changeSettings(_element.attr('setting-type'), _element.val() + 'px');
    }
});
$(document).on('change', '.bal-left-menu-container  .form-control', function() {
    _element = $(this);
    if (typeof _element.attr('setting-type') !== "undefined") {
        changeSettings(_element.attr('setting-type'), _element.val());
    }
    //  changeSettings(_element.attr('setting-type'), _element.val());
});
//her elementin edit olunan contenti varsa birbasa onun icin edit edey
$(document).on('click', '[contenteditable="true"]', function(event) {
    $('.bal-content-wrapper').removeClass('active');
    _content.find('[contenteditable="true"]').removeClass('element-contenteditable active')
    $(this).addClass('element-contenteditable active');
    _sortableClick($(this));
    //  _menuEditor($(this), event);
    event.stopPropagation();
});
$(document).on('click', '.element-content', function(event) {
    $('.bal-content-wrapper').removeClass('active');
    _content.find('[contenteditable="true"]').removeClass('element-contenteditable active');
    _sortableClick($(this));
    //_menuEditor($(this), event);
    event.stopPropagation();
});

function _sortableClick(_this) {
    _element = _this.parents('.sortable-row');
    //select current item
    _content.find('.sortable-row').removeClass('active')
    _element.addClass('active');
    _dataTypes = _element.find('.sortable-row-content .main').attr('data-types');
    if (_dataTypes.length < 1) {
        return;
    }
    _typeArr = _dataTypes.toString().split(',');
    _arrSize = _menu.find('.tab-property .bal-elements-accordion-item').length;
    for (var i = 0; i < _arrSize; i++) {
        _accordionMenuItem = _menu.find('.tab-property .bal-elements-accordion-item').eq(i);
        //console.log(_accordionMenuItem.attr('data-type'))
        if (_dataTypes.indexOf(_accordionMenuItem.attr('data-type')) > -1) {
            _accordionMenuItem.show();
        } else {
            _accordionMenuItem.hide();
        }
    }

    _tabMenu(_element.find('.sortable-row-content .main').attr('data-last-type'));

    if (_dataTypes.indexOf('input-text') > -1) {
        _dataInputType = _element.find('.sortable-row-content .main').attr('data-type');
        $('.input-text-item').hide();

        $('.input-text-item').each(function() {
            if ($(this).attr('data-type').indexOf(_dataInputType) > -1) {
                $(this).show();
            }
        });
        getInputSetting();
    }

    getSettings();
}
//left menu all items
const _tabMenuItems = {
    //elements tab
    'elements': {
        itemSelector: 'elements',
        parentSelector: 'tab-elements'
    },
    'media': {
        itemSelector: 'media',
        parentSelector: 'tab-elements'
    },
    'layout': {
        itemSelector: 'layout',
        parentSelector: 'tab-elements'
    },
    'button': {
        itemSelector: 'button',
        parentSelector: 'tab-elements'
    },
    'social': {
        itemSelector: 'social',
        parentSelector: 'tab-elements'
    },
    //property tab
    'background': {
        itemSelector: 'background',
        parentSelector: 'tab-property'
    },
    'border-radius': {
        itemSelector: 'border-radius',
        parentSelector: 'tab-property'
    },
    'text-style': {
        itemSelector: 'text-style',
        parentSelector: 'tab-property'
    },
    'padding': {
        itemSelector: 'padding',
        parentSelector: 'tab-property'
    },
    'youtube-frame': {
        itemSelector: 'youtube-frame',
        parentSelector: 'tab-property'
    },
    'hyperlink': {
        itemSelector: 'hyperlink',
        parentSelector: 'tab-property'
    },
    'image-settings': {
        itemSelector: 'image-settings',
        parentSelector: 'tab-property'
    },
    'input-text': {
        itemSelector: 'input-text',
        parentSelector: 'tab-property'
    },
    'step-property': {
        itemSelector: 'step-property',
        parentSelector: 'tab-property'
    },
    'step-form': {
        itemSelector: 'step-form',
        parentSelector: 'tab-step-form'
    },
    'social-content': {
        itemSelector: 'social-content',
        parentSelector: 'tab-property'
    }
}
//open/close menu
function _tabMenu(tab) {
    _menuItem = _tabMenuItems[tab];
    _tabMenuItem = $('.bal-left-menu-container .bal-menu-item[data-tab-selector="' + _menuItem.parentSelector + '"]');
    _accordionMenuItem = $('.bal-elements-accordion .bal-elements-accordion-item[data-type="' + _menuItem.itemSelector + '"] .bal-elements-accordion-item-title');
    _tabMenuItemClick(_tabMenuItem, true);
    _menuAccordionClick(_accordionMenuItem, false);
}
//left tab menu
function _tabMenuItemClick(_element, handle) {
    _tabSelector = _element.data('tab-selector');
    if (_element.hasClass('bal-collapse')) {
        return false;
    }
    _menu.find('.bal-menu-item.active').removeClass('active');
    _menu.find('.bal-element-tab.active').removeClass('active');
    //show tab content
    _menu.find('.' + _tabSelector).addClass('active');
    //select new tab
    _element.addClass('active');
    if (!handle) {
        _content.find('.sortable-row.active').removeClass('active');
    }
}
//left menu accordion
function _menuAccordionClick(_element, toggle) {
    var dropDown = _element.closest('.bal-elements-accordion-item').find('.bal-elements-accordion-item-content');
    _element.closest('.bal-elements-accordion').find('.bal-elements-accordion-item-content').not(dropDown).slideUp();
    if ($('.tab-property').hasClass('active')) {
        _content.find('.sortable-row.active .main').attr('data-last-type', _element.closest('.bal-elements-accordion-item').attr('data-type'));
    }
    if (!toggle) {
        _element.closest('.bal-elements-accordion').find('.bal-elements-accordion-item-title.active').removeClass('active');
        _element.addClass('active');
        dropDown.stop(false, true).slideDown();
    } else {
        if (_element.hasClass('active')) {
            _element.removeClass('active');
        } else {
            _element.closest('.bal-elements-accordion').find('.bal-elements-accordion-item-title.active').removeClass('active');
            _element.addClass('active');
        }
        dropDown.stop(false, true).slideToggle();
    }
}
//change every settings
function changeSettings(type, value) {
    _activeElement = getActiveElementContent();
    if (type == 'font-size') {
        _activeElement.find('>h1,>h4').css(type, value);
    } else if (type == 'background-image') {
        _activeElement.css(type, 'url("' + value + '")');
        _activeElement.css({
            'background-size': 'cover',
            'background-repeat': 'no-repeat'
        });
    }
    _activeElement.css(type, value);
}
//get active element of email content
function getActiveElementContent() {
    //_selector = '.main';
    //switch (dataType) {
    //    case 'background':
    //        _selector = '.main';
    //        break;
    //    case 'padding':
    //    case 'padding-left':
    //    case 'padding-right':
    //    case 'padding-top':
    //    case 'padding-bottom':
    //        _selector = '.element-content';
    //        break;
    //    default:
    //        _selector = '.main';
    //        break;
    //}
    _element = _content.find('.sortable-row.active .sortable-row-content .element-content');
    //element-contenteditable active
    if (_element.find('[contenteditable="true"]').hasClass('element-contenteditable')) {
        _element = _element.find('.element-contenteditable.active');
    }
    if ($('.bal-content-wrapper').hasClass('active')) {
        _element = $('.bal-content-wrapper');
    }
    return _element;
}
//get active element settings
function getSettings() {
    _element = getActiveElementContent();
    _style = _element.attr('style');
    if (typeof _style === "undefined" || _style.length < 1) {
        return;
    }
    //background
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="background-color"]').css('background-color', _element.css('background-color'));
    /*Paddings*/
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="padding-top"]').val(_element.css('padding-top').replace('px', ''));
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="padding-bottom"]').val(_element.css('padding-bottom').replace('px', ''));
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="padding-left"]').val(_element.css('padding-left').replace('px', ''));
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="padding-right"]').val(_element.css('padding-right').replace('px', ''));
    /*Border radius*/
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="border-top-left-radius"]').val(_element.css('border-top-left-radius').replace('px', ''));
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="border-top-right-radius"]').val(_element.css('border-top-right-radius').replace('px', ''));
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="border-bottom-left-radius"]').val(_element.css('border-bottom-left-radius').replace('px', ''));
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="border-bottom-right-radius"]').val(_element.css('border-bottom-right-radius').replace('px', ''));
    /*text style*/
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="font-family"]').val(_element.css('font-family'));
    _menu.find('.tab-property .bal-elements-accordion-item [setting-type="font-size"]').val(_element.css('font-size').replace('px', ''));
    //text color
    _menu.find('.tab-property .bal-icon-box-item[setting-type="color"]').css({
        'background': _element.css('color')
    });
    //text align
    _menu.find('.tab-property .bal-align-icons .bal-icon-box-item').removeClass('active');
    _menu.find('.tab-property .bal-align-icons .bal-icon-box-item.' + _element.css('text-align')).addClass('active');
    //text bold
    if (_element.css('font-weight') == 'bold') {
        _menu.find('.tab-property .bal-icon-box-item[setting-type="bold"]').addClass('active');
    } else {
        _menu.find('.tab-property .bal-icon-box-item[setting-type="bold"]').removeClass('active');
    }
    //text group style
    _menu.find('.tab-property .bal-text-icons .bal-icon-box-item').removeClass('active');
    if (_element.css('text-decoration').indexOf('underline') > -1) {
        _menu.find('.tab-property .bal-text-icons .bal-icon-box-item.underline').addClass('active');
    }
    if (_element.css('text-decoration').indexOf('line-through') > -1) {
        _menu.find('.tab-property .bal-text-icons .bal-icon-box-item.line').addClass('active');
    }
    if (_element.css('font-style').indexOf('italic') > -1) {
        _menu.find('.tab-property .bal-text-icons .bal-icon-box-item.fontStyle').addClass('active');
    }
    //_arr = _style.split(';');
    //for (var i = 0; i < _arr.length; i++) {
    //    _arrStyle = _arr[i].split(':');
    //    _item = _menu.find('.tab-property .bal-elements-accordion-item [setting-type="' + _arrStyle[0] + '"]');
    //    if (_item.is('input')) {
    //        _item.val(_arrStyle[1].replace('px', ''));
    //        console.log(_arrStyle[0]+'-'+_arrStyle[1].replace('px', ''))
    //    } else {
    //        _item.css(_arrStyle[0], _arrStyle[1]);
    //    }
    //    //.val(_arr[i].split(':')[1]);
    //}
    if (_element.hasClass('social-content')) {
        $('.bal-content .sortable-row.active .sortable-row-content .element-content.social-content a').each(function() {
            _socialType = $(this).attr('class');
            _socialRow = _menu.find('[data-social-type="' + _socialType + '"]');
            _socialRow.find('.social-input').val($(this).attr('href'));
            if ($(this).css('display') == 'none') {
                _socialRow.find('.checkbox-title input').prop("checked", false);
            } else {
                _socialRow.find('.checkbox-title input').prop("checked", true);
            }
        });
    }
    if (_element.hasClass('youtube-frame')) {
        _ytbUrl = _element.find('iframe').attr('src');
        _menu.find('.youtube').val(_ytbUrl.split('/')[4]);
    }

    //hyperlink
    if (_element.hasClass('hyperlink')) {
        _href = _element.attr('href');
        _menu.find('.hyperlink-url').val(_href);
    }
    //image size
    _menu.find('.tab-property .bal-elements-accordion-item .image-width').val(_element.find('.content-image').css('width'));
    _menu.find('.tab-property .bal-elements-accordion-item .image-height').val(_element.find('.content-image').css('height'));

}
$(document).on('keyup', '.image-size', function() {


    //  image-width number image-size
    _activeElement = getActiveElementContent();


    if ($(this).hasClass('image-height')) {
        console.log($(this).val() + ' width');
        _activeElement.find('.content-image').css('height', $(this).val());
    } else if ($(this).hasClass('image-width')) {
        console.log($(this).val() + ' width');
        _activeElement.find('.content-image').css('width', $(this).val());
    }

});
$(document).on('keydown', '.number', function(e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
        //// Allow: Ctrl+A
        //(e.keyCode == 65 && e.ctrlKey === true) ||
        //// Allow: Ctrl+C
        //(e.keyCode == 67 && e.ctrlKey === true) ||
        //// Allow: Ctrl+X
        //(e.keyCode == 88 && e.ctrlKey === true) ||
        //// Allow: home, end, left, right
        //(e.keyCode >= 35 && e.keyCode <= 39)
    ) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});
$(document).on('keyup', '.bal-social-content-box .social-input', function() {
    _element = $(this);
    _socialType = _element.parents('.row').attr('data-social-type');
    _val = _element.val();
    _activeElement = getActiveElementContent();
    if (_activeElement.hasClass('social-content')) {
        _activeElement.find('a.' + _socialType).attr('href', _val);
    }
});
$(document).on('change', '.bal-social-content-box .checkbox-title input', function() {
    _socialType = $(this).parents('.row').attr('data-social-type');
    _activeElement = getActiveElementContent();
    if ($(this).is(":checked")) {
        _activeElement.find('a.' + _socialType).show();
    } else {
        _activeElement.find('a.' + _socialType).hide();
    }
});
//bu silinecey, popup acilib icinde sekil yuklemey olacag
$(document).on('click', '.bg-image', function() {
    changeSettings($(this).attr('setting-type'), 'assets/images/demo.jpg');
});
/*for remove action*/
$(document).on('click', '.sortable-row .row-remove', function() {
    if (_content.find('.sortable-row').length == 1) {
        alert('At least should be 1 item')
        return;
    }
    $(this).parents('.sortable-row').remove();
});
/*for inline edit action*/
$(document).on('click', '.sortable-row .row-code', function() {
    $(this).parents('.sortable-row').addClass('code-editor');
    _html = $(this).parents('.sortable-row').find('.sortable-row-content').html();
    _aceEditor.session.setValue(_html);
    $('#popup_editor').modal('show');
});

/*for dublicate action*/
$(document).on('click', '.sortable-row .row-duplicate', function() {
    if ($(this).hasParent('.bal-elements-list-item')) {
        _parentSelector = '.bal-elements-list-item';
    } else {
        _parentSelector = '.sortable-row';
    }
    _parent = $(this).parents(_parentSelector);
    $('.sortable-row').removeClass('active');
    $('.bal-elements-list-item').removeClass('active');
    _parent.addClass('active');
    //_parent.after('<div class="sortable-row">'+ _parent.html()+"</div>");
    _parent.clone().insertAfter(_parentSelector + '.active');
});
$.fn.hasParent = function(e) {
    return ($(this).parents(e).length == 1 ? true : false);
}
$(document).on('click', '.bal-setting-item.other-devices', function() {
    _parent = $(this).parents('.bal-settings');
    if ($(this).hasClass('active')) {
        _parent.find('.bal-setting-content .bal-setting-content-item.other-devices').hide();
        $(this).removeClass('active');
    } else {
        _parent.find('.bal-setting-content .bal-setting-content-item.other-devices').show();
        $(this).addClass('active');
    }
});
$(document).on('click', '.bal-setting-content .bal-setting-device-tab', function() {
    _parent = $(this).parents('.bal-setting-content');
    _parent.find('.bal-setting-device-tab').removeClass('active');
    $(this).addClass('active');
    _parent.find('.bal-setting-device-content').removeClass('active');
    _parent.find('.bal-setting-device-content.' + $(this).attr('data-tab')).addClass('active');
    _removeClass = 'sm-width lg-width';
    _addClass = '';
    switch ($(this).attr('data-tab')) {
        case 'mobile-content':
            _addClass = 'sm-width';
            break;
        case 'desktop-content':
            _addClass = 'lg-width';
            break;
    }
    _content.find('.bal-content-main').removeClass(_removeClass);
    _content.find('.bal-content-main').addClass(_addClass);
});
$(document).on('click', '.bal-content-wrapper', function() {
    _content.find('.sortable-row.active').removeClass('active');
    _content.find('.sortable-row .element-contenteditable.active').removeClass('.element-contenteditable .active');
    $(this).addClass('active');
    _dataTypes = $(this).attr('data-types');
    if (_dataTypes.length < 1) {
        return;
    }
    _typeArr = _dataTypes.toString().split(',');
    _arrSize = _menu.find('.tab-property .bal-elements-accordion-item').length;
    for (var i = 0; i < _arrSize; i++) {
        _accordionMenuItem = _menu.find('.tab-property .bal-elements-accordion-item').eq(i);
        //console.log(_accordionMenuItem.attr('data-type'))
        if (_dataTypes.indexOf(_accordionMenuItem.attr('data-type')) > -1) {
            _accordionMenuItem.show();
        } else {
            _accordionMenuItem.hide();
        }
    }
    _menu.find('[setting-type="padding-top"]').val($(this).css('padding-top').replace('px', ''));
    _menu.find('[setting-type="padding-bottom"]').val($(this).css('padding-bottom').replace('px', ''));
    _menu.find('[setting-type="padding-left"]').val($(this).css('padding-left').replace('px', ''));
    _menu.find('[setting-type="padding-right"]').val($(this).css('padding-right').replace('px', ''));

    _menu.find('.email-width').val($('.bal-content-main').width());

    _tabMenu(_typeArr[0]);
});
$(document).on('keyup', '.bal-elements-accordion-item-content .youtube', function() {
    _element = $(this);
    _val = _element.val();
    _activeElement = getActiveElementContent();
    _activeElement.find('iframe').attr('src', 'https://www.youtube.com/embed/' + _val);
});
$(document).on('keyup', '.bal-elements-accordion-item-content .hyperlink-url', function() {
    _element = $(this);
    _val = _element.val();
    _activeElement = getActiveElementContent();
    _activeElement.attr('href', _val);
});
String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
};



//for export email form
$(document).on('click', '.bal-setting-item.export', function() {
    _settingsClick('export');
    //$('#popup_demo').modal('show');
});
//for creating preview html
$(document).on('click', '.bal-setting-item.preview', function() {
    _settingsClick('preview');
});

function getContentHtml() {
    //  _html = '';//$('.bal-content-main').html();

    $('body').append('<div class="export-content" style="display:none">' + $('.bal-content-main').html() + '<div>')

    $('.export-content .sortable-row').each(function() {
        _html = $(this).find('.element-content').parent().html().replaceAll('contenteditable="true"', '');

        $(this).parents('.tsf-step-content').append(_html);
        $(this).parents('.bal-elements-list-item').remove();
        $(this).remove();
    });
    //  _width = $('.bal-content-main').css('width');
    _style = '';
    _style += 'background:' + $('.bal-content-wrapper').css('background') + ';';
    _style += 'padding:' + $('.bal-content-wrapper').css('padding');
    //  _result = '<div class="email-content" style="' + _style + '">' + _html + '</div>';

    _html = $('.export-content').html();
    $('.export-content').remove();
    return _html;
}

function getStepFormScript() {
    _height = $('.step-form-height').val();
    _navPos = $('.step-form-nav-pos').val();
    _stepEffect = $('.step-form-effect').val();
    _style = $('.step-form-style').val();
    _showNum = $('.step-form-show-number').is(':checked') == true ? true : false;
    _nextText = $('.step-form-next').val();
    _prevText = $('.step-form-prev').val();
    _finishText = $('.step-form-finish').val();

    _transition = $('.step-form-transition').is(':checked') == true ? true : false;

    return "$('.tsf-wizard-1').tsfWizard({\n" +
        "stepEffect:'" + _stepEffect + "',\n" +
        "stepStyle:'" + _style + "',\n" +
        "navPosition:'" + _navPos + "',\n" +
        "validation:false,\n" +
        "stepTransition:" + _transition + ",\n" +
        "showButtons:true,\n" +
        "showStepNum:" + _showNum + ",\n" +
        "height:'" + _height + "',\n" +
        "prevBtn:'" + '<i class="fa fa-chevron-left"></i> ' + _prevText + "',\n" +
        "nextBtn:'" + _nextText + " <i class=\"fa fa-chevron-right\"></i>',\n" +
        "finishBtn:'" + _finishText + "',\n" +
        "disableSteps:'none',\n" +
        "onBeforeNextButtonClick:function (e, validation) {\n" +
        "   console.log('onBeforeNextButtonClick');\n" +
        "   console.log(validation);\n" +
        "   //for return please write below code\n" +
        "   //  e.preventDefault();\n" +
        "},\n" +
        "onAfterNextButtonClick:function (e, from, to, validation) {\n" +
        "   console.log('onAfterNextButtonClick');\n" +
        "},\n" +
        "onBeforePrevButtonClick:function (e, from, to) {\n" +
        "   console.log('onBeforePrevButtonClick');\n" +
        "   console.log('from '+from+' to '+to);\n" +
        "//  e.preventDefault();\n" +
        "},\n" +
        "onAfterPrevButtonClick:function (e, from, to) {\n" +
        "   console.log('onAfterPrevButtonClick');\n" +
        "   console.log('validation '+from+' to '+to);\n" +
        "},\n" +
        "onBeforeFinishButtonClick:function (e, validation) {\n" +
        "   console.log('onBeforeFinishButtonClick');\n" +
        "   console.log('validation '+validation);\n" +
        "   //e.preventDefault();\n" +
        "},\n" +
        "onAfterFinishButtonClick:function (e, validation) {\n" +
        "   console.log('onAfterFinishButtonClick');\n" +
        "   console.log('validation '+validation);\n" +
        "}\n" +
        "});";
}

function _settingsClick(settingsType) {
    _result = getContentHtml();
    _js_script = getStepFormScript();
    $.ajax({
        url: 'export.php',
        type: 'POST',
        data: {
            html: _result,
            js_script: _js_script
        },
        dataType: 'json',
        success: function(data) {
            if (data.code == -5) {
                $('#popup_demo').modal('show');
                return;
            } else if (data.code == 0) {
                if (settingsType == 'export') {
                    window.location.href = data.url;
                }
                if (settingsType == 'preview') {
                    var win = window.open(data.preview_url, '_blank');
                    if (win) {
                        //Browser has allowed it to be opened
                        win.focus();
                    } else {
                        //Browser has blocked it
                        alert('Please allow popups for this website');
                    }
                }
            }
        },
        error: function() {}
    });
}
//for opening modal
$(document).on('click', '.change-image', function(e) {
    loadImages();
    $('#popup_images').modal('show');
    // _content.find('.content-image').removeClass('active');
    // $(this).addClass('active');
});
//when popup image click
$(document).on('click', '.upload-image-item', function() {
    $('.modal .upload-image-item').removeClass('active');
    $(this).addClass('active');
});
$(document).on('click', '.btn-select', function() {
    _url = $('.modal').find('.upload-image-item.active').attr('src');
    // _content.find('.content-image.active').attr('src', _url);
    // _content.find('.content-image.active').removeClass('active');
    getActiveElementContent().find('.content-image').attr('src', _url);

    $('#popup_images').modal('hide');
});
//upload image to server
$(document).on('click', '.modal .btn-upload', function() {
    var file_data = $('.input-file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    $.ajax({
        url: 'upload.php', // point to server-side PHP script
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(php_script_response) {
            loadImages();
        }
    });
});
//load all uploads files from server
function loadImages() {
    /*<div class="col-sm-4">
      <img src="fd" alt='' data-url=''>
     </div>*/
    $.ajax({
        url: 'get-files.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.code == 0) {
                _output = '';
                for (var k in data.files) {
                    if (typeof data.files[k] !== 'function') {
                        _output += "<div class='col-sm-3'>" +
                            "<img class='upload-image-item' src='" + data.directory + data.files[k] + "' alt='" + data.files[k] + "' data-url='" + data.directory + data.files[k] + "'>" +
                            "</div>";
                        // console.log("Key is " + k + ", value is" + data.files[k]);
                    }
                }
                $('.upload-images').html(_output);
            }
        },
        error: function() {}
    });
}
var _templateListItems;

function loadTemplates() {
    $('.template-list').html('<div style="text-align:center">Loading...</div>');
    $.ajax({
        url: 'load_templates.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.code == 0) {
                _templateItems = '';
                _templateListItems = data.files;
                for (var i = 0; i < data.files.length; i++) {
                    _templateItems += '<div class="template-item" data-id="' + data.files[i].id + '">' +
                        '<div class="template-item-icon">' +
                        '<i class="fa fa-file-text-o"></i>' +
                        '</div>' +
                        '<div class="template-item-name">' +
                        data.files[i].name +
                        '</div>' +
                        '</div>';
                }
                $('.template-list').html(_templateItems);
            } else if (data.code == 1) {
                $('.template-list').html('<div style="text-align:center">No items</div>');
            }
        },
        error: function() {}
    });
}
// $(document).on('click', '.btn-save-template', function() {
//     $('.input-error').text('');
//     if ($('.template-name').val().length < 1) {
//         $('.input-error').text('Template name required');
//         return false;
//     }
//     $.ajax({
//         url: 'save_template.php',
//         type: 'POST',
//         //dataType: 'json',
//         data: {
//             name: $('.template-name').val(),
//             content: $('.bal-content-wrapper').html()
//         },
//         success: function(data) {
//             //  console.log(data);
//             if (data === 'ok') {
//                 $('#popup_save_template').modal('hide');
//             } else {
//                 $('.input-error').text('Problem in server');
//             }
//         },
//         error: function(error) {
//             $('.input-error').text('Internal error');
//         }
//     });
// });
$(document).on('click', '.template-list .template-item', function() {
    $('.template-list .template-item').removeClass('active');
    $(this).addClass('active');
    $('.btn-load-template').show();
});
// $(document).on('click', '.btn-load-template', function() {
//     _dataId = $('.template-list .template-item.active').attr('data-id');
//     //search template in array
//     var result = $.grep(_templateListItems, function(e) {
//         return e.id == _dataId;
//     });
//     if (result.length == 0) {
//         //show error
//         $('.template-load-error').text('An error has occurred');
//     }
//     _contentText = $('<div/>').html(result[0].content).text();
//     $('.bal-content-wrapper').html(_contentText);
//     $('#popup_load_template').modal('hide');
//     makeSortable();
// });


$(document).on('keyup', '.email-width', function() {
    _element = $(this);
    _val = $('.email-width').val();
    if (parseInt(_val) < 300 || parseInt(_val) > 1000) {
        return false;
    }
    $('.bal-content-main').css('width', _val + 'px');
});
$(document).on('click', '.btn-save-editor', function() {
    $('.sortable-row.code-editor .sortable-row-content').html(_aceEditor.getSession().getValue());
    $('#popup_editor').modal('hide');
    $('.sortable-row.code-editor').removeClass('code-editor');
});


$(document).on('click', '.tsf-nav-step .current', function(e) {

    $('.step-form-input[data-type="main-text"]').val($(this).find('.desc label').text());
    $('.step-form-input[data-type="sub-text"]').val($(this).find('.desc span').html());

    if ($(this).hasClass('gsi-hide-number') == false) {
        $('.step-form-number').attr('checked', false);
        if ($(this).hasClass('gsi-show-number') == false) {
            $('.step-form-number').attr('checked', true);
        }
    } else {
        $('.step-form-number').attr('checked', true);
    }

    _dataTypes = 'step-property';
    _arrSize = _menu.find('.tab-property .bal-elements-accordion-item').length;
    for (var i = 0; i < _arrSize; i++) {
        _accordionMenuItem = _menu.find('.tab-property .bal-elements-accordion-item').eq(i);
        if (_dataTypes.indexOf(_accordionMenuItem.attr('data-type')) > -1) {
            _accordionMenuItem.show();
        } else {
            _accordionMenuItem.hide();
        }
    }

    _tabMenu('step-property');
    e.stopPropagation();

});

$(document).on('keypress', '.step-form-input', function() {
    changeStepSetting($(this));
});
$(document).on('change', '.step-form-input', function() {
    changeStepSetting($(this));
});
$(document).on('change', '.step-form-number', function() {
    if ($(this).is(':checked') === true) {
        $('.tsf-nav-step li.current').addClass('gsi-show-number').removeClass('gsi-hide-number');
    } else {
        $('.tsf-nav-step li.current').addClass('gsi-hide-number').removeClass('gsi-show-number');
    }
});

$(document).on('change', '.step-form-subtext', function() {
    if ($(this).is(':checked') === true) {
        $('.tsf-nav-step li.current').addClass('gsi-show-subtext').removeClass('gsi-hide-subtext');
    } else {
        $('.tsf-nav-step li.current').addClass('gsi-hide-subtext').removeClass('gsi-show-subtext');
    }
});

function changeStepSetting(step) {

    switch (step.attr('data-type')) {
        case 'main-text':
            $('.tsf-nav-step li.current .desc label').text(step.val());
            break;

        case 'sub-text':
            $('.tsf-nav-step li.current .desc span').text(step.val());
            break;
    }
}


$(document).on('change', '.step-form-height', function() {
    changeFormSettings();

});
$(document).on('keydown', '.step-form-height', function() {
    changeFormSettings();
});
$(document).on('change', '.step-form-effect', function() {
    changeFormSettings();
});
$(document).on('change', '.step-form-style', function(e) {
    changeFormSettings();

});
$(document).on('change', '.step-form-nav-pos', function() {
    changeFormSettings();
});
$(document).on('change', '.step-form-show-number', function() {
    changeFormSettings();
});


$(document).on('change', '.step-form-next', function() {
    changeFormSettings();
});
$(document).on('change', '.step-form-prev', function() {
    changeFormSettings();
});
$(document).on('change', '.step-form-finish', function() {
    changeFormSettings();
});

$(document).on('keypress', '.step-form-next', function() {
    changeFormSettings();
});
$(document).on('keypress', '.step-form-prev', function() {
    changeFormSettings();
});
$(document).on('keypress', '.step-form-finish', function() {
    changeFormSettings();
});

function changeFormSettings() {
    _height = $('.step-form-height').val();
    _navPos = $('.step-form-nav-pos').val();
    _stepEffect = $('.step-form-effect').val();
    _style = $('.step-form-style').val();
    _showNum = $('.step-form-show-number').is(':checked') == true ? true : false;
    _nextText = $('.step-form-next').val();
    _prevText = $('.step-form-prev').val();
    _finishText = $('.step-form-finish').val();

    _transition = $('.step-form-transition').is(':checked') == true ? true : false;

    $('.tsf-wizard').removeClass('top left bottom right');
    $('.tsf-container').removeClass(' tsf-bottom-container tsf-left-container tsf-right-container tsf-top-container');
    $('.tsf-nav-step').removeClass('tsf-left-nav-step').removeClass('tsf-right-nav-step').removeClass('tsf-bottom-nav-step').removeClass('tsf-top-nav-step');

    $('.tsf-wizard-1').tsfWizard({
        stepEffect: _stepEffect,
        stepStyle: _style,
        navPosition: _navPos,
        validation: false,
        stepTransition: _transition,
        showButtons: true,
        showStepNum: _showNum,
        height: _height,
        prevBtn: '<i class="fa fa-chevron-left"></i> ' + _prevText,
        nextBtn: _nextText + ' <i class="fa fa-chevron-right"></i>',
        finishBtn: _finishText,
        disableSteps: 'none'
    });

    _tabMenu('step-form');
    makeSortable();
}

function getInputSetting() {
    _element = getActiveElementContent();
    _input = _element.find('[data-type="input"]');
    _label = _element.find('[data-type="label"]');
    _help = _element.find('[data-type="help"]');

    $('.input-id').val(_input.attr('id'));
    $('.input-name').val(_input.attr('name'));
    $('.input-text-type').val(_input.attr('type'));
    $('.input-label').val(_label.text());
    $('.input-placeholder').val(_input.attr('placeholder'));
    $('.input-value').val(_input.val());
    $('.input-help-text').val(_help.text());
    $('.input-field-size').val(_input.attr('rows'));
    $('.input-css-class').val(_input.attr('class'));
    $('.input-label-css-class').val(_element.find('.col-form-label').attr('class'));

    $('.input-accept').val(_input.attr('accept'));

    _attr = _input.attr('required');
    if (typeof _attr !== typeof undefined && _attr !== false) {
        $('.input-required').attr('checked', 'checked');
    } else {
        $('.input-required').removeAttr('checked');
    }


    _attr = _input.attr('disabled');
    if (typeof _attr !== typeof undefined && _attr !== false) {
        $('.input-disabled').attr('checked', 'checked');
    } else {
        $('.input-disabled').removeAttr('checked');
    }

    _attr = _input.attr('readonly');
    if (typeof _attr !== typeof undefined && _attr !== false) {
        $('.input-readonly').attr('checked', 'checked');
    } else {
        $('.input-readonly').removeAttr('checked');
    }

    _attr = _input.attr('multiple');
    if (typeof _attr !== typeof undefined && _attr !== false) {
        $('.input-file-multi').attr('checked', 'checked');
    } else {
        $('.input-file-multi').removeAttr('checked');
    }
}
$(document).on('keypress', '.input-text-control', function(e) {
    changeInputSettings($(this));
});
$(document).on('keydown', '.input-text-control', function(e) {
    changeInputSettings($(this));
});
$(document).on('change', '.input-text-control', function(e) {
    changeInputSettings($(this));
});
$(document).on('keyup', '.input-text-control', function(e) {
    changeInputSettings($(this));
});

function changeInputSettings(_element) {
    _activeElement = getActiveElementContent();
    _input = _activeElement.find('[data-type="input"]');
    _label = _activeElement.find('[data-type="label"]');
    _help = _activeElement.find('[data-type="help"]');

    switch (_element.attr('data-input-type')) {

        case 'input-name':
            _input.attr('name', _element.val());
            break;

        case 'input-id':
            _input.attr('id', _element.val());
            break;

        case 'value':
            _input.val(_element.val());
            break;
        case 'type':
            _input.attr('type', _element.val());
            break;

        case 'label':
            _label.text(_element.val());
            break;

        case 'placeholder':
            _input.attr('placeholder', _element.val());
            break;

        case 'input-class':
            _input.attr('class', _element.val());
            break;

        case 'label-class':
            _label.attr('class', _element.val());
            break;

        case 'help-text':
            _help.text(_element.val());
            break;

        case 'min-number':
            _input.attr('min', _element.val());
            break;

        case 'max-number':
            _input.attr('max', _element.val());
            break;

        case 'step-number':
            _input.attr('step', _element.val());
            break;

        case 'rows':
            _input.attr('rows', _element.val());
            break;

        case 'date-type':
            _input.attr('type', _element.val());
            break;

        case 'min-date':
            _input.attr('min', _element.val());
            break;

        case 'max-date':
            _input.attr('max', _element.val());
            break;

        case 'required':
            _val = _element.is(':checked') == true ? true : false;
            if (_val) {
                _input.attr('required', 'required');
                _input.addClass('required');
                _activeElement.find('.form-group').addClass('required');
            } else {
                _input.removeAttr('required');
                _input.removeClass('required');
                _activeElement.find('.form-group').removeClass('required');
            }
            break;

        case 'readonly':
            _val = _element.is(':checked') == true ? true : false;
            if (_val) {
                _input.attr('readonly', 'readonly');
            } else {
                _input.removeAttr('readonly');
            }
            break;

        case 'disabled':
            _val = _element.is(':checked') == true ? true : false;
            if (_val) {
                _input.attr('disabled', 'disabled');
            } else {
                _input.removeAttr('disabled');
            }
            break;

        case 'multi-file':
            _val = _element.is(':checked') == true ? true : false;
            if (_val) {
                _input.attr('multiple', 'multiple');
            } else {
                _input.removeAttr('multiple');
            }
            break;

        case 'accept':
            _input.attr('accept', _element.val());
            break;

        case 'checkbox':
            _activeElement.find('.multiple').html('');
            _items = _element.val().split('\n');
            for (var i = 0; i < _items.length; i++) {
                _values = _items[i].split('|');
                if (_values[0].length > 0) {
                    _activeElement.find('.multiple').append('<label><input type="checkbox" data-type="input" ' + (_values[1] == 'check' ? 'checked' : '') + '>' + _values[0] + '</label><br/>');
                }
            }
            break;

        case 'select':
            _input.html('');
            _items = _element.val().split('\n');
            for (var i = 0; i < _items.length; i++) {
                _values = _items[i].split('|');
                if (_values[0].length > 0) {
                    _input.append('<option ' + (_values[1] == 'select' ? 'selected' : '') + '>' + _values[0] + '</option>');
                }
            }
            break;

        case 'radio':
            _activeElement.find('.multiple').html('');
            _items = _element.val().split('\n');
            for (var i = 0; i < _items.length; i++) {
                _values = _items[i].split('|');
                if (_values[0].length > 0) {
                    _activeElement.find('.multiple').append('<label><input type="radio" data-type="input" ' + (_values[1] == 'select' ? 'checked' : '') + '>' + _values[0] + '</label><br/>');
                }
            }
            break;
    }
}
//

$(document).on('click', '[data-type="input"]', function() {
    $(this).blur();
});
$(document).on('focusin', '[data-type="input"]', function() {
    $(this).blur();
});
// $('[data-type="input"]').focusin(function() {
//     $(this).blur();
// });
