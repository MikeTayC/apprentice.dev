$(function(){
    var lessons = new Array();

    $.ajax({
        url: 'http://apprentice.dev/incubate/ajax/index',
        data: {
            id: 'lesson'
        },
        type: "GET",
        dataType: "json",

        success: function(json) {
            $.each(json, function(key, value) {
                lessons.push(value);
            });
            console.log(lessons);
        }

    });


    $('#myLessons').tagit( {
        availableTags: lessons,
        removeConfirmation: true,
        allowSpaces: true,
        showAutocompleteOnFocus: true,
        tagLimit : 1,
        fieldName: "lesson_name"
    });

});

