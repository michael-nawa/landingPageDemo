var formdata = new FormData();
var url = '';
var formId;

var ApiPath = "http://localhost/web_clientProjects/MartinProject/API/API.php?apicall=";
// var ApiPath = "https://mwila-university.000webhostapp.com/MartinDB/API/API.php?apicall=";

(function ($) {
    "use strict";

    // Add active state to sidbar nav links
    var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
    $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function () {
        if (this.href === path) {
            $(this).addClass("active");
        }
    });

    // Toggle the side navigation
    $("#sidebarToggle").on("click", function (e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });
})(jQuery);

// fetch function
function sendRequest(formdata, url) {
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

    var requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: formdata,
        redirect: 'follow'
    };

    return fetch(`${ApiPath + url}`, requestOptions)
        .then(response => response.json())
        .then(result => {
            return result;
        })
        .catch(error => console.log('error', error));
}

//post function
function postRequest(url, form_data, reloadPage) {
    $.ajax({
        type: "post",
        url: `${ApiPath + url}`,
        data: form_data,
        contentType: false,
        processData: false,
        cache: false,
        success: function (html) {
            var jsonResult = JSON.parse(html);
            if (!jsonResult.error) {
                window.location = reloadPage;
            } else {
                alertify.error(`Error: ${!jsonResult.message}`);
            };
        },
        error: function (x, e) {
            alertify.error('Unknown Error.\n' + x.responseText);
        }
    });

}

//open dialog model
function modal(call, src, id) {
    var formdata = new FormData();
    formdata.append("src", src);
    formdata.append("id", id);
    formdata.append("call", call);

    $.ajax({
        type: "post",
        url: "includes/dialogs/renderDialog.php",
        data: formdata,
        contentType: false,
        processData: false,
        cache: false,
        success: function (html) {
            $("#dialog_modal").modal();
            $('#modal_content').html(html);
        },
        error: function (x, e) {
            alert('Unknown Error.\n' + x.responseText);
        }
    });
    return false;
}

//dialog form submission 
function dialogSubmission(call, src, Id) {
    var File = document.getElementsByName("File")[0].files[0];
    var Title = document.getElementsByName("Title")[0].value;
    var Description = document.getElementsByName("Description")[0].value;
    var CategoryId = document.getElementsByName("CategoryId")[0].value;
    var Price = document.getElementsByName("Price")[0].value;
    var Quantity = document.getElementsByName("Quantity")[0].value;
    var Names = document.getElementsByName("Names")[0].value;
    var Email = document.getElementsByName("Email")[0].value;
    var Password = document.getElementsByName("Password")[0].value;
    var Roles = document.getElementsByName("Roles")[0].value;
    var Address = document.getElementsByName("Address")[0].value;

    formdata.append("Title", Title);
    formdata.append("Description", Description);
    formdata.append("ImgPath", File);
    formdata.append("CategoryId", CategoryId);
    formdata.append("Price", Price);
    formdata.append("Id", Id);
    formdata.append("Quantity", Quantity);
    formdata.append("Names", Names);
    formdata.append("Email", Email);
    formdata.append("Password", Password);
    formdata.append("Roles", Roles);
    formdata.append("Address", Address);


    switch (call) {
        case 'Products':
            postRequest(`Products&src=${src}`, formdata, 'products.php');
            break;
        case 'ProductCategory':
            postRequest(`ProductCategory&src=${src}`, formdata, 'Category.php');
            break;
        case 'account':
            postRequest(`account&src=${src}`, formdata, 'users.php');
            break;
    }
}

//validation
function validate(src, errorMessage) {
    var state = false;
    if (src == "") {
        state = true;
        alertify.error(errorMessage);
    }
    return state;
}

//delete item
function deleteItem(src, Id, reloadPage) {
    postRequest(`${src}&src=delete&Id=${Id}`, formdata, reloadPage);
}

//get cookie
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "1";
}




