<html>
    <body>
        <?php
        $fp = @fsockopen('localhost',80);
	if ( !$fp ){
		return;
	}
        ?>
    </body>
</html>