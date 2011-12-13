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
    `val_do_not_overwrite` varchar(32) NOT NULL default '',
    `val_store_file` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
    `uploader` varchar(128) NOT NULL default 'default',
    `do_not_overwrite` char(1) NOT NULL default '',
    `do_not_overwrite_type` varchar(128) NOT NULL default 'useSuffix',
    `uploader_debug` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;