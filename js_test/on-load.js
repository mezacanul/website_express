var path = window.location.pathname;
var currentPage = (path.split("/").pop()).split(".")[0];
switch (currentPage) {
    case "preview":
            previewAndEditItem()
        break;
    default:
        break;
}

$.post("get.php", {action: "getTypes"}, (data)=>{
    productTypes = JSON.parse(data)
    productTypes.forEach(el => {
        $("select[name='type']").append(`<option value='${el.productType}'>${el.productType}</option>`)
    })
})

$.post("get.php", {action: "getPriceRanges"}, (data)=>{
    priceRanges = JSON.parse(data)
    priceRanges.forEach(el => {
        $("select[name='priceRange']").append(`<option value='${el.priceId}'>$${el.min} - $${el.max}</option>`)
    })
})