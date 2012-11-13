SELECT cc.id, ce.email, cp.phone, ccc.what_year_are_you_in__12
FROM civicrm_contact AS cc
JOIN civicrm_email AS ce ON cc.id = ce.contact_id
JOIN civicrm_phone AS cp ON cc.id = cp.contact_id AND phone_type_id=2
JOIN civicrm_value_contact_reference_9 AS ccc ON cc.id = ccc.entity_id
WHERE what_year_are_you_in__12 = 'thirteen'
GROUP BY cc.id
ORDER BY rand()
LIMIT 1000
