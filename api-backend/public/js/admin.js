$( document ).ready(function() {
    $(document)
    .on('change', 'select.selectionable_type', function () {

        // По изменению типа контента заполняем объектами контента
        let type = $(this).val();
        let element = $(this).closest('.has-many-items-form').find('select.selectionable_id');

        fillSelectionIdSelect(element, type);
    })
    .on('click', '#update-autocomplete-words', function () {

        $.ajax({
            url: '/admin/api/update_autocomplete_words/',
            type: "get",
            dataType: 'json',
            success : function(data) {
                alert('Готово!');
                console.log(data.data);
            },
            async : false
        });
        return false;
    });
});

function fillAllSelects() {
    // Заполняем все ранее созданные селекты
    $('select.selectionable_type').each(function() {
        let type = $(this).val();
        let parent = $(this).closest('.has-many-items-form');
        let element = parent.find('select.selectionable_id');
        fillSelectionIdSelect(element, type);
        drawImage(parent);
        // alert(parent.children().val());
    });
}

function drawImage(parentElement) {
    let selectionId = parentElement.children().val();
    $.ajax({
        url: '/admin/api/selection_items/' + selectionId + '/media',
        type: "get",
        dataType: 'json',
        success : function(data) {
            parentElement.prepend(
              '<div class="form-group">' +
                '<div class="col-sm-2"></div>' +
                '<div class="col-sm-8"><img src="' + data.data.thumb + '" width="50px"/></div>' +
              '</div>'
            );
            // console.log(data.data);
        },
        async : false
    });
}

function fillSelectionIdSelect(element, type) {

    let id = element.attr('data-value');
    console.log('id ' + id);
    let dataList;

    $.ajax({
        url: '/admin/api/selection_items',
        type: "get",
        dataType: 'json',
        data:  {
            type: type,
        },
        success : function(data) {
            dataList = data.data;
        },
        async : false
    });

    // Заполняем селект
    element.empty().trigger("change");
    element.select2({
        placeholder : 'Select',
        allowClear: true,
        data: dataList
    });

    // Устанавливаем значение
    if (id) {
        element.val(id).trigger('change');
    }
}
