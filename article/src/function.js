//---------------------------------------------------------------------------------------------
// modify_article
//---------------------------------------------------------------------------------------------
// function modify_article(id) {
//   var formData = new FormData();
//   var request = new XMLHttpRequest();

//   formData.set('id', id);
//   request.open("POST", '/admin/article/creation');
//   request.onreadystatechange = function() { //Appelle une fonction au changement d'état.
//     if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
//       upload_number.innerHTML = (parseInt(upload_number.innerHTML) + 1);
//       document.??
//     }
//   };
//   request.send(formData);
// };

//---------------------------------------------------------------------------------------------
// remove_article
//---------------------------------------------------------------------------------------------
function remove_article(id) {
  var element = document.getElementById('element' + id.toString());
  var formData = new FormData();
  var request = new XMLHttpRequest();

  formData.set('function', 'remove_article');
  formData.set('id', id);
  request.open("POST", '/vendor/gnicolas/package/article/src/function.php');
  request.onreadystatechange = function() { //Appelle une fonction au changement d'état.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      element.parentNode.removeChild(element);
    }
  };
  request.send(formData);
};