/**
 * Created by lucabuonomo1 on 08/09/15.
 */
function formhash(form, password) {
    // Crea input in modo da mandare la password gia criptata.
    var p = document.createElement("input");
    // Aggiungi un nuovo elemento al tuo form.
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden"
    p.value = hex_sha512(password.value);
    console.log(p.value);
    // cancello il valore
    password.value = "";
    //mando il form
    form.submit();
}