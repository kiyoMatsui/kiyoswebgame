$(document).ready(function(){
  $(".fademe").click(function(){
    var div = $(".fademe");  
    div.animate({ fontSize: '0' }, "slow");
    setTimeout(function(){ div.animate({ fontSize: '16' }, "fast"); }, 500);
  });
});
 
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation')

    // Loop over them and prevent submission
    Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  }, false)
}())
