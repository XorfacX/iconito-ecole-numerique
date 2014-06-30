function initClassroomRelatedToYear(choiceMap) {
    var $grade = $('#grade');
    var $originalClassroom = $('#classroom').clone();

    var onYearChange = function() {
        var selectedYear = $grade.val();

        var $classroom = $originalClassroom.clone().empty();

        $('#classroom').replaceWith($classroom);

        var classroomIds = choiceMap[selectedYear];

        var $choices = $originalClassroom.children().filter(function(){
            var value = $(this).attr('value');
            return (value == '' || (-1 !== $.inArray(value, classroomIds)));
        });

        $classroom.append($choices.clone());
    };

    $grade.change(onYearChange);

    onYearChange();
}