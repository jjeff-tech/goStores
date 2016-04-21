INSERT INTO `sptbl_lookup` (`vLookUpName`,`vLookUpValue`) VALUES ('HomeFooterContent','How can we help you?');
Update sptbl_lookup set vLookUpValue='en' where vLookUpName='DefaultLang';
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='LangChoice';
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='AutoLock';
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='VerifyTemplate';
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='VerifyKB';
Update `sptbl_lookup` set `vLookUpValue`=4.3 where `vLookUpName`='Version';
INSERT INTO `sptbl_lookup` (`vLookUpName`) VALUES ('SMTPUsername');
INSERT INTO `sptbl_lookup` (`vLookUpName`) VALUES ('SMTPPassword');
INSERT INTO `sptbl_lookup` (`vLookUpName`) VALUES ('SMTPEnableSSL'); 

INSERT INTO `sptbl_templates` (`nTemplateId`, `dDate`, `vTemplateTitle`, `tTemplateDesc`, `nStaffId`, `vStatus`) VALUES ('1', '2013-07-26 00:00:00', 'installer_mail', '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<table width="600" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #ccc; background-color:#FDFDFD; ">
    <tbody>
        <tr>
            <td height="5" colspan="3"></td>
        </tr>
        <tr>
            <td width="5"></td>
            <td width="586"><table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td height="56" style="background-color:#324148; ">;
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td width="2%" height="80">&#160;</td>
                            <td width="37%">{SITE_LOGO}</td>
                            <td width="61%"><p style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#CCCCCC; text-align:right; padding:0 15px 0 0; ">{Date}</p></td>
                        </tr>
                    </tbody>
                </table></td>
        </tr>
        <tr>
            <td height="10">&#160;</td>
        </tr>
        <tr>
            <td>&#160;</td>
        </tr>
        <tr>
            <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td width="2%">&#160;</td>
                            <td width="28%">{MAIL_CONTENT}</td>
                            <td width="2%">&#160;</td>
                        </tr>
                        <tr>
                            <td>&#160;</td>
                            <td>&#160;</td>
                            <td>&#160;</td>
                        </tr>
                        <tr>
                            <td>&#160;</td>
                            <td>&#160;</td>
                            <td>&#160;</td>
                        </tr>
                        <tr>
                            <td>&#160;</td>
                            <td>&#160;</td>
                            <td>&#160;</td>
                        </tr>
                    </tbody>
                </table></td>
        </tr>
        <tr>
            <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td height="30" style="background-color:#324148; "><p style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:0 0 0 15px; ">{COPYRIGHT}</p></td>
                        </tr>
                    </tbody>
                </table></td>
        </tr>
    </tbody>
                </table></td>
                <td width="4">&#160;</td>
        </tr>
        <tr>
            <td height="10" colspan="3">&#160;</td>
        </tr>     </tbody> </table> <p>&#160;</p></meta>', '0', '0');