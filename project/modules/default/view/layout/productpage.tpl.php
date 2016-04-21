<div class="outer_wrapper">
    <?php
    PageContext::renderPostAction('cloudtopmenupage');
    ?>
    <?php echo $this->_content; ?>  
    <?php
    if ($this->footerType == 'limited') {
        PageContext::renderPostAction('cloudlimitedfooter');
    } else {
        PageContext::renderPostAction('cloudfooterpage');
    }
    ?>
   
</div>




