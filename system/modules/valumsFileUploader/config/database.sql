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
    `maxfilelength` int(10) unsigned NOT NULL default '0',
    `doNotOverwriteExt` varchar(32) NOT NULL default '',
    `valumsStoreFile` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `valumsFileUploader` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;