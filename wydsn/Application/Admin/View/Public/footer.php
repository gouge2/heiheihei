<div class="footer">
	<div class="pull-right">
        &copy; 2020 <a href="https://http://livedd.com/" target="_blank">版权所有：<?php echo defined('APP_NAME')?APP_NAME:''; ?></a>
	</div>
    <div class="pull-right" style="margin-right: 10px">
        <?php
            $system=new \Admin\Controller\SystemController();
            $res=$system->versionInfo();
            echo $res;
        ?>
    </div>
</div>