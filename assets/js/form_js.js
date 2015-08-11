$(function(){
    var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];
    var lessonTags = new Array();

    $.ajax({
        url: 'http://apprentice.dev/incubate/ajax/index',
        data: {
            id: 'tag'
        },
        type: "GET",
        dataType: "json",

        success: function(json) {
            $.each(json, function(key, value) {
                lessonTags.push(value);
            });
        }

    });



    $('#myTags').tagit( {
        availableTags: lessonTags,
        singleField: true,
        removeConfirmation: true,
        allowSpaces: true,
        showAutocompleteOnFocus: true
    });

});

