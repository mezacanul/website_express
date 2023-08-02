const currentPage = document.URL.split("/").pop()

// refreshTemplateList(currentPage, isSpecial = "false")

function capFirstLetter(string) {
    return (string.charAt(0).toUpperCase()) + (string.slice(1))
}

$.post("./server/get.php", {action: "getTypes"}, (data)=>{
    // console.log(data);
    productTypes = JSON.parse(data)
    productTypes.forEach(el => {
        $("select[name='type']").append(`<option data-niche="${el.nicheType}" value='${el.productType}'>${capFirstLetter(el.productType)}</option>`)
    })
    if(currentPage != "templates.php"){
        nicheType = $("select[name='type']").find(":selected").attr("data-niche")
        $("select[name='type']").attr("data-niche", nicheType)
    } else { nicheType = "" }
    refreshTemplateList(currentPage, nicheType)
})

$.post("./server/get.php", {action: "getPriceBanks"}, (data)=>{
    priceBanks = JSON.parse(data)
    priceBanks.forEach(el => {
        $("select[name='prices']").append(`<option value='${el.bank}'>${(el.bank).toUpperCase()}</option>`)
    })
})

function refreshTemplateList(currentPage, nicheType) {
    $("select[name='template']").html("")
    $.post("./server/get.php", {action: "getTemplates", nicheType}, (data)=>{
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

        nicheType = $("select[name='type']").find(":selected").attr("data-niche")
        $("select[name='type']").attr("data-niche", nicheType)
    
        previewUrl = $("select[name='template'] option:checked").attr("data-preview")
        $(".templatePreviewLink").attr("href", previewUrl)
        $(".templatePreviewLink").html($("select[name='template'] option:checked").text())
    })
}

$("select[name='type']").change(()=>{
    if(currentPage != "templates.php"){
        currentNicheType = $("select[name='type']").attr("data-niche")
        newNicheType = $("select[name='type']").find(":selected").attr("data-niche")
        if(currentNicheType != newNicheType){
            refreshTemplateList(currentPage, newNicheType)
        }
    }
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