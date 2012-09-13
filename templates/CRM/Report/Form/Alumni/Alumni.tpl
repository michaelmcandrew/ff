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
cj(document).ready(function() {
    cj('#col-groups').after("<p>You can use this function to find alumni in specific jobs, employment sectors or who studied certain A levels or university subjects. This will help you target the right alumni for opportunities you have in your school E.g. why not see who has been to university and could come back to inform current students about what it’s really like.<p></p>You can also see what volunteering opportunities the alumni are interested in by looking at their individual profiles, so don’t miss out on the chance to get them involved with the school.</p>").hide();
    cj('#set-filters td:nth-child(2), #access, #civicrm-footer, #set-filters h3').hide();
});
    cj('.crm-accordion-header').html(cj('.crm-accordion-header').html().replace('Report Criteria', 'Search alumni'));
    cj('#_qf_Alumni_submit').val('Search');
    cj('#_qf_Alumni_submit_print').hide();
    cj('#_qf_Alumni_submit_pdf').hide();
{/literal}
</script>
