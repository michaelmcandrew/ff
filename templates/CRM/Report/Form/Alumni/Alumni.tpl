{include file="CRM/Report/Form.tpl"}
<script type="text/javascript">
{literal}

cj(document).ready(function() {
    cj('#col-groups').after("<p>You can use this function to find alumni in specific jobs, employment sectors or who studied certain A levels or university subjects. This will help you target the right alumni for opportunities you have in your school E.g. why not see who has been to university and could come back to inform current students about what it’s really like.<p></p>You can also see what volunteering opportunities the alumni are interested in by looking at their individual profiles, so don’t miss out on the chance to get them involved with the school.</p>").hide();
    cj('#set-filters td:nth-child(2), #access, #civicrm-footer, #set-filters h3').hide();
});
    cj('.crm-accordion-header').html(cj('.crm-accordion-header').html().replace('Report Criteria', 'Search alumni'));
    cj('#_qf_Alumni_submit').val('Search');
    cj('#_qf_Alumni_submit_print').hide();
    cj('#_qf_Alumni_submit_pdf').hide();

{/literal}
{if $latest eq true}
{literal}

cj('#Alumni').before("Here are the latest students from your current cohort who have signed up to your alumni network. Use this list to see who still needs to sign up this year.");
cj('.crm-report-field-form-block').hide();

{/literal}
{/if}

</script>
