cj(document).ready(function() {
    cj('#profilewrap15 .content').each(function(){
        if(!cj(this).html().trim().length){
            cj(this).parent().hide();
        }
    });
});
