$(function(){
    var students = new Array();

    $.ajax({
        url: 'http://apprentice.dev/incubate/ajax/index',
        data: {
            id: 'student'
        },
        type: "GET",
        dataType: "json",

        success: function(json) {
            $.each(json, function(key, value) {
                students.push(value);
            });
        }

    });


    $('#myStudents').tagit( {
        availableTags: students,
        removeConfirmation: true,
        allowSpaces: true,
        showAutocompleteOnFocus: true,
        singleField: true,
        fieldName: "student_list"
    });

});
