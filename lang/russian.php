<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 04.02.2020, 6:41
 *
 */

global $_LANG;

// Управление доменами
$_LANG['DomainManager_service'] = 'Услуга';
$_LANG['DomainManager_domain'] = 'Домен';
$_LANG['DomainManager_addon'] = 'Дополнение';
$_LANG['DomainManager_zone'] = 'Зоны:';
$_LANG['DomainManager_add_zone'] = 'Добавить новую зону';
$_LANG['DomainManager_add_zone_modal'] = 'Добавить домен';
$_LANG['DomainManager_domain'] = 'Домен';
$_LANG['DomainManager_allow_domain'] = 'Разрешены только зоны: %s';
$_LANG['DomainManager_create_main'] = 'Создать @';
$_LANG['DomainManager_other_ip'] = 'Свой ip';
$_LANG['DomainManager_ip'] = 'ip';
$_LANG['DomainManager_other_ip_selected'] = 'Другой';
$_LANG['DomainManager_create_www'] = 'Создать www';
$_LANG['DomainManager_added_zone'] = 'Добавление зоны';
$_LANG['DomainManager_empty_zones'] = 'Вы ещё не добавили не одного домена';
$_LANG['DomainManager_edit_zone'] = 'Редактирование dns записей';
$_LANG['DomainManager_delete_zone'] = 'Удалить домен';

//общее
$_LANG['DomainManager_close_modal'] = 'Закрыть';

// Управление записями
$_LANG['DomainManager_manager_dns'] = 'Управление DNS';
$_LANG['DomainManager_edit_zone'] = 'Редактирование зоны';
$_LANG['DomainManager_record_context'] = 'Значение';
$_LANG['DomainManager_save_records'] = 'Сохранить изменения';
$_LANG['DomainManager_add_record'] = 'Добавить запись';
$_LANG['DomainManager_record_ttl'] = 'TTL (секунды)';
$_LANG['DomainManager_record_type'] = 'Тип';
$_LANG['DomainManager_record_name'] = 'Имя';
$_LANG['DomainManager_record_delete'] = 'Удалить запись';
$_LANG['DomainManager_add_record_title_modal'] = 'Добавление записи';
$_LANG['DomainManager_total'] = 'Всего';
$_LANG['DomainManager_priority'] = 'Priority';
$_LANG['DomainManager_weight'] = 'Weight';
$_LANG['DomainManager_port'] = 'Port';
$_LANG['DomainManager_text'] = 'Text';
$_LANG['DomainManager_preference'] = 'Preference';
$_LANG['DomainManager_exchange'] = 'Exchange';


//валидация
$_LANG['DomainManager_domain_edit_not_match_domain'] = 'Вы пытаетесь создать запись с именем домена отличного от текущего (возможно забыли точку на конце).';
$_LANG['DomainManager_zone_not_allowed'] = 'Вы не можете создать домен в этой зоне';
$_LANG['DomainManager_zone_limit_reached'] = 'Действие невозможно, превышен лимит доменов для пакета.';
$_LANG['DomainManager_zone_record_limit_reached'] = 'Превышен лимит записи типа %s';
$_LANG['DomainManager_domain_busy'] = 'Домен %s уже создан другим пользователем';
$_LANG['DomainManager_domain_not_found'] = 'Домен %s не найден';
$_LANG['DomainManager_domain_already_created'] = 'Домен %s уже создан другим пользователем';
$_LANG['DomainManager_domain_is_not_assigned_account'] = 'Невозможно удалить домен, так как он не привязан к аккаунту в whmcs';
$_LANG['DomainManager_domain_blacklisted'] = 'Домен %s занесен в черный список администратором';
$_LANG['DomainManager_zone_regex_error'] = 'Возникла ошибка при выполнении регулярного выражения, свяжитесь с администратором';
$_LANG['DomainManager_not_in_expected_format'] = 'Запись %s: не в ожидаемом формате (анализируется как \'%s\')';
$_LANG['DomainManager_name_value_is_not_correct'] = 'Значение имени для одной из записей не корректно';