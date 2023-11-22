var onResult = function (form, fields, response, error) {
    if (error) {
    }
    else {
        window.location.href = response.data.redirect;
    }
};
//var init = function () {
function getChild(id) {
    var parentId = $(id).parents(".question_tree").attr("id");
    if (id.value == 2 || id.value == 5) {
        $("#" + parentId).find(".add").attr("onclick", 'addLabelsQue(this);');
        $("#" + parentId).find("li").remove();
        $("#" + parentId).find(".add").click();
    } else {
        $("#" + parentId).find(".add").attr("onclick", 'addLabels(this);');
        $("#" + parentId).find(".lrm").remove();
    }


}

var counters = 0;
function addLabels(obj) {


    var res = (obj.id).replace("add", "");


    // e.preventDefault();
    var parentId = $(obj).parents(".question_tree").attr("id");
    var name = $("#" + parentId).find("textarea.qt").prop("name");

    var lbel = name.replace('[q]', '');

    // $('<div/>').addClass( 'new-text-div' )
    // var count=parseInt($('#count').val());
    //var subcount=parseInt($('#subcounter').val()) + 1;
    //$('#subcounter').val(subcount);
    //$('#count').val(0);
    var QuestionVal = $("#" + parentId).find(".qt").val();
    if (QuestionVal == '' || QuestionVal == ' ') {
        $("#" + parentId).find("#error").show();
        return false;
    } else {
        $("#" + parentId).find("#error").hide();
    }

    var myid1 = parseInt($("#" + parentId).find("#lbcount").attr("value")) + 1;
    $("#" + parentId).find("#lbcount").attr("value", myid1);
    var myid = res + '' + myid1;
    if (myid.length > 16) {
        myid = myid + 't';
    };
    var label_name = lbel + '[opt][' + myid1 + '][val]';

    $("#" + parentId).children(".new-text-div").append($('<li class="lrm' + myid + '"><a href="#" onclick="return false;"><div class="row form-group rm"><input type="hidden" value="0" id="qscount' + myid + '"><input type="textbox" name="' + label_name + '" id="label" class="form-control col-md-7 offset-md-1"/> <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove" id=' + myid + '></i><i class="col-md-1 offset-md-1 i-Add  as" id="addSubQuestion" onclick=addSubQuestion("' + myid + '","' + label_name + '","' + parentId + '")  style="color: #2cb8ea;  font-size: 22px;" ></i> </div><div class="row"><span id="lberror" class="col-md-12" style="display:none;color:red">Please enter label</span></div></a><ul class="abc" id="appendsub_div' + myid + '""></ul></li>'));
    var otherValue = $("#other_amount").val();
    // count++;
}
function addLabelsQue(obj) {


    var res = (obj.id).replace("add", "");


    // e.preventDefault();
    var parentId = $(obj).parents(".question_tree").attr("id");
    var name = $("#" + parentId).find("textarea.qt").prop("name");

    var lbel = name.replace('[q]', '');

    // $('<div/>').addClass( 'new-text-div' )
    // var count=parseInt($('#count').val());
    //var subcount=parseInt($('#subcounter').val()) + 1;
    //$('#subcounter').val(subcount);
    //$('#count').val(0);

    var myid1 = parseInt($("#" + parentId).find("#lbcount").attr("value")) + 1;
    $("#" + parentId).find("#lbcount").attr("value", myid1);
    var myid = res + '' + myid1;
    if (myid.length > 16) {
        myid = myid + 't';
    };
    var label_name = lbel + '[opt][' + myid1 + '][val]';

    $("#" + parentId).children(".new-text-div").append($('<li class="lrm"><a href="#" onclick="return false;"><div class="row form-group rm"><input type="hidden" value="0" id="qscount' + myid + '"><input type="hidden" name="' + label_name + '" value="default yes" id="label" class="form-control col-md-7 offset-md-1"/> </div><div class="row"><span id="lberror" class="col-md-12" style="display:none;color:red">Please enter question</span></div></a><ul class="abc" id="appendsub_div' + myid + '""></ul></li>'));
    var otherValue = $("#other_amount").val();
    addSubQuestion(myid, label_name, parentId);
    // count++;
}

