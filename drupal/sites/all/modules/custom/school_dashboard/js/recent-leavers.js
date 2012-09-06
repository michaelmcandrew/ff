jQuery(document).ready(function() {
    console.log('hello');
    jQuery('#recent_leavers').load('school-dashboard/report?force=1&recent=1&snippet=1&section=2');
});
