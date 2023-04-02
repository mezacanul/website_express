const currentPage = document.URL.split("/").pop()

function capFirstLetter(string) {
    return (string.charAt(0).toUpperCase()) + (string.slice(1))
}

$.post("./server/get.php", {action: "getTypes"}, (data)=>{
    // console.log(data);
    productTypes = JSON.parse(data)
    productTypes.forEach(el => {
        $("select[name='type']").append(`<option value='${el.productType}'>${capFirstLetter(el.productType)}</option>`)
    })
})

$.post("./server/get.php", {action: "getPriceBanks"}, (data)=>{
    priceBanks = JSON.parse(data)
    priceBanks.forEach(el => {
        $("select[name='prices']").append(`<option value='${el.bank}'>${(el.bank).toUpperCase()}</option>`)
    })
})

$.post("./server/get.php", {action: "getTemplates"}, (data)=>{
    templates = JSON.parse(data)
    // console.log(templates);
    templates.forEach(el => {
        optionText = (el.url).replace("https://github.com/YanaEgorova/", "")
        switch (currentPage) {
            case "templates.php":
                $("select[name='template']").append(`<option value="${el.id}" data-url='${el.url}' data-preview="${el.preview}">${ optionText }</option>`)
                break;
            case "":
                $("select[name='template']").append(`<option value='${el.url}' data-id="${el.id}" data-preview="${el.preview}">${ optionText }</option>`)
                break
            default:
                break;
        }
    })

    switch (currentPage) {
        case "templates.php":
            templatId = $("select[name='template']").val()
            getTemplateInfo(templatId)
            break;
        default:
            break;
    }

    previewUrl = $("select[name='template'] option:checked").attr("data-preview")
    $(".templatePreviewLink").attr("href", previewUrl)
    $(".templatePreviewLink").html($("select[name='template'] option:checked").text())
})

$("select[name='template']").change(()=>{
    switch (currentPage) {
        case "templates.php":
            templatId = $("select[name='template']").val()
            getTemplateInfo(templatId)
            break;
        default:
            break;
    }
    previewUrl = $("select[name='template'] option:checked").attr("data-preview")
    $(".templatePreviewLink").attr("href", previewUrl)
    $(".templatePreviewLink").html($("select[name='template'] option:checked").text())
})