function empty() {
    if ($("#content_title").val() == '' || $("#content_title").val() == ' ') {
        $('.cerror').show();
        return false;
    } else {
        $('.cerror').hide();
    }
    if ($('#module').val() == 0) {
        $('.merror').show();
        return false;
    } else { $('.merror').hide(); }
    if ($('#sub_module').val() == 'Select Sub Module') {
        $('.smerror').show();
        return false;
    } else { $('.smerror').hide(); }
    if ($('#stages').val() == 'Select Stage') {
        $('.stageerror').show();
        return false;
    } else { $('.stageerror').hide(); }
    if ($('#stage_code').val() == 'Select Stage Code') {
        $('.steperror').show();
        return false;
    } else { $('.steperror').hide(); }

    var valid = true;
    $(".qt").each(function () {
        var parentId = $(this).parents(".question_tree").attr("id");
        if (this.value == '' && $('#' + parentId).parents(".lrm").find('#label').attr("value") != 'default yes') {
            //alert($('#'+parentId).parents(".lrm").find('#label').attr("value"));
            $("#" + parentId).find("#error").show();
            valid = false;
        } else { $("#" + parentId).find("#error").hide(); }
    })
    $("form input[type='textbox']").each(function () {
        var pId = $(this).parents("li").attr("class");
        if (this.value == '') {
            $("." + pId).find("#lberror").show();
            valid = false;
        } else { $("." + pId).find("#lberror").hide(); }
    })
    return valid;

}
/*
function addSubLabels(obj){   
           // e.preventDefault();
           var parentId = $(obj).parents(".question_tree").attr("id");
           var res = (obj.id).replace("add", ""); 
          // alert(parentId);
           // $('<div/>').addClass( 'new-text-div' )
           var count=parseInt($('#subcounter').val());
            $("#"+parentId).children(".new-text-div").append( $('<li class="lrm"><a href="#"><div class="row form-group rm"><input type="textbox" name="question[q]['+ count + '][label][]" id="label" class="form-control col-md-7 offset-md-1"/> <i type="button" style="color: #f44336;  font-size: 22px;" class="remove col-md-1 i-Remove"></i><i class="col-md-1 offset-md-1 i-Add  as" id="addSubQuestion" onclick="addSubQuestion('+count+');";  style="color: #2cb8ea;  font-size: 22px;" ></i> </div></a><ul class="class="abc" id="appendsub_div'+count+'""></ul></li>'));
            var otherValue = $("#other_amount").val();
            // count++;
         }  */

/*
$(document).on('click', 'i.queRemove', function( e ) {
        var count=parseInt($('#counter').val());
        var id = $(this).parents( 'div.appendedQuestion' ).find('.labels_container').attr("id");
        var c =  id.substring(id.length- 1);
       if(count==c){
        var countvalue=parseInt($('#counter').val()) - 1;
            $('#counter').val(countvalue);
        e.preventDefault();
            $(this).parents( 'ul.appendedul' ).remove();
       }else{
           alert('Cant remove intermediate question');
       }
           
    });   */
// $(".queSubRemove").click(function(){
//alert("The paragraph was clicked.");
//});


$(document).on('click', 'i.remove', function (e) {
    e.preventDefault();
    $(this).parents('li.lrm' + this.id).remove();
});



