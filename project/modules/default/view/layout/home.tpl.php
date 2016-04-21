<!-- Main wrapper starts -->
<div class="wrapper">
    <!-- Header starts Here -->
     <?php
     PageContext::renderPostAction('cloudtopmenu');
     ?>
    <!-- Header ends -->
    <?php echo $this->_content; ?>

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
