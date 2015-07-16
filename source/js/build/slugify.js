COURIER.slugify = function(text) {
	return text
		.toString()                 // Make sure it's a string
		.toLowerCase()              // Make it all lower case
		.replace(/\s+/g, '-')       // Replace spaces with -
		.replace(/[^\w\-]+/g, '')   // Remove all non-word chars
		.replace(/\-\-+/g, '-')     // Replace multiple - with single -
		.replace(/^-+/, '')         // Trim - from start of text
		.replace(/-+$/, '');        // Trim - from end of text
};