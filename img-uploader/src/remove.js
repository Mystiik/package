function remove_image(id) {
  var element = document.getElementById('element' + id.toString());
  var formData = new FormData();
  var request = new XMLHttpRequest();

  formData.set('id', id);
  request.open("POST", '/vendor/gnicolas/package/img-uploader/src/remove.php');
  request.onreadystatechange = function() { //Appelle une fonction au changement d'état.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      element.parentNode.removeChild(element);
    }
  };
  request.send(formData);
};