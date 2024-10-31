/*********************
*
*	Insertion Functions
*
*/

var win = window.dialogArguments || opener || parent || top;

function insertRating() {
	
	var the_rating = window.prompt("How many stars?","");
	
	if (isNaN(the_rating) || the_rating == "") {
		alert("You need to provide a number of stars to give in your rating (type in a number).");
	} else {
		var rating_shortcode = "[rating=" + the_rating + "]";
		win.send_to_editor(rating_shortcode);
	}
	
}
