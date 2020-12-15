let type = $("input.type").val();
if (type === 0 || type == undefined) {
  type = "";
}
// update search category
let category = $("input.category").val();
if (category === 0 || category == undefined) {
  category = "";
}
// update search keyword
keyword =
  "/?s=" + $(this).val() + "&post_type=" + type + "&category_name=" + category;
$(".search-keyword").attr("data-target", "/cimuk" + keyword);
$(".search-keyword .label").text("Search for " + '"' + $(this).val() + '"');

//call this function on submit or enter
function goKeyword() {
  window.location.href = $(".search-keyword").data("target");
}
