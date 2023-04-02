const productsSelect = $('select[name="products"')
const productsDetails = $(".productsDetails")
const uploadFiles = $(".uploadFiles")

// Initial Styling
productsDetails.css("display", "none")
uploadFiles.css("display", "none")

//Events
productsSelect.change(()=>{
    switch (productsSelect.val()) {
        case "dont":
            productsDetails.css("display", "none")
            uploadFiles.css("display", "none")
            break;
        case "include":
            uploadFiles.css("display", "none")
            productsDetails.css("display", "flex")
            break;
        case "upload":
            productsDetails.css("display", "none")
            uploadFiles.css("display", "flex")
            break;
        default:
            break;
    }
})