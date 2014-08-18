//<![CDATA[
function checkFields(el) {
    var sel = $(el).val();
    $("#embed").toggle(sel == "embed");
    $("#file").toggle(sel == "file");
}
$(function () {
    checkFields($(".audio_type_radio:checked"));
    $(".audio_type_radio").on("click", function () { checkFields(this); });
});
//]]>