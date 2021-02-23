// try with JS only
// document.querySelectorAll("bulkOptionsContainer")

$(document).ready(function(){
   $('#selectAll').click(function (event){
       if(this.checked){
           $('.checkBoxes').each(function(){
               this.checked = true;
           });
       } else {
           $('.checkBoxes').each(function(){
               this.checked = false;
           });
       }
   });
});