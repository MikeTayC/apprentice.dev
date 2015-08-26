$(function(){
    var students = new Array();

    $.ajax({
        url: 'http://apprentice.dev/incubate/ajax/index',
        data: {
            id: 'admin'
        },
        type: "GET",
        dataType: "json",

        success: function(json) {
            $.each(json, function(key, value) {
                students.push(value);
            });
        }

    });


    $('#myTeachers').tagit( {
        availableTags: students,
        removeConfirmation: true,
        allowSpaces: true,
        showAutocompleteOnFocus: true,
        singleField: true,
        tagLimit: 1,
        fieldName: "teacher"
    });

});

