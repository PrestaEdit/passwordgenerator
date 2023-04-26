function passwordGeneratorGenerateCode(size)
{
    var $passwdField = $("#customer_password");

    $passwdField.val('');
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for (var i = 1; i <= size; ++i) {
        $passwdField.val($passwdField.val() + chars.charAt(Math.floor(Math.random() * chars.length)));
    }
    $("#passwd-generate-field").val($passwdField.val());

    $passwdField.keyup();
}
