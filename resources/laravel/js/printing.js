window.printing = function () {
	$(document).on("click", "[type='checkbox'],[type='radio']", function () {
		return false;
	});
}
