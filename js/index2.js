function capFirstLetter(string) {
    return (string.charAt(0).toUpperCase()) + (string.slice(1))
}

$.post("./server/get.php", { action: "getReturnAddressAll" }).then((data)=>{
    try {
        returnAddressAll = JSON.parse(data)
        returnAddressAll.forEach((ra, i) => {
            option = `<option value="${ra.id}" data-address="${ra.address}" ${ i == 0 ? "selected" : ""}>${ra.name}</option>`
            $("#return-address-select").append(option)
        });
        
        setReturnAddress()
    } catch (error) {
        console.log(data, e);
    }
})

$.post("./server/get.php", {action: "getTypes"}, (data)=>{
    // console.log(data);
    productTypes = JSON.parse(data)
    productTypes.forEach((el, i) => {
        option = `<option value="${el.productType}" data-niche="${el.nicheType}" ${ i == 0 ? "selected" : ""}>${capFirstLetter(el.productType)}</option>`
        $("#niche-select").append(option)
    })

    currentNicheType = $("#niche-select").find(":selected").attr("data-niche")
    $("#niche-select").attr("data-niche-type", currentNicheType)

    refreshTemplateList(currentNicheType)
})

function refreshTemplateList(nicheType) {
    $("#template-select").html("")
    
    $.post("./server/get.php", {action: "getTemplates", nicheType}, (data)=>{
        templates = JSON.parse(data)

        templates.forEach((el, i) => {
            optionText = (el.url).replace("https://github.com/YanaEgorova/", "")
            option = `<option value='${el.url}' data-id="${el.id}" data-preview="${el.preview}" ${ i == 0 ? "selected" : ""}>${ optionText }</option>`
            $("#template-select").append(option)
        })

        updateTemplateLink()
    })

}

$("#niche-select").on("change", ()=>{
    currentNicheType = $("#niche-select").attr("data-niche-type")
    newNicheType = $("#niche-select").find(":selected").attr("data-niche")
    if(currentNicheType != newNicheType){
        $("#niche-select").attr("data-niche-type", newNicheType)
        refreshTemplateList(newNicheType)
    }
})

function setReturnAddress() {
    returnAddress = $("#return-address-select").find(":selected").attr("data-address")
    $("#return-address-target").html(returnAddress)
    $("#return-address-target").attr("title", returnAddress)
}

function updateTemplateLink() {
    previewUrl = $("#template-select").find(":selected").attr("data-preview")
    $("#target-template-url").attr("href", previewUrl)
    $("#target-template-url").attr("target", "_blank")
}

function selectRandomTemplate() {
    templateOptions = $("#template-select").children()
    $(templateOptions[getRandomInt(0, templateOptions.length)]).attr("selected", "selected")
    updateTemplateLink()
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function create() {
    current_session = getCookie("current_session");
    // alert(current_session)

    infoData = $("#mainForm").serializeArray()
    console.log(infoData);
}

$("#return-address-select").on("change", ()=>{ setReturnAddress() })
$("#template-select").on("change", ()=>{ updateTemplateLink() })
$("#products-select").on("change", ()=>{ 
    selected = $("#products-select").val()
    if(selected == "upload"){ $("#formFileDirectory").css("display", "block") }
    else { $("#formFileDirectory").css("display", "none") }
})