/*
$('#addQuestion').on('click', function( e ) {
        $.ajax({
        url: "get-form",
        success: function (data) {  
            template = $(data);
            var count=parseInt($('#counter').val()) + 1;
            $('#counter').val(count);
 
            template.find('.question_tree').attr('id', 'question_tree_'+count);
            template.find('.btn-info').attr('data-target', '#appendedQuestion_'+count);
            template.find('.appendedQuestion').attr('id', 'appendedQuestion_'+count);           
            template.find('#question').attr('name', "question[q]["+count+"][questionTitle]");
            template.find('#question').attr('id', 'question_'+count);
            template.find('#answerFormat').attr('name', "question[q]["+count+"][answerFormat]");
            template.find('#answerFormat').attr('id', 'answerFormat_'+count);
            // $('#label').attr('name', "question[q]["+count+"][label]");
            // $('#label').attr('id', 'label_'+count);
 
            template.find('.labels_container').attr('id', 'labels_container_'+count);
            template.find('.add').attr('id', 'add'+count);
            template.find('.add').attr('id', 'add'+count);
            template.find('#questionCounter').text(count);
            // template.find('#label').attr('value', ''+count);
            // alert((template).html());
            $("#append_div" ).append(template);
            $('#removeButton').show(); },
        dataType: 'html'
        });
        
    });*/

function questionRemove(obj) {

    if ($('li#subquestion_tree' + obj.id).parents('li.lrm').find('#label').attr('value') == 'default yes') {
        $('li#subquestion_tree' + obj.id).parents('li.lrm').remove();
    } else {
        $(obj).parents('li#subquestion_tree' + obj.id).remove();
    }

}

function addSubQuestion(valOfCount, labelname, lbid) {
    //alert($("#question_tree_1").find(.'#qscount').attr('value'));
    if ($("#" + lbid).find('select').val() == '1') {
        return false;
    }
    $.ajax({
        url: "dynamic-template-decision",
        success: function (data) {
            template = $(data);
            var count = parseInt($("#" + lbid).find('#qscount' + valOfCount).attr('value')) + 1;
            if ($(".lrm" + valOfCount).find('#label').val() == '' || $(".lrm" + valOfCount).find('#label').val() == ' ') {
                $(".lrm" + valOfCount).find('#lberror').show();
                return false;
            } else {
                $(".lrm" + valOfCount).find('#lberror').hide();
            }
            $("#" + lbid).find('#qscount' + valOfCount).attr('value', count);
            //$('#count').val(count);
            //alert(count);
            var label = labelname.replace('[val]', '');
            var label_question = label + '[qs][' + count + '][q]';
            var label_af = label + '[qs][' + count + '][AF]';
            //alert($('#addSubQuestion').prev("input").attr("name"));
            //var count=parseInt($('#count').val()) + 1;
            //var subcount
            //template.find('.question_tree_list').attr('id', 'addtree_'+valOfCount);

            template.find('.btn-info').attr('data-target', '#appendedSubQuestion_' + valOfCount + '' + count);
            template.find('.appendedQuestion').attr('id', 'appendedSubQuestion_' + valOfCount + '' + count);
            template.find('#question').attr('name', label_question);
            template.find('#question').attr('id', 'question_' + valOfCount);
            template.find('#answerFormat').attr('name', label_af);
            template.find('#answerFormat').attr('id', 'answerSubFormat_' + valOfCount + '' + count);
            // $('#label').attr('name', "question[q]["+count+"][label]");
            // $('#label').attr('id', 'label_'+count);

            template.find('.labels_container').attr('id', 'labels_subcontainer_' + valOfCount + '' + count);
            template.find('.add').attr('id', 'add' + valOfCount + '' + count);
            template.find('.queSubRemove ').attr('id', valOfCount + '' + count);
            // template.find('#questionCounter').text(valOfCount+''+count);
            // template.find('#label').attr('value', ''+count);
            var valOfdiv = valOfCount;
            $("#appendsub_div" + valOfdiv).append('<li class="question_tree" id="subquestion_tree' + valOfCount + '' + count + '"></li>');
            $("#subquestion_tree" + valOfCount + '' + count).append(template);
            $('#removeButton').show();
        },
        dataType: 'html'
    });


}
//};

window.decisionTree = {
    //init: init,
    onResult: onResult
};