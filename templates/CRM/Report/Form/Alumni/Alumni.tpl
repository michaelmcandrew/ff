{include file="CRM/Report/Form.tpl"}
<div id="update_via_profile"></div>
<script type="text/javascript">
{literal}
function updateViaProfile( contactID ) {

    var dataURL = {/literal}"{crmURL p="civicrm/profile/edit" q="reset=1&snippet=5&context=dialog&blockNo=1&gid=14&id=" h=0}"{literal};
    dataURL = dataURL + contactID;

    cj.ajax({
        url: dataURL,
        success: function( content ) {
            cj( '#update_via_profile' ).show( ).html( content ).dialog({
                title: "Update contact details",
                modal: true,
                
                close: function(event, ui) {
                    cj('#update_via_profile').fadeOut(5000);
                }
            });
        }
    });
}
{/literal}
</script>
