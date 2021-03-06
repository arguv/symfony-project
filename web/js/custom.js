$(function () {
    count = 1;

    $('body').on('click', 'nav ul.main_item li a', function (e) {

        if ($(this).parent('li').length) {
            $($(this).parent('li').find('.child_item')).toggle();
        } else {
            $('.main_item').find('.sub_item .child_item').css('display', 'none');
        }

    });

    addMoreField();

    $(document).on('keydown', 'input.numberControl', function(evt){
        var key = evt.charCode || evt.keyCode || 0;

        return (key == 8 ||
            key == 9 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
    });
});

function incrementCount() {
    return ++count;
}

function deleteMoreField(number) {

    $("#edit_productId").find("[data-id='" + number + "']").remove();
}

function addMoreField() {

    var currentNumber = incrementCount();

    var tag = '<div data-id="'+ currentNumber +'" class="form-group" style="border: 1px solid #ccc; padding: 25px">\n' +
        '            <span onclick="deleteMoreField('+ currentNumber +');" style="float: right;margin-top: -11px;cursor: pointer;">X</span>\n' +
        '            <label id="parentId-'+ currentNumber +'">Product Id</label>\n' +
        '            <input id="parentId-'+ currentNumber +'" name="'+ currentNumber + '[productId]" type="text" class="form-control numberControl" maxlength="50" required="required" />\n' +
        '            <br>\n' +
        '            <label id="parentId-'+ currentNumber +'">Note</label>\n' +
        '            <input id="parentId-'+ currentNumber +'" name="'+ currentNumber + '[note]" type="text" class="form-control" maxlength="50" />\n' +
        '        </div>';


    $('#mainForm').after(tag).html();
}