$(function(){
    var tags = new Array();

    $.ajax({
        url: 'http://apprentice.dev/incubate/ajax/index',
        data: {
            id: 'tag'
        },
        type: "GET",
        dataType: "json",

        success: function(json) {
            $.each(json, function(key, value) {
                tags.push(value);
            });
            console.log(tags);
        }

    });


    $('#myTags').tagit( {
        availableTags: tags,
        removeConfirmation: true,
        allowSpaces: true,
        showAutocompleteOnFocus: true,
        singleField: true,
        fieldName: 'tags'
    });

});