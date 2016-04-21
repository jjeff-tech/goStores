<!-- Main wrapper starts -->
<div class="wrapper">
    <!-- Footer starts Here -->
     <?php
     PageContext::renderPostAction('cloudtopmenupage');
     ?>
    <div class="content_whitebg">
    <!-- Header ends -->
    <?php echo $this->_content; ?>    
    </div>
    <!-- Footer starts Here -->
    <?php
    if($this->footerType=='limited')    {
        PageContext::renderPostAction('cloudlimitedfooter');
    }   else    {
        PageContext::renderPostAction('cloudfooter');
    }
    ?>
    <!-- Footer ends -->
</div>
<!-- Main wrapper ends -->
