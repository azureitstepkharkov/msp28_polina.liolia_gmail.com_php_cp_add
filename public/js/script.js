$(function ($) {
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

    $('[data-toggle=confirmation]').click(
        function (e) {
            e.preventDefault();
            let $form = $(this).closest("form");
            //$form.submit();
            alertify.submitForm = $form;
            alertify.confirm($(this).data("title"), $(this).data("content"),
                function () {

                    alertify.submitForm.submit();
                    alertify.success('Ok')

                }
                , function () {
                }
            );

        });

    setBodyMinHeight();

    $('.programmable_role').change(function () {
        if(this.checked) //any technical role was checked on form
        {
            $('.tech_list').css('display', 'block');
        }
        else //any technical role was unchecked on form
        {
            $programmable_checkboxes = $('.programmable_role');
            //checking if any other technical role is still checked:
            $flag = false;
            $programmable_checkboxes.each(function ($i, $val) {
                if($val.checked)
                {
                    $flag = true;
                    return false;
                }
            });
            if($flag == false) //if no technical role is still checked, hiding technologies list:
                $('.tech_list').css('display', 'none');
        }
        }
    );


    $('#add_contact_type').click(function ($e) {
        $e.preventDefault();
        $('#new_contact_type_input').show();
        $('#contact_types_select').hide();
    });

    $('#cancel_contact_type_creation').click(function ($e) {
        $e.preventDefault();
        $('#new_contact_type_input').hide();
        $('#contact_types_select').show();
    });


    Dropzone.autoDiscover = false;
    Dropzone.options.myAwesomeDropzone
        = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        autoQueue: false,
        init: function () {
            // Set up event handlers
            this.on("success", function(file, response) {
                console.log("completed");
                console.log(JSON.parse(response));
                location.reload();
            });
            this.on("complete", function(file, response) {
                console.log("completed");
                console.log(JSON.parse(response));
                location.reload();
            });
        }
    };


    Dropzone.options.myFilesDropzone = {
        autoQueue: true,
        init: function () {
            // Set up event handlers
            this.on("complete", function(file, response) {
                console.log("completed");
                console.log(JSON.parse(response));
                location.reload();
            });
        }
    };

});


    function setBodyMinHeight() {
    let bodyHeight = $('body').height();
    let windowHeight = $(window).height();
    if (bodyHeight < windowHeight)
    {
        $('body').height(windowHeight);
        $('footer.main_footer').css("position", "absolute");
       // $('footer.main_footer1').css({position: "absolute", marginBottom : "62px"});
    }
    else
    {
        let footerHeight = windowHeight * 0.1;
        $('footer.main_footer').css('height', footerHeight)
                                .css("position", "absolute")
                                .css('top', bodyHeight);

    }

}