//<![CDATA[
function checkFields(el) {
    var sel = $(el).val();
    $("#amazon").toggle(sel == "amazon");
    $("#external").toggle(sel == "external");
    $("#internal").toggle(sel == "internal");
}
$(function () {
    checkFields($(".product_type_radio:checked"));
    $(".product_type_radio").on("click", function () { checkFields(this); });
});
//]]>