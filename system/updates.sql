/* r170 */
ALTER TABLE `ac_users` CHANGE `code` `salt` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `ac_users` CHANGE `pass` `hash` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `ac_users` CHANGE `created` `time_c` INT( 10 ) NOT NULL;
ALTER TABLE `ac_users` ADD `time_u` INT( 10 ) NOT NULL AFTER `time_c`;
ALTER TABLE `ac_users` ADD `time_l` INT( 10 ) NOT NULL AFTER `time_u`
ALTER TABLE `ac_users` DROP `permissions`;
ALTER TABLE `ac_permissions` ADD `access_mask` INT( 10 ) NOT NULL AFTER `option`;
UPDATE `ac_settings` SET `status`='1';
RENAME TABLE `ac_templates` TO `ac_blocks`;
ALTER TABLE `ac_blocks` DROP `name`;
ALTER TABLE `ac_blocks` ADD `position` TEXT NOT NULL AFTER `page`;
INSERT INTO `ac_blocks` (`title`,`module`,`block`,`page`,`position`,`weight`,`active`) VALUES ('Меню первого уровня','site_menu','level_1','','left','1','1');

/* r118 */
ALTER TABLE `ac_settings` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ac_settings` ADD `module` TEXT NOT NULL AFTER `value`;
UPDATE `ac_settings` SET `module`='wysiwygs';
ALTER TABLE `ac_settings` ADD `type` TEXT NOT NULL,
ADD `options` TEXT NOT NULL,
ADD `attributes` TEXT NOT NULL,
ADD `status` TEXT NOT NULL;

/* r111 */
ALTER TABLE `ac_css` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ac_menu` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ac_pages` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;
UPDATE `ac_templates` SET `id`='10' WHERE `id`='0';
ALTER TABLE `ac_templates` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ac_users` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ac_users_permissions` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;

/* r109 */
ALTER TABLE `ac_modules` CHANGE `group_m` `group` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `ac_modules` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT;

/* r107 */
ALTER TABLE `ac_pages` ADD `meta_title` TEXT AFTER `title`;

/* r101 */
INSERT INTO `ac_data_types` (`id`, `num`, `name`, `title`, `module`, `url`, `data_table`, `active`) VALUES (12, 12, 'data_template', 'Данные. Шаблон', 'data', '', 'data_templates', 1);
ALTER TABLE `ac_data_templates` ADD `title` TEXT NOT NULL AFTER `name`;
UPDATE `ac_menu` SET `childs`='add,edit,list_fields,edit_field,list_templates,edit_template' WHERE `name`='data';
INSERT INTO `ac_data_fields` (`id`, `num`, `name`, `title`, `module`, `data_name`, `type`, `options`, `attributes`, `default_value`, `status`, `active`) VALUES
(67, 67, 'name', 'Идентификатор', 'data', 'data_template', 'textbox', '', '', '', '', 1),
(68, 68, 'title', 'Название', 'data', 'data_template', 'textbox', '', '', '', '', 1),
(69, 69, 'text', 'Текст', 'data', 'data_template', 'textarea', '', 'rows=10 cols=60', '', '', 1),
(70, 70, 'fields', 'Поля', 'data', 'data_template', 'textbox', '', '', '', '', 1),
(71, 71, 'page_template', 'Шаблон страницы', 'data', 'data_template', 'textbox', '', '', '', '', 1);
INSERT INTO `ac_users_permissions` (`id`, `title`, `module`, `page`, `option`, `active`) VALUES
(58, 'Список шаблонов', 'data', 'list_templates', '', 1),
(59, 'Добавление шаблонов', 'data', 'add_template', 'template_add', 1),
(60, 'Редактирование шаблонов', 'data', 'edit_template', 'template_edit', 1),
(61, 'Удаление шаблонов', 'data', '', 'template_delete', 1);

/* r98 */
INSERT INTO `ac_data_fields` (`id`, `num`, `name`, `title`, `module`, `data_name`, `type`, `options`, `attributes`, `default_value`, `status`, `active`) VALUES
(72, 72, 'url', 'ссылка', 'data', 'data_type', 'textbox', '', '', '', '', 1);

/* r96 */
ALTER TABLE `ac_data_types` ADD `url` TEXT NOT NULL AFTER `module`;
UPDATE `ac_data_types` SET `url`='pages' WHERE `name`='page';
CREATE TABLE IF NOT EXISTS `ac_data_templates` (
  `id` int(10) NOT NULL,
  `name` text NOT NULL,
  `text` text NOT NULL,
  `fields` text NOT NULL,
  `page_template` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `ac_data_templates` (`id`, `name`, `text`, `fields`, `page_template`) VALUES
(1, 'default_list', '<ul>\r\n{foreach from=$items item=item}\r\n    <li>{$item.title}</li>\r\n{/foreach}\r\n</ul>', 'title', 'main'),
(2, 'page_list', '<ul>\r\n{foreach from=$items item=item}\r\n    <li><a href="{$item.url}">{$item.title}</a></li>\r\n{/foreach}\r\n</ul>', 'title,url', 'main');

/* r90 */
UPDATE `ac_menu` SET `childs`='add,edit,edit_profile,list_permissions,edit_permission' WHERE `name`='users';

/* r76 */
UPDATE `ac_data_fields` SET `attributes`='class=checkbox' WHERE `id` IN ('5','15','25');
UPDATE `ac_data_fields` SET `attributes`='rows=17 cols=80' WHERE `id` IN ('10','16','46');
UPDATE `ac_data_fields` SET `attributes`='rows=17 cols=80 class=wysiwyg' WHERE `id`='24';
UPDATE `ac_data_fields` SET `default_value`='' WHERE `default_value`='undefined';
UPDATE `ac_data_fields` SET `status`='' WHERE `status`='undefined';
UPDATE `ac_menu` SET `childs`='add,edit,list_fields,edit_field' WHERE `name`='data';

/* r75 */
ALTER TABLE `ac_menu` ADD `childs` TEXT NOT NULL;
UPDATE `ac_menu` SET `childs`='add,edit' WHERE `name` IN ('css','templates','pages','menu');
UPDATE `ac_menu` SET `childs`='add,edit,list_permissions,edit_permission' WHERE `name`='users';

/* r65 */
CREATE TABLE IF NOT EXISTS `ac_files_temp` ( `id` int(10) NOT NULL, `user` text NOT NULL, `name` text NOT NULL, `path` text NOT NULL,  `size` int(10) NOT NULL, `created` int(10) NOT NULL,  PRIMARY KEY  (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/* r52 */
INSERT INTO `ac_data_fields` (`id`, `num`, `name`, `title`, `module`, `data_name`, `type`, `options`, `attributes`, `default_value`, `status`, `active`) VALUES (63, 63, 'options', 'Отдельный список опций', 'menu', 'menu', 'checkbox', '', '', '', '', 1);
ALTER TABLE `ac_menu` ADD `options` TEXT NOT NULL;
UPDATE `ac_users` SET `permissions`='' WHERE `login`='root';
UPDATE `ac_wysiwygs` SET `default_theme`='normal' WHERE `name`='tinymce';
UPDATE `ac_wysiwygs` SET `default_theme`='Normal' WHERE `name`='ckeditor';
UPDATE `ac_wysiwygs` SET `default_theme`='Normal' WHERE `name`='fckeditor';

/* r28 */
INSERT INTO `ac_menu` (`id`, `parent`, `weight`, `level`, `name`, `title`, `module`, `data`, `page`, `active`, `group_m`) VALUES (26, 1, 10, NULL, 'info', 'Информация о системе', 'info', '', 'list', 1, '');
INSERT INTO `ac_modules` (`id`, `group_m`, `name`, `title`, `caption`, `active`, `install`, `version`) VALUES (16, 'system', 'info', 'Информационный модуль', NULL, 1, 1, 0);
INSERT INTO `ac_users_permissions` (`id`, `title`, `module`, `page`, `option`, `active`) VALUES (57, 'Инфо-центр', 'Info', 'list', 'list', 1);

/* r25 */
UPDATE `ac_users_permissions` SET `option`=page WHERE `option`=''
