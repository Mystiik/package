//---------------------------------------------------------------------------------------------
// updateSameTextId
//---------------------------------------------------------------------------------------------
function updateSameTextId(e) {
  // Search for same name elements
  var textarea = document.getElementsByName(e.name.toString());

  // Loop
  for (var i = 0; i < textarea.length; i++) {
    textarea.item(i).value = e.value;
  }
}