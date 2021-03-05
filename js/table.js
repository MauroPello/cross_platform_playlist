var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width());
    });
    return $helper;
},
updateIndex = function(e, ui) {
    ids = "";
    $('td.index', ui.item.parent()).each(function (i) {
        $(this).html(i + 1);
    });
    $("#table-song > tbody > tr").each(function () {
        ids += $($($(this)[0].innerHTML)[0].innerHTML).val();
    });
    ids = ids.replaceAll("Play_Song", "").replace("*|*", "");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {
            rearrange_songs: ids
        }
    });
};

$("#table-song tbody").sortable({
    helper: fixHelperModified,
    stop: updateIndex
}).disableSelection();