function reindexCollection(container) {
    $('#' + container).find('tbody tr td:nth-child(2)').each(function (index, element) {
        $(element).html(index + 1);
    });
}

$('table').delegate('.remove-line', 'click', function (e) {
    e.preventDefault();
    var row = $(this).parent().parent();
    var table = row.parent().parent();
    row.remove();
    reindexCollection(table.attr('id'));
});

$('.add_tag_link').click(function (e) {
    e.preventDefault();
    var table = $(this).closest('table');
    var maxLimit = table.attr('data-max-limit');
    var rowCount = table.find('tbody tr').length;
    if ('' != maxLimit && rowCount >= maxLimit) {
        alert('You cannot add more than ' + maxLimit + ' items.');
        return;
    }
    var prototype = table.attr('data-prototype');
    prototype = prototype.replace(/__name__/g, rowCount);
    rowCount++;

    // create a new list element and add it to the list
    table.find('tbody').prepend(prototype);
    reindexCollection(table.attr('id'));
});

$("#form_recherche").submit(function (e) {
    e.preventDefault();
    $(".loading").show();
    var motcle = $("#actor_search_motcle").val();

    var DATA = 'motcle=' + motcle;
    $.ajax({
        type: "GET",
        url: Routing.generate('actors_list'),
        data: DATA,
        cache: false,
        success: function (data) {
            $('#resultats_recherche').html(data);
            $(".loading").hide();
        }
    });
});

$("#app_portal_actor_birthday").datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'dd/mm/yy',
    yearRange: "-100:+0",
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
    }
});

$("#app_portal_movie_releasedAt").datepicker({dateFormat: 'dd/mm/yy'});

$('#app_portal_movie_filter_releaseDateFrom').datepicker( {
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'dd-mm-yy',
    yearRange: "-50:+0",
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
    }
});

$('#app_portal_movie_filter_releaseDateTo').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'dd-mm-yy',
    yearRange: "-50:+0",
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
    }
});

$('#app_portal_contacttype_other').hide();

if ($("input[name='app_portal_contacttype[knowledge]']:checked").val() == 'autre') {
    $('#app_portal_contacttype_other').show();
} else {
    $('#app_portal_contacttype_other').hide();
}

$("input[name='app_portal_contacttype[knowledge]']").click(function () {
    if ($(this).val() == 'autre') {
        $('#app_portal_contacttype_other').fadeIn();
    } else {
        $('#app_portal_contacttype_other').fadeOut();
    }
});

$('#app_portal_movie_filter_actors').multiselect(
    {
        buttonWidth: '200px',
        disableIfEmpty: true,
        disabledText: 'aucune valeur ...',
        buttonText: function(options, select) {
            if (options.length === 0) {
                return 'Tous les acteurs';
            }
            else if (options.length > 3) {
                return 'Plus de 3 sélectionnés';
            }
            else {
                var labels = [];
                options.each(function() {
                    if ($(this).attr('label') !== undefined) {
                        labels.push($(this).attr('label'));
                    }
                    else {
                        labels.push($(this).html());
                    }
                });
                return labels.join(', ') + '';
            }
        }
    }
);
$('#app_portal_movie_filter_hashTags').multiselect(
    {
        buttonWidth: '200px',
        disableIfEmpty: true,
        disabledText: 'aucune valeur ...',
        buttonText: function(options, select) {
            if (options.length === 0) {
                return 'Tous les hashtags';
            }
            else if (options.length > 3) {
                return 'Plus de 3 sélectionnés';
            }
            else {
                var labels = [];
                options.each(function() {
                    if ($(this).attr('label') !== undefined) {
                        labels.push($(this).attr('label'));
                    }
                    else {
                        labels.push($(this).html());
                    }
                });
                return labels.join(', ') + '';
            }
        }
    }
);
