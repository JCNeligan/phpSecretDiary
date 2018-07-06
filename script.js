$(".toggleForms").click(function() {
    $("#signUp").toggle();
    $("#logIn").toggle();
})

$("#diary").bind("input propertychange", function() {
    $.ajax({
        method: "POST",
        url: "updatedatabase.php",
        data: { content: $("#diary").val() }
    });
});