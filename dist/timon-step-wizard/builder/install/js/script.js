$(function() {
    // Nav Tab stuff



    $('.nav-tabs > li > a').click(function() {
        if($(this).hasClass('disabled')) {
            return false;
        } else {
            var linkIndex = $(this).parent().index() - 1;
            $('.nav-tabs > li').each(function(index, item) {
                $(this).attr('rel-index', index - linkIndex);
            });
        }
    });
    $('#step-1-next').click(function() {


        var isValid = checkInputs();

        if(isValid) {
            saveData();
        }
    });
    $('#step-2-next').click(function() {
        // Check values here
        var isValid = checkInputs();

        if(isValid) {
            resetInputs();
            $('.nav-tabs > li:nth-of-type(3) > a').removeClass('disabled').click();
        }
    });
    $('#step-3-next').click(function() {
        // Check values here
        var isValid = checkInputs();

        if(isValid) {
            saveData();
        }
    });
});
function checkInputs() {
    var isValid = true;
    $('.tab-pane.active .form-control').each(function () {

      if ($(this).val()=='') {
        isValid=false;
        $(this).addClass('required');
      }
    });

  return isValid;
}
function resetInputs() {
  $('.tab-pane.active .form-control').each(function () {
      $(this).removeClass('required');
  });
}
function saveData() {
  var data={};
    $('.tab-pane .form-control').each(function () {

      data[$(this).attr('id')]=$(this).val();
    });

    data['add_templates']=$('#db_add_template').is(':checked');

    $.ajax({
      type: "POST",
      url: 'save.php',
      data: data,
      success: function (response) {
        if (response=='ok') {
          window.location.href='finish.php?status=success';
        }else{
      //   window.location.href='finish.php?status=error';
        }
      },error: function () {
        //  window.location.href='finish.php?status=error';
      }
    });
}
