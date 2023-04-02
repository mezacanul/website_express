// Global Variables

// Routine
$.post("./server/get.php", { action: "getReturnAddressAll" }).then((data)=>{
    // console.log(data);
    try {
        returnAddressAll = JSON.parse(data)
        returnAddressAll.forEach(ra => {
            option = `<option value="${ra.id}" data-address="${ra.address}">${ra.name}</option>`
            $("select[name='returnAddressSelect']").append(option)
        });
        
        setReturnAddress()
    } catch (error) {
        console.log(data, e);
    }
})

// Functions
function setReturnAddress() {
    returnAddress = $("select[name='returnAddressSelect'] option:selected").attr("data-address")

    $("input[name='returnAddress']").val(returnAddress)
    $(".raTarget").html(returnAddress)
}

function setDescriptor() {
    url = $("input[name='url']").val()
    phone = $("input[name='phone']").val()
    descriptorType = $("select[name='descriptorType']").val()
    descriptor = ""
    
    switch (descriptorType) {
        case "alphanumeric":
            nPhone = phone.replaceAll(" ", "")
            
            descriptor = (nPhone + url).substr(0, 22)
            break;
        case "dba":
            nUrl = url.replace(".com", "")
            
            descriptor = (nUrl).substr(0, 22)
            break;
        case "url":
            descriptor = (url).substr(0, 22)
            break;
        case "dashed":
            nPhone = phone.replaceAll(" ", "-")
            
            descriptor = (nPhone + url).substr(0, 22)
            break;
        case "spaced":
            nUrl = url.split(/(?=[A-Z])/)
            spaced = ""

            nUrl.forEach(l => { spaced += (l + " ") })
            spaced = spaced.replace(".com", "")
            spaced = spaced.substr(0, 22)

            descriptor = spaced
            break;
        default: break;
    }

    $(".descTarget").html(descriptor)
    $("input[name='descriptor']").val(descriptor)
}