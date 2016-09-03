$(function(){
    /* Select/Deselect all checkboxes in tables */
    $('.btSelectAll').on('change',function() {
        if(this.checked){
            $('.checkboxes').each(function(){
                this.checked = true;
            });
        }else{
            $('.checkboxes').each(function(){
                this.checked = false;
            });
        }
    });

    $('.checkboxes').on('change',function(){
        if($('.checkboxes:checked').length == $('.checkboxes').length){
            $('.btSelectAll').prop('checked',true);
        }else{
            $('.btSelectAll').prop('checked',false);
        }
    });
});