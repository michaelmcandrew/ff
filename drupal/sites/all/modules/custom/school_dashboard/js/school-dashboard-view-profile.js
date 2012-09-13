cj(document).ready(function() {
    cj('#profilewrap15 .content').each(function(){
        if(!cj(this).html().trim().length){
            cj(this).parent().hide();
        }
    });
    var year = cj('#row-custom_32 .content').html().trim();
    if(year.length){
        cj('#row-custom_32 .content').html(year.substr(year.length-4))
    }

});
