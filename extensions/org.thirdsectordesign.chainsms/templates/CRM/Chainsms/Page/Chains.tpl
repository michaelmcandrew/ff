<p>This page shows all message templates that have automatic responses configured.  You can edit currently set up responses and create new responses.</p>

<p>Here are all the message templates that are set up to have responses.</p>
{foreach from=$templates item=template}
	<h3>{$template.cmt_msg_title}</h3>
	<p>{$template.cmt_msg_text}</p>
	<table class="display">
	<thead><tr><th>Answer</th><th>Next message</th><th>Actions</th></tr></thead>
	<tbody>
	{foreach from=$template.answers item=answer}
		<tr class="{cycle values='odd,even'}-row">
			<td>{$answer.cca_answer}</td>
			<td>
				<div><b>{$answer.cnmt_msg_title}</b></div>
				<div>{$answer.cnmt_msg_text}</div>
			</td>
			<td>
				<a href="/civicrm/sms/chains/answer?action=update&id={$answer.cca_id}">edit</a>
				<a href="/civicrm/sms/chains/answer?action=delete&id={$answer.cca_id}">delete</a>
			</td>
		<tr>
	{/foreach}
	</tbody>
	</table>
	<p><a href="/civicrm/sms/chains/answer?action=add&msg_template_id={$template.cmt_id}">add another answer</a>
{/foreach} 
