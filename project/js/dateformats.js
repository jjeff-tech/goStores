$( document ).ready(function() {
    
    //Sold Templates
    $("#searchField option[value='paidOn']").text('Purchase Date (YYYY-MM-DD)');
    
    //Invoices
    $("#searchField option[value='dGeneratedDate']").text('Generated On (YYYY-MM-DD)');
    $("#searchField option[value='dDueDate']").text('Due On (YYYY-MM-DD)');
    $("#searchField option[value='dPayment']").text('Paid On (YYYY-MM-DD)');
    
    
    $('#searchField').css({'width': 232});
});
