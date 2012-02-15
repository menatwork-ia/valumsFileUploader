-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_form_field`
-- 

CREATE TABLE `tl_form_field` (
    `val_max_file_length` int(10) unsigned NOT NULL default '0',
    `details_failure_message` char(1) NOT NULL default '',
    `max_file_count` int(2) NOT NULL default '0',
    `val_do_not_overwrite` varchar(32) NOT NULL default '',
    `val_store_file` char(1) NOT NULL default '',
    `val_uploader_debug` char(1) NOT NULL default '',
    `val_init_text` text NULL,
    `val_drop_text` text NULL,
    `resize_resolution` char(1) NOT NULL default '',
    `val_image_size` varchar(64) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
    `uploader` varchar(32) NOT NULL default '',
    `details_failure_message` char(1) NOT NULL default '',
    `max_file_count` int(2) NOT NULL default '0',
    `do_not_overwrite` char(1) NOT NULL default '',
    `do_not_overwrite_type` varchar(128) NOT NULL default 'useSuffix',
    `uploader_debug` char(1) NOT NULL default '',
    `resize_resolution` char(1) NOT NULL default '',
    `val_image_size` varchar(64) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;