<body onLoad="document.frmReplied.submit()">
 <?php echo ("<form name=frmReplied  action='". $_SESSION['sess_backurl_reply_success'] ."' method=post ><input type=hidden name=mt value='y'></form>"); ?>
</body>