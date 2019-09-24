function saveLocal(){
    sessionStorage.setItem('email',$('input[name=email]').val());
}
$(function () {
    $('input[name=email]').val(sessionStorage.getItem('email'));